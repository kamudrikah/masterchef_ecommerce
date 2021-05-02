<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $input = $request->all();
        $user = $this->getUserOrCreate();
        $user->createOrGetStripeCustomer();

        $checkout = $user->checkoutCharge(1200, 'T-Shirt', 1);
        return view('checkout', [
            'checkout' => $checkout,
        ]);
    }

    public function getUserOrCreate()
    {
        $email = request('email');
        $user = User::where('email', $email)->get();

        if ($user->count() > 0) {
            return $user->first();
        }

        $user = new User(request()->all());
        $user->save();

        // TODO: Assign role

        return $user;
    }
}
