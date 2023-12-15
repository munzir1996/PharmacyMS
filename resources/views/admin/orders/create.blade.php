@extends('admin.layouts.app')


@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Create Sale</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Create Sale</li>
	</ul>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
                <!-- Create Sale -->
                <form method="GET" action="{{route('sales.receipt')}}">
                    {{-- <form method="POST" action="{{route('sales.store')}}"> --}}
					{{-- @csrf --}}
					<div class="row form-row" id="dynamicForm">

						<div class="col-5">
							<div class="form-group main">
								<label>Product <span class="text-danger">*</span></label>
								<select class="select2 form-select form-control" name="sales[0][product]" required>
									<option disabled selected > Select Product</option>
									@foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->purchase->product}} -- {{AppSettings::get('app_currency', '$')}}{{$product->discountedPrice}}</option>
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
						<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
					</div>

					<button type="submit" class="btn btn-success btn-block">Save Changes</button>

				</form>
                <!--/ Create Sale -->
			</div>
		</div><!-- Visit codeastro.com for more projects -->
	</div>
</div>
@endsection


@push('page-js')

<script type="text/javascript">

    var i = 0;

    $("#add").click(function(){

        ++i;

		$("#dynamicForm").append("<div class='col-12 row removeSale' id='test'><div class='col-5'><div class='form-group'><label>Product <span class='text-danger'>*</span></label><select class='select2 form-select form-control' name='sales["+i+"][product]'><option disabled selected > Select Product</option>@foreach ($products as $product)<option value='{{$product->id}}'>{{$product->purchase->product}} -- {{AppSettings::get('app_currency', '$')}}{{$product->discountedPrice}}</option>@endforeach</select></div></div><div class='col-5'><div class='form-group'><label>Quantity</label><input type='number' value='1' class='form-control' name='sales["+i+"][quantity]'></div></div><div class='col-2 align-self-center'><button type='button' class='btn btn-danger remove-tr'>Remove</button></div></div>");
		$('.select2').select2();
	});

    $(document).on('click', '.remove-tr', function(){
        // $('#test').remove();
		$(this).closest('.removeSale').remove();
        // $(this).parents('tr').remove();
    });

	// $('.select2').select2();
</script>

@endpush
