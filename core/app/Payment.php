<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id', 'id');
    }

    function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }

    function customer()
    {
        return $this->belongsTo(User::class,'component_number','id')->withTrashed();
    }
    function dealer()
    {
        return $this->belongsTo(Shopkeeper::class,'component_number','id');
    }
    function lead()
    {
        return $this->belongsTo(Lead::class,'component_number','id');
    }

}
