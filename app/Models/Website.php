<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','status','user_id'];
    protected $hidden = ['pivot','created_at','updated_at','deleted_at'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'website_id');
    }
}
