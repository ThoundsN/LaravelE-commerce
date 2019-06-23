<?php

namespace App\Models\Traits;

use App\Scoping\Scoper;
use Illuminate\Database\Eloquent\Builder;

trait CanBeScoped
{
    public function scopeWithScopes(Builder $builder,$scopes){

        $scoper = new Scoper(request());


        return $scoper->apply($builder,$scopes);
    }

}