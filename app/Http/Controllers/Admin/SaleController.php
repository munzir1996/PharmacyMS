<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Events\PurchaseOutStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use stdClass;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'sales';
        $sales = Sale::latest()->get();

        if($request->ajax()){
            $sales = Sale::latest();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('product',function($sale){
                        $image = '';
                        if(!empty($sale->product)){
                            $image = null;
                            if(!empty($sale->product->purchase->image)){
                                $image = '<span class="avatar avatar-sm mr-2">
                                <img class="avatar-img" src="'.asset("storage/purchases/".$sale->product->purchase->image).'" alt="image">
                                </span>';
                            }
                            return $sale->product->purchase->product. ' ' . $image;
                        }
                    })
                    ->addColumn('id',function($sale){
                        return $sale->id;
                    })
                    ->addColumn('invoice_id',function($sale){
                        return $sale->order->invoice_id;
                    })
                    ->addColumn('total_price',function($sale){
                        return $sale->total_price;
                    })
                    ->addColumn('date',function($row){
                        return date_format(date_create($row->created_at),'d M, Y H:i:s');
                    })
                    ->addColumn('user',function($sale){
                        return $sale->user->name;
                    })
                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("sales.edit", $row->id).'" class="editbtn"><button class="btn btn-info"><i class="fas fa-edit"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('sales.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-sale')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-sale')) {
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.sales.index',compact(
            'title','products',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create sales';

        $products = Product::with(['purchase',])->whereHas('purchase', function($query) {
            $query->where('quantity', '>', 0);
        })->get();

        return view('admin.sales.create',compact(
            'title','products'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $salesCollect = collect(
            [
                'sales' => json_decode($request->sales[0]),
                'totalPrice' => $request->totalPrice,
                'invoiceId' => $request->invoiceId,
                'date' => $request->date,
            ]
        );

        $order = Order::create([
            'invoice_id' => $salesCollect['invoiceId'],
            'user_id' => Auth::user()->id,
            'date' => $salesCollect['date'],
        ]);

        foreach ($salesCollect['sales'] as $sale) {
            $sold_product = Product::findOrFail($sale->product_id);

            /**update quantity of
                sold item from
             purchases
            **/

            $purchased_item = Purchase::findOrFail($sold_product->purchase->id);
            $new_quantity = ($purchased_item->quantity) - ($sale->quantity);
            $notification = '';

            if (!($new_quantity < 0)){

                $purchased_item->update([
                    'quantity'=>$new_quantity,
                ]);

                /**
                 * calcualting item's total price
                **/

                // munzir
                $total_price = ($sale->quantity) * ($sold_product->discountedPrice);

                Sale::create([
                    'order_id' => $order->id,
                    'user_id'=>Auth::user()->id,
                    'product_id'=>$sale->product_id,
                    'quantity'=>$sale->quantity,
                    'total_price'=>$total_price,
                ]);

                $notification = notify("Product has been sold");
            }

            if($new_quantity <=1 && $new_quantity !=0){
                // send notification
                $product = Purchase::where('quantity', '<=', 1)->first();
                event(new PurchaseOutStock($product));
                // end of notification
                $notification = notify("Product (".$sold_product->purchase->product.") is running out of stock!!!");

            }

        }

        return redirect()->route('sales.create')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        $title = 'edit sale';
        $products = Product::get();
        return view('admin.sales.edit',compact(
            'title','sale','products'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|integer|min:1'
        ]);
        $sold_product = Product::find($request->product);
        /**
         * update quantity of sold item from purchases
        **/
        $purchased_item = Purchase::find($sold_product->purchase->id);
        if(!empty($request->quantity)){
            $new_quantity = ($purchased_item->quantity) - ($request->quantity);
        }
        $new_quantity = $sale->quantity;
        $notification = '';
        if (!($new_quantity < 0)){
            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            if(!empty($request->quantity)){
                $total_price = ($request->quantity) * ($sold_product->discountedPrice);
            }
            $total_price = $sale->total_price;
            $sale->update([
                'product_id'=>$request->product,
                'quantity'=>$request->quantity,
                'total_price'=>$total_price,
            ]);

            $notification = notify("Product has been updated");
        }
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification
            $product = Purchase::where('quantity', '<=', 1)->first();
            event(new PurchaseOutStock($product));
            // end of notification
            $notification = notify("Product is running out of stock!!!");

        }
        return redirect()->route('sales.index')->with($notification);
    }

    /**
     * Generate sales reports index
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request){
        $title = 'sales reports';
        return view('admin.sales.reports',compact(
            'title'
        ));
    }

    /**
     * Generate sales report form post
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request){
        $this->validate($request,[
            'from_date' => 'required',
            'to_date' => 'required',
            'user_id' => 'nullable',
        ]);

        $title = 'sales reports';

        if (!empty($request->user_id)) {
            $sales = Sale::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->Where('user_id', $request->user_id)->get();
        } else {
            $sales = Sale::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->get();
        }

        return view('admin.sales.reports',compact(
            'sales','title'
        ));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Sale::findOrFail($request->id)->delete();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function today(Request $request)
    {
        $title = 'sales';
        if($request->ajax()){
            $sales = Sale::whereDate('created_at','=',Carbon::now())->get();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('product',function($sale){
                        $image = '';
                        if(!empty($sale->product)){
                            $image = null;
                            if(!empty($sale->product->purchase->image)){
                                $image = '<span class="avatar avatar-sm mr-2">
                                <img class="avatar-img" src="'.asset("storage/purchases/".$sale->product->purchase->image).'" alt="image">
                                </span>';
                            }
                            return $sale->product->purchase->product. ' ' . $image;
                        }
                    })
                    ->addColumn('total_price',function($sale){
                        return settings('app_currency','$').' '. $sale->total_price;
                    })
                    ->addColumn('date',function($row){
                        return date_format(date_create($row->created_at),'d M, Y H:i:s');
                    })
                    ->addColumn('user',function($sale){
                        return $sale->user->name;
                    })
                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("sales.edit", $row->id).'" class="editbtn"><button class="btn btn-info"><i class="fas fa-edit"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('sales.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-sale')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-sale')) {
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
    }

    public function receipt(Request $request)
    {
        $this->validate($request,[
            'sales.*.product'=>'required',
            'sales.*.quantity'=>'required|integer|min:1'
        ]);

        $salesArray = [];

        foreach ($request->sales as $key => $sale) {

            $sold_product = Product::findOrFail($sale['product']);

            /**update quantity of
                sold item from
             purchases
            **/

            $purchased_item = Purchase::findOrFail($sold_product->purchase->id);
            $new_quantity = ($purchased_item->quantity) - ($sale['quantity']);
            $notification = '';

            if($new_quantity <=1 && $new_quantity !=0){
                // send notification
                $product = Purchase::where('quantity', '<=', 1)->first();
                event(new PurchaseOutStock($product));
                // end of notification
                $notification = notify("Product (".$sold_product->purchase->product.") is running out of stock!!!");

                return redirect()->route('sales.create')->with($notification);
            }

            $salesArray[$key]['product_id'] = $sale['product'];
            $salesArray[$key]['product'] = $sold_product->purchase->product;
            $salesArray[$key]['quantity'] = $sale['quantity'];
            $salesArray[$key]['discountedPrice'] = $sale['quantity'] * $sold_product->discountedPrice;
        }

        $salesCollect = collect($salesArray);
        $totalPrice = $salesCollect->sum('discountedPrice');

        $invoiceId = Auth::user()->id.'-'.substr(Auth::user()->name, 0, 3).'-'.random_int(100000, 999999);

        return view('admin.sales.receipt', [
            'date' => date('Y-m-d H:i:s'),
            'sales' => $salesCollect,
            'totalPrice' => $totalPrice,
            'invoiceId' => $invoiceId,
        ]);

    }
}
