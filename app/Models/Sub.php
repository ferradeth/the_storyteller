<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sub_id',
        'period',
        'payed'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sub(){
        return $this->belongsTo(User::class, 'sub_id');
    }
}
