@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/streams') }}">Stream</a> :
@endsection
@section("contentheader_description", $stream->$view_col)
@section("section", "Streams")
@section("section_url", url(config('laraadmin.adminRoute') . '/streams'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Streams Edit : ".$stream->$view_col)

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
				{!! Form::model($stream, ['route' => [config('laraadmin.adminRoute') . '.streams.update', $stream->id ], 'method'=>'PUT', 'id' => 'stream-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'name')
					@la_input($module, 'school')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/streams') }}">Cancel</a></button>
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
	$("#stream-edit-form").validate({
		
	});
});
</script>
@endpush
