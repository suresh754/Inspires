<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
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
	
	public function __construct() {
		
	}
	
	public function getEmailById() {
	    return response()->json(array('code'=>'success','data'=>'hello'));
	    
	   
	}
	
	public function getStudentEmails() {
	    
	    return json_encode(array('code'=>'success','data'=>Student::select('email')->get()->toArray()));
	    //return array('code'=>'success','data'=>Student::select('email')->get()->toArray());
	}
	   	
}
