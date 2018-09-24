<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class APIController extends Controller
{
    protected $requests;
    protected $count;

    public function getPlaces(Request $request)
    {
    	$Inputs = $request->all();
    	$city = $Inputs['city'];
    	$data = $this->csvToArray();
    	$result = Array();
    	foreach ($data as $value) {
    		if($value['city'] == $city){
    			$result[] = $value['places'];
    		}
    	}

    	return $result;
    }


    function csvToArray()
    {
    	$handle = fopen('places.csv', "r");
    	$header = true;
    	$data = Array();
    	while ($csvLine = fgetcsv($handle, 1000, ",")) {

		    if ($header) {
		        $header = false;
		    } else {
		    	$data[] = Array('city' => $csvLine[0], 'places' => $csvLine[1]);
		    }
		}
		return $data;
    }

    function createRequestRecord()
    {
    	$this->requests[] = Carbon::now();
    }

    function checkRateLimit()
    {
    	foreach($this->requests as $request){
    		if(date_diff(Carbon::now(),$request)){
    			$this->count++;
    			echo "count = " .  $this->count;
    		}
    	}	
    	return $this->count > 10 ? false : true;
    }
}
