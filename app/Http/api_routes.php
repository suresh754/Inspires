<?php



/*
 |--------------------------------------------------------------------------
 | Api Routes
 |--------------------------------------------------------------------------
 */



    
      
    /* ================== Students ================== */
    //Route::resource('api/students', 'api\StudentsController');
   // Route::get(config('laraadmin.adminRoute') . '/student_dt_ajax/{stream}', 'LA\StudentsController@dtajax');
   Route::get('api/emailbyid','Api\StudentsController@getEmailById');
   Route::get('api/email','Api\StudentsController@getStudentEmails');
    