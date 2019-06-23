<?php

namespace App\Rules;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class ValidShippingMethodRule implements Rule
{
    protected $address;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($address_id)
    {

        $this->address  = Address::find($address_id);

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->address) {
            return false;
        }

        return $this->address->country->shippingMethods->contains('id', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid shipping method';
    }
}
