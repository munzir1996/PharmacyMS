<!-- Add Sale Modal -->
<div class="modal fade" id="add_sales" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sell Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{route('sales.receipt')}}">
                {{-- <form method="POST" action="{{route('sales.store')}}"> --}}
                    {{-- @csrf --}}
                    <div class="row form-row" id="dynamicFormModal">
                        <div class="col-5">
                            <div class="form-group">
                                <label>Product <span class="text-danger">*</span></label>
                                <select class="select2 form-select form-control" name="sales[0][product]" required>

                                    <option disabled selected > Select Product</option>

                                    @foreach (\App\Models\Product::with(['purchase',])->whereHas('purchase', function($query) {
                                        $query->where('quantity', '>', 0);
                                    })->get() as $product)
                                        {{-- @if (!empty($product->purchase)) --}}
                                            {{-- @if (!($product->purchase->quantity <= 0)) --}}
                                                <option value="{{$product->id}}">{{$product->purchase->product}} -- {{AppSettings::get('app_currency', '$')}}{{$product->discountedPrice}}</option>
                                            {{-- @endif
                                        @endif --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" value="1" class="form-control" name="sales[0][quantity]" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
						<button type="button" name="add" id="addModal" class="btn btn-success">Add More</button>
					</div>

					<button type="submit" class="btn btn-success btn-block">Save Changes</button>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Sale Modal -->
