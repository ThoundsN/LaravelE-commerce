<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;


    /**
     * Determine if the address can be shown to the user .
     *
     * @return bool
     */
    public function show(User $user, Address $address)
    {
        return $user->id === $address->user_id;
    }


}
