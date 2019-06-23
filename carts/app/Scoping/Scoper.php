<?php

namespace App\Scoping;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Scoper
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $buider, array $scopes){


        foreach ($scopes as $key => $scope){
            if (!$scope instanceof Scope){
                continue;
            }
            $scope->apply($buider,$this->request->get($key));
        }

        return $buider;
    }

}