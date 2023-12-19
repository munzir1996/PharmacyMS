@extends('admin.layouts.app')

<x-assets.datatables />


@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Sales Reports</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Generate Sales Reports</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#generate_report" data-toggle="modal" class="btn btn-success float-right mt-2">Generate Report</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">

		@isset($sales)
            <!--  Sales Report -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sales-table" class="datatable table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Medicine Name</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Total Profit</th>
                                    <th>Date</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <th>Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                            <tbody>
                                @foreach ($sales as $key => $sale)
                                    @if (!(empty($sale->product->purchase)))
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                {{$sale->product->purchase->product}}
                                                @if (!empty($sale->product->purchase->image))
                                                    <span class="avatar avatar-sm mr-2">
                                                    <img class="avatar-img" src="{{asset("storage/purchases/".$sale->product->purchase->image)}}" alt="image">
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{$sale->quantity}}</td>
                                            <td>{{($sale->total_price)}}</td>
                                            <td>{{($sale->total_profit)}}</td>
                                            <td>{{date_format(date_create($sale->created_at),"d M, Y")}}</td>
                                            <td>{{$sale->user->name}}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- / sales Report -->
        @endisset


	</div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generate_report" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Generate Report</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('sales.report')}}">
					@csrf
					<div class="row form-row">
						<div class="col-12">
							<div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>User <span class="text-primary"></span></label>
                                        <select class="select2 form-select form-control" name="user_id">
											<option value=""></option>
                                            @foreach (\App\Models\User::get() as $user)
                                                @if (!empty($user))
                                                    {{-- @if (!($product->purchase->quantity <= 0)) --}}
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    {{-- @endif --}}
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
								<div class="col-6">
									<div class="form-group">
										<label>From<span class="text-danger">*</span></label>
										<input type="date" name="from_date" class="form-control from_date">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>To<span class="text-danger">*</span></label>
										<input type="date" name="to_date" class="form-control to_date">
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-success btn-block submit_report">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Generate Modal -->
@endsection

@push('page-js')
<script>
    $(document).ready(function(){
        $('#sales-table').DataTable({
			dom: 'Bfrtip',
			buttons: [
				{
				extend: 'collection',
				text: 'Export Data',
				buttons: [
					{
						extend: 'pdf',
                        footer: true,
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						},
                        stripHtml: false
					},
					{
						extend: 'excel',
                        footer: true,
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						},
                        stripHtml: false
					},
					{
						extend: 'csv',
                        footer: true,
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						},
                        stripHtml: false
					},
					{
						extend: 'print',
                        footer: true,
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						},
					}
				]
				}
			],
            "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        nb_cols = api.columns().nodes().length;
                        var j = 2;
                        while (j <= 4) {
                            var pageTotal = api
                                .column(j, {page: 'current'})
                                .data()
                                .reduce(function (a, b) {
                                    var x = parseFloat(a);
                                    var y = parseFloat(b)
                                    // var y = isNaN(parseFloat(b))?0:parseFloat(b);
                                    let result=x + y;
                                    return result
                                }, 0);
                            // Update footer
                            $(api.column(j).footer()).html(pageTotal);
                            j++;
                        }
                    }
		});
    });
</script>
@endpush
