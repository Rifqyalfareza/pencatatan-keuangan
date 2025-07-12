<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amout',
        'categories_id',
        'type',
    ];

    public function categories(){
        return $this->belongsTo(Category::class);
    }
}
