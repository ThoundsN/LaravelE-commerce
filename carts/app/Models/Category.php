<?php

namespace App\Models;

use App\Models\Traits\HasChildren;
use App\Models\Traits\IsOrderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{


    use HasChildren;
    use IsOrderable;

    protected $fillable = [
        'name',
        'order',
        'slug'
    ];




    public function Children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
