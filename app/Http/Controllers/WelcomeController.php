<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function stripeFunction(Request $request){
        Stripe::setApiKey('sk_test_IblRHJQHrblVBGkmWHYxfi0w00ulab2t8m');
//        dd($request->input('stripeToken'));
//        $charge = Charge::create(array([
//            'amount' => 300 * 100,
//            'currency' => 'usd',
//            'source' => $request->input('stripeToken')
//        ]));
//
//        dd($charge);

        $paymentIntent = PaymentIntent::create([
            'amount' => 300 * 100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
//            'source' => $request->input('stripeToken')
        ]);

        $intent =  PaymentIntent::retrieve($paymentIntent->id);

        $intents = $intent->confirm([
            'payment_method_options' => ['card' => [
                'request_three_d_secure' => 'any'
            ]],
            'payment_method_types' => [
                'card'
            ],
            'payment_method' => 'pm_card_threeDSecure2Required',
            'return_url' => 'http://testworklocal.com/success',
        ]);


       return redirect($intents->next_action->redirect_to_url->url);


//        try{
//            Charge::create( array(
//                "amount" => 300 * 100,
//                "currency" => "usd",
//                "source" => $request->input('stripeToken'),
//                "description" => "Sample Description"
//            ));
//
//            return redirect()->back()->with('notification',"Payment Done Successfully!");
//
//        } catch(\Exception $e){
//
//
//            return redirect()->back()->with('notification', "Error!. please try again!");
//
//        }
    }

    public function stripeConfirm(){
        return view('stripeConfirm');
    }
}
