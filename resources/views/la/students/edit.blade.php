@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/students') }}">Student</a> :
@endsection
@section("contentheader_description", $student->$view_col)
@section("section", "Students")
@section("section_url", url(config('laraadmin.adminRoute') . '/students'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Students Edit : ".$student->$view_col)

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
				{!! Form::model($student, ['route' => [config('laraadmin.adminRoute') . '.students.update', $student->id ], 'method'=>'PUT', 'id' => 'student-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'first_name')
					@la_input($module, 'last_name')
					@la_input($module, 'mobile')
					@la_input($module, 'mobile2')
					@la_input($module, 'gender')
					@la_input($module, 'email')
					@la_input($module, 'email2')
					@la_input($module, 'registration_no')
					@la_input($module, 'address')
					@la_input($module, 'about')
					@la_input($module, 'dob')
					@la_input($module, 'admission_date')
					@la_input($module, 'guardian')
					@la_input($module, 'stream')
					@la_input($module, 'section')
					@la_input($module, 'school')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/students') }}">Cancel</a></button>
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
	$("#student-edit-form").validate({
		
	});
});
</script>
@endpush
