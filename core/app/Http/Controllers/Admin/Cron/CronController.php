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
}
