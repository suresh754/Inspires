@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/guardians') }}">Guardian</a> :
@endsection
@section("contentheader_description", $guardian->$view_col)
@section("section", "Guardians")
@section("section_url", url(config('laraadmin.adminRoute') . '/guardians'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Guardians Edit : ".$guardian->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($guardian, ['route' => [config('laraadmin.adminRoute') . '.guardians.update', $guardian->id ], 'method'=>'PUT', 'id' => 'guardian-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'guardian')
					@la_input($module, 'last_name')
					@la_input($module, 'school')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/guardians') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#guardian-edit-form").validate({
		
	});
});
</script>
@endpush
