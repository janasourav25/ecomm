<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Address extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'first_name','last_name',
    'email','phone','address','city','state','postal_code','country'];
   
    public function order(){
        return $this->belongsTo(Order::class);
    } 

    public function getFullNameAttribute(){
        return" {$this->first_name} {$this->last_name} " ;
    } 

   
}
