<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
//use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use Dwij\Laraadmin\Helpers\LAHelper;

use App\User;
use App\Models\Stream;
use App\Models\Student;
use App\Role;
use Mail;
use Log;

class StudentsController extends Controller
{
	public $show_action = true;
	public $view_col = 'first_name';
	public $listing_cols = ['id', 'first_name', 'last_name', 'mobile', 'mobile2', 'gender', 'email', 'email2', 'registration_no', 'address', 'about', 'dob', 'admission_date', 'guardian', 'stream', 'section', 'school'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Students', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Students', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Students.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	  
		$module = Module::get('Students');
		
		if(Module::hasAccess($module->id)) {
		    $context_id = Auth::user()->context_id ;
		    $user_type = Auth::user()->type;
		    $table = str_plural(lcfirst($user_type)) ;
		    $school = DB::table($table)->where('id',$context_id)->whereNull('deleted_at')->value('school');
		   
		  // print_r($school);die;
		   // $streams = Stream::where('school',$school)->select('name')->get();
		    $streams = Stream::select('id','name')->where('school',$school)->get();
			
		   
			//$streams = array_combine([''=>'View All'],$streams) ;
			return View('la.students.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module,
			    'streams'=>$streams
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new student.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created student in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Students", "create")) {
		
			$rules = Module::validateRules("Students", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			// generate password
			$password = LAHelper::gen_password();
			
			$insert_id = Module::insert("Students", $request);
			
			$user = User::create([
				'name' => $request->first_name,
				'email' => $request->email,
				'password' => bcrypt($password),
				'context_id' => $insert_id,
				'type' => "Student",
			]);
	
			// update user role
			$user->detachRoles();
			//$role = Role::find($request->role);
			$role = Role::where('name', 'STUDENT')->firstOrFail();
			$user->attachRole($role);
			
			if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
				// Send mail to User his Password
				Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
					$m->from('skg.74754@gmail.com', 'LaraInspires');
					$m->to($user->email, $user->name)->subject('LaraInspires - Your Login Credentials');
				});
			} else {
				Log::info("User created: username: ".$user->email." Password: ".$password);
			}
			
			return redirect()->route(config('laraadmin.adminRoute') . '.students.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified student.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Students", "view")) {
			
			$student = Student::find($id);
			if(isset($student->id)) {
				$module = Module::get('Students');
				$module->row = $student;
				
				return view('la.students.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('student', $student);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("student"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified student.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Students", "edit")) {			
			$student = Student::find($id);
			if(isset($student->id)) {	
				$module = Module::get('Students');
				
				$module->row = $student;
				
				return view('la.students.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('student', $student);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("student"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified student in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Students", "edit")) {
			
			$rules = Module::validateRules("Students", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Students", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.students.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified student from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Students", "delete")) {
			Student::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.students.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax($stream = -1)
	{
	   \Log::info("inside dtajax");
	   		$values = DB::table('students')->select($this->listing_cols)->whereNull('deleted_at');
	   		if($stream != -1)
	   		    $values = $values->where('stream',$stream);
		$out = Datatables::of($values)->make();
		$data = $out->getData();
\Log::info($out);
		$fields_popup = ModuleFields::getModuleFields('Students');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/students/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Students", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/students/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Students", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.students.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
