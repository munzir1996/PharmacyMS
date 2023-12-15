@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Orders</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Orders</li>
	</ul>
</div>
@can('create-sale')
{{-- <div class="col-sm-5 col">
	<a href="{{route('sales.create')}}" class="btn btn-success float-right mt-2">Add Sale</a>
</div> --}}
@endcan
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
		<!--  Orders -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="orders-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>ID</th>
								<th>Invoice ID</th>
								<th>Total Price</th>
								<th>Date</th>
								<th>User</th>
								<th class="action-btn">Action</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- / Orders -->

	</div>
</div>


@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('orders.index')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'total_price', name: 'total_price'},
                {data: 'date', name: 'date'},
                {data: 'user', name: 'user'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
</script>
@endpush
