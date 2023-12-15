@extends('admin.layouts.app')


@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Receipt</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Receipt</li>
	</ul>
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
