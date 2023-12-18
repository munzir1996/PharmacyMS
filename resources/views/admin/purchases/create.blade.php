@extends('admin.layouts.app')

@push('page-css')
	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Add Purchase</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Add Purchase</li>
	</ul>
</div>
@endpush


@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">

				<!-- Add Medicine -->
				<form method="post" enctype="multipart/form-data" autocomplete="off" action="{{route('purchases.store')}}">
					@csrf
					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label>Medicine Name<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="product" required>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Category <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="category" required>
										@foreach ($categories as $category)
											<option value="{{$category->id}}">{{$category->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Supplier <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="supplier" required>
										@foreach ($suppliers as $supplier)
											<option value="{{$supplier->id}}">{{$supplier->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label>Cost Price<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="cost_price" required>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Quantity<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="quantity" required>
								</div>
							</div>
                            <div class="col-lg-4">
								<div class="form-group">
									<label>Unit Price<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="unit_price" required>
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Expire Date<span class="text-danger">*</span></label>
									<input class="form-control" type="date" name="expiry_date" required>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Medicine Image</label>
									<input type="file" name="image" class="form-control">
								</div>
							</div>
						</div>
					</div>


					<div class="submit-section">
						<button class="btn btn-success submit-btn" type="submit" >Submit</button>
					</div>
				</form>
				<!-- /Add Medicine -->
			<!-- Visit codeastro.com for more projects -->
			</div>
		</div>
	</div>
</div>
@endsection

@push('page-js')
	<!-- Datetimepicker JS -->
	<script src="{{asset('assets/js/moment.min.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
@endpush

