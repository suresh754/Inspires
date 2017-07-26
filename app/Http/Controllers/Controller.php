<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Config;

use GuzzleHttp\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    
    public function __construct() {
        
    }
    //'http://local.inspires-erp.com/register'
    protected function apiRequest($type, $api, $data = []) {
             $client = new Client([
            'headers' => ['content-type' => 'application/json','Accept' => 'application/json'],
        ]);
        $api = config('env.api_url').$api ;
       
        $response = '' ;
        switch($type) {
            case 'GET':
              
            $response = $client->request($type,$api);
           
            break;
            case 'POST':
                $response = $client->request($type,$api,[
                'json' => $data,
                ]);
           // print_r("inside static create of website");
            //die;
            // $data = $response->getBody();
            //$data = json_decode($response->getBody());
        }
        if ($response->getStatusCode() == 200) {
           
            //dd($response->getBody());die;
            $result = json_decode($response->getBody(),true);
           
            if(!empty($result['code']) && $result['code']=='success') {
              
                return $result['data'] ;
            }
        }
        
            
    }
    
}
