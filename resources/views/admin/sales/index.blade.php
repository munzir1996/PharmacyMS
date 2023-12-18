@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Sales</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Sales</li>
	</ul>
</div>
@can('create-sale')
<div class="col-sm-5 col">
	<a href="{{route('sales.create')}}" class="btn btn-success float-right mt-2">Add Sale</a>
</div>
@endcan
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
		<!--  Sales -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="sales-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>ID</th>
								<th>Invoice ID</th>
								<th>Medicine Name</th>
								<th>Quantity</th>
								<th>Total Price</th>
								<th>Total Profit</th>
								<th>Date</th>
								<th>User</th>
								<th class="action-btn">Action</th>
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
                            <th></th>
                            <th></th>
                        </tfoot>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- / sales -->

	</div>
</div>


@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('sales.index')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'product', name: 'product'},
                {data: 'quantity', name: 'quantity'},
                {data: 'total_price', name: 'total_price'},
                {data: 'total_profit', name: 'total_profit'},
				{data: 'date', name: 'date'},
				{data: 'user', name: 'user'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        nb_cols = api.columns().nodes().length;
                        var j = 3;
                        while (j <= 5) {
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
