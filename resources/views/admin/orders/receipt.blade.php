@extends('admin.layouts.app')


@push('page-css')
<style>
    .margin-top {
    margin-top: 1.25rem;
}
.footer {
    font-size: 0.875rem;
    padding: 1rem;
    background-color: rgb(241 245 249);
}
table {
    width: 100%;
    border-spacing: 0;
}
table.products {
    font-size: 0.875rem;
}
table.products tr {
    background-color: rgb(96 165 250);
}
table.products th {
    color: #ffffff;
    padding: 0.5rem;
}
table tr.items {
    background-color: rgb(241 245 249);
}
table tr.items td {
    padding: 0.5rem;
}
.total {
    text-align: right;
    margin-top: 1rem;
    font-size: 1.5rem;
}
</style>
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Receipt Sale</h3>
	{{-- <ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Receipt Sale</li>
	</ul> --}}
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
                <!-- Create Sale -->
                <form method="POST" action="{{route('sales.store')}}">
					@csrf
                    <div class="container">

                        <div class="row justify-content-between">
                            <a class="logo">
                                <img src="@if(!empty(AppSettings::get('logo'))) {{asset('storage/'.AppSettings::get('logo'))}} @else{{asset('assets/img/pharrrlg.png')}} @endif" alt="Logo">
                            </a>

                            <h3 class="page-title">Invoice ID: {{$invoiceId}}</h3>
                        </div>

                        <div class="row mt-4 justify-content-between">
                            <div class="row">
                                <h5 class="font-weight-bold">Sold by:</h5>
                                <p>{{Auth::user()->name}}</p>
                            </div>
                            <div class="row">
                                <h5 class="font-weight-bold">Date:</h5>
                                <p>{{$date}}</p>
                            </div>
                        </div>

                        <div class="margin-top">
                            <table class="products">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                                @foreach ($sales as $key => $sale)
                                <tr class="items">
                                    <td>
                                        {{$sale['product']}}
                                    </td>
                                    <td>
                                        {{$sale['quantity']}}
                                    </td>
                                    <td>
                                        {{AppSettings::get('app_currency', '$')}} {{$sale['discountedPrice']}}
                                    </td>

                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="total">
                            Total: {{AppSettings::get('app_currency', '$')}}{{$totalPrice}}
                        </div>

                        <div class="footer margin-top">
                            <div>Thank you</div>
                            <div>&copy; {{AppSettings::get('app_name', 'App')}}</div>
                        </div>

                        <input name="sales[]" value={{$sales}} hidden>
                        <input name="totalPrice" value={{$totalPrice}} hidden>
                        <input name="invoiceId" value={{$invoiceId}} hidden>
                        <button type="submit" class="btn btn-success btn-block mt-3">Order</button>
                    </div>

				</form>
                <!--/ Create Sale -->
			</div>
		</div><!-- Visit codeastro.com for more projects -->
	</div>
</div>
@endsection


@push('page-js')

<script type="text/javascript">


</script>

@endpush
