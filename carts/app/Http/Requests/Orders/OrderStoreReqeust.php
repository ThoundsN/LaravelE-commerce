<?php

namespace App\Http\Requests\Orders;

use App\Models\Address;
use App\Rules\ValidShippingMethodRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreReqeust extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'address_id' =>[
                'required',
                rule::exists('addresses','id')->where(function ($builder){
                    $builder->where('user_id', $this->user()->id);
                })
            ],
            'payment_method_id' =>[
                'required',
                rule::exists('payment_methods','id')->where(function ($builder){
                    $builder->where('user_id', $this->user()->id);
                })
            ],
            'shipping_method_id' => [
                'required',
                'exists:shipping_methods,id',
                new ValidShippingMethodRule($this->address_id)
            ]
        ];
    }
}
