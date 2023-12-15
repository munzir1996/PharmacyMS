@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Purchase</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Purchase</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('purchases.create')}}" class="btn btn-success float-right mt-2">Add New</a>
</div>
@endpush
<!-- Visit codeastro.com for more projects -->
@section('content')
<div class="row">
	<div class="col-md-12">

		<!-- Recent Orders -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="purchase-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>ID</th>
								<th>Medicine Name</th>
								<th>Category</th>
								<th>Supplier</th>
								<th>Purchase Cost</th>
								<th>Quantity</th>
								<th>Expire Date</th>
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
                        </tfoot>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /Recent Orders -->

	</div>
</div>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#purchase-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('purchases.index')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'product', name: 'product'},
                {data: 'category', name: 'category'},
                {data: 'supplier', name: 'supplier'},
                {data: 'cost_price', name: 'cost_price'},
                {data: 'quantity', name: 'quantity'},
				{data: 'expiry_date', name: 'expiry_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        nb_cols = api.columns().nodes().length;
                        var j = 4;
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
