<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'orders';

        if($request->ajax()){
            $orders = Order::latest()->get();

            return DataTables::of($orders)
                    ->addIndexColumn()
                    ->addColumn('id',function($order){
                        return $order->id;
                    })
                    ->addColumn('invoice_id',function($order){
                        return $order->invoice_id;
                    })
                    ->addColumn('total_price',function($order){
                        return $order->total_price;
                    })
                    ->addColumn('date',function($order){
                        return $order->date;
                    })
                    ->addColumn('user',function($order){
                        return $order->user->name;
                    })
                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("orders.show", $row->id).'" class="editbtn"><button class="btn btn-warning"><i class="fe fe-document text-white"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('orders.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-order')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-order')) {
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        }
        return view('admin.orders.index',compact(
            'title',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('admin.orders.receipt', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Order::findOrFail($request->id)->delete();
    }

    public function x(Request $request){
        $title = 'orders reports';
        return view('admin.orders.reports',[
            'title' => $title,
        ]);
    }

    public function generateReport(Request $request)
    {
        $this->validate($request,[
            'from_date' => 'required',
            'to_date' => 'required',
            'user_id' => 'nullable',
        ]);

        $title = 'orders reports';

        if (!empty($request->user_id)) {
            $orders = Order::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->Where('user_id', $request->user_id)->get();
        } else {
            $orders = Order::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->get();
        }

        return view('admin.orders.reports',compact(
            'orders','title'
        ));
    }
}
