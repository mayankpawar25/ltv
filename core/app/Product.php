<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = [];

    public function vendor() {
      return $this->belongsTo('App\Vendor');
    }

    public function previewimages() {
      return $this->hasMany('App\PreviewImage');
    }

    public function carts() {
      return $this->hasMany('App\Cart');
    }

    public function category() {
      return $this->belongsTo('App\Category');
    }

    public function subcategory() {
      return $this->belongsTo('App\Subcategory');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    public function productreviews() {
      return $this->hasMany('App\ProductReview');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'favorits');
    }

    public function flashinterval() {
      return $this->belongsTo('App\FlashInterval');
    }
    static function sorting(){
      return  array(['name'=>'Latest on top','value'=>'date_desc'],
                    ['name'=>'Oldest on top','value'=>'date_asc'],
                    ['name'=>'Price:High to Low','value'=>'price_desc'],
                    ['name'=>'Price:Low to High','value'=>'price_asc'],
                    ['name'=>'Top Sales','value'=>'sales_desc'],
                    ['name'=>'Top Rated','value'=>'rate_desc']
                  );
    }

    static function column_sequence_for_import(){
        return [

            // Contact
           'A' => 'title', 
           'B' => 'description', 
           'C' => 'price', 
           'D' => 'quantity', 
           'E' => 'category', 
           'F' => 'subcategory',
           'G' => 'product_code',
        ];
    }
}
