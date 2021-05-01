<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{

    public function pay(Request $request)
    {
        // TODO: Input validation

        $input = $request->all();
        $user = $this->getUser();

        $input['user_id'] = $user->id;
        $bill = new Bill($input);
        $bill->save();

        $bill = $this->createBill($user, $bill);
        dd([$user, $bill]);
    }

    public function createBill($user, $bill)
    {
        $url = config('toyyibpay.endpoint').'/index.php/api/createBill';
        $payload = [
            'userSecretKey' => config('toyyibpay.secret_key'),
            'categoryCode' => config('toyyibpay.categories.product1'),
            'billName' => 'Car Rental WXX123',
            'billDescription' => 'Car Rental WXX123 On Sunday',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => 100,
            'billReturnUrl' => 'http://bizapp.my',
            'billCallbackUrl' => 'http://bizapp.my/paystatus',
            'billExternalReferenceNo' => $bill->id,
            'billTo' => $user->name,
            'billEmail' => $user->email,
            'billPhone' => $user->phone,
            'billPaymentChannel' => '0',
            'billContentEmail' => 'Thank you for purchasing our MasterChef Coffee!',
            'billChargeToCustomer' => 2
        ];
        dump($url, $payload);

        $response = Http::post($url, $payload);
        dd($response->json());
        $bill = 0;
        return $bill;

    }
    public function getUser()
    {
        $email = request('email');
        $user = User::where('email', $email)->get();

        if($user->count() > 0) {
            return $user->first();
        }

        $user = new User(request()->all());
        $user->save();

        // TODO: Assign role

        return $user;
    }
}
