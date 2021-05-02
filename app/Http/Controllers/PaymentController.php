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
        $input['amount'] = config('toyyibpay.products.0.price');
        $bill = new Bill($input);
        $bill->save();

        $bill = $this->createBill($bill);
        $bill = $this->runBill($bill);
        dd([$user, $bill]);
    }

    public function createBill(Bill $bill): Bill
    {
        $url = config('toyyibpay.endpoint') . '/index.php/api/createBill';
        $payload = [
            'userSecretKey' => config('toyyibpay.secret_key'),
            'categoryCode' => config('toyyibpay.products.0.category_id'),
            'billName' => config('toyyibpay.products.0.name'),
            'billDescription' => config('toyyibpay.products.0.description'),
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $bill->amount,
            'billReturnUrl' => 'http://bizapp.my',
            'billCallbackUrl' => 'http://bizapp.my/paystatus',
            'billExternalReferenceNo' => $bill->id,
            'billTo' => $bill->user->name,
            'billEmail' => $bill->user->email,
            'billPhone' => $bill->user->phone,
            'billPaymentChannel' => '2',
            'billContentEmail' => 'Thank you for purchasing our MasterChef Coffee!',
            'billChargeToCustomer' => 2
        ];

        $response = Http::asForm()->post($url, $payload);
        $bill->bill_id = data_get($response->json(), '0.BillCode');
        $bill->save();
        return $bill;
    }

    public function runBill(Bill $bill)
    {
        $banks = $this->getBank();
        $banksFPX = $this->getBankFPX();
        dd($banksFPX);

        $url = config('toyyibpay.endpoint') . '/index.php/api/runBill';
        $payload = [
            'userSecretKey' => config('toyyibpay.secret_key'),
            'billCode' => $bill->bill_id,
            'billpaymentAmount' => $bill->amount,
            'billpaymentPayorName' => $bill->user->name,
            'billpaymentPayorPhone' => $bill->phone,
            'billpaymentPayorEmail' => $bill->user->email,
            'billBankID' => $banksFPX
        ];

        // dd($payload);

        $response = Http::post($url, $payload);
        dd($response->json());
        return $bill;
    }

    public function getBank(): array
    {
        $url = config('toyyibpay.endpoint') . '/index.php/api/getBank';
        $response = Http::post($url);
        return $response->json();
    }

    public function getBankFPX()
    {
        // TODO: Ask Toyyib pay WTH is this bug
        // return "MB2U0227";
        $url = config('toyyibpay.endpoint') . '/index.php/api/getBankFPX';
        $response = Http::post($url);
        return $response->json();
    }

    public function getUser()
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
