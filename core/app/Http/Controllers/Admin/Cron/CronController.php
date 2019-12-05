<?php

namespace App\Http\Controllers\Admin\cron;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\PaymentCollection;
use App\Models\StaffUser;

class CronController extends Controller
{
    public function viewTodaysCollection(){
    	$members = StaffUser::whereNotNull('level')->get();
    	$i=0;
    	foreach ($members as $m_key => $member) {
    		$totalCollection = 0;
    		$regId = $member->fcm_id;
    		$regId = 'dsu3ifB8DBw:APA91bHn-XcV8K2pU2s4sHeE95IZmubGaSUK9f3nInY-ZhbnAFZtiV8kjRdyLFlCWwxY5JyRdSh1MxoS9oPIFp0rejk0ZyYvHTOnWfR6QjkX9ojLP6UUvIOr2dGbypprOrmGjFmqqlT1';
    		$memberId = $member->id;
    		$collection = PaymentCollection::where('status',0)->where('staff_user_id',$memberId)->where('new_date',date('Y-m-d'))->get();
    		$count = count($collection);
    		$title = 'Today New Collection';
    		$message = sprintf(__('form.total_collection_message'),$count);

    		sendNotification($regId,$title,$message);

    		if($count > 0)
    			$data[$i] = ['title'=>$title,'message'=>$message,'status'=>true];
    			$i++;
    	}
		echo json_encode($data);
		exit;
    }

    /* Send Firebase Notification */
    public function sendNotification($regId,$title,$message){

    	define('FIREBASE_API_KEY', 'AAAAUG7Snkg:APA91bFdUnrMQwY_hJ3mD0MLj_vjCpvlXFBQbuRykSIaSwFnyxv7dd-PNKsIUhWnSX8dxj_zmCgPaG06oqTWms0PtEKX01h5ulNeDB71iqX9HiabOWfA64jlYp5Eq8sMMXm9UfOjKFkN');

    	$message = strip_tags($message);        
    	$title = strip_tags($title);

    	$curl = curl_init();
    	curl_setopt_array($curl, array(
    		CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_ENCODING => "",
    		CURLOPT_MAXREDIRS => 10,
    		CURLOPT_TIMEOUT => 30,
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		CURLOPT_CUSTOMREQUEST => "POST",
    		CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"$regId\",\r\n \"collapse_key\" : \"type_a\",\r\n \"notification\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\"\r\n },\r\n \"data\" : {\r\n \"body\" : \"$message\",\r\n \"title\": \"$title\",\r\n \"key_1\" : \"\" }\r\n}",
    		CURLOPT_HTTPHEADER => array(
    			"Authorization: key=".FIREBASE_API_KEY,
    			"Cache-Control: no-cache",
    			"Content-Type: application/json",
    			"Postman-Token: 17dca3af-6994-4fe7-b8ec-68f99d13cfe8"
    		),
    	));
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    	curl_close($curl);
		// echo $response;
		// exit;
    	return true;
    }
	/* Send Firebase Notification */

}
