<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 

class NeodataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
         /*
         USING PHP CURL FOR CALL API FOR GET NEO DATA
        */
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.nasa.gov/neo/rest/v1/feed?start_date='.$request->start_date.'&end_date='.$request->end_date.'&api_key=DEMO_KEY',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = json_decode(curl_exec($curl));
      
        $data= [];
        /*
         STORING USEABLE DATA TO AN ARRAY
        */
        foreach($response->near_earth_objects as $dates =>$objects){
            $data[] = ['date'=>$dates, "count"=>count($objects)];
        }
        
       
        /*
        API NOT PROVIDING SORTED DATA SO THIS METHORD WILL SORT DATA BY DATES
        */
        usort($data, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        }); 

        
        curl_close($curl);
        return response([ 'data' => $data, 'message' => 'Retrieved successfully'], 200);
    }
     
    
}