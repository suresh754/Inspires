@extends("la.layouts.app")

@section("contentheader_title", "Students")
@section("contentheader_description", "Students listing")
@section("section", "Students")
@section("sub_section", "Listing")
@section("htmlheader_title", "Students Listing")

@section("headerElems")
@la_access("Students", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Student</button>
@endla_access
@endsection

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

<form>
<div class="row">
<div class="col-sm-4">

 <div class="form-group">
  <label for="stream">Select Class:</label>
  <select class="form-control" id="stream">
   <option value="">Select Stream</option>
    @foreach($streams as $stream) 
    <option value={{$stream->id}}>{{$stream->name}}</option>
    
   @endforeach
  </select>
</div>
</div>
</div>	
</form>	

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Students", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Student</h4>
			</div>
			{!! Form::open(['action' => 'LA\StudentsController@store', 'id' => 'student-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
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
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>

var stream = -1 ;
$(function generate($stream) {
	$("#example1").DataTable({
		//"dom" : '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/student_dt_ajax') }}"+"/"+stream,
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#student-add-form").validate({
		
	});
});

$("#stream").change(function() {
	var table = $('#example').DataTable();
	alert(this.value);
	alert(table.search( this.value ).draw());
	alert("hello");
});


</script>
@endpush
