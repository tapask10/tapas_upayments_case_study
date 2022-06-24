<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['session_id','user_id','product_id','qty'];
    protected $visible = ['id','session_id','user_id','product_id','qty'];
}
