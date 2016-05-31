<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Libraries\Stripe;

class CreditCardController extends Controller
{
    
    public function AddCustomer()
    {
        $email = "niru.nstu@gmail.com";    
        $description = "Ok";
        $stripeObj = new Stripe();
        $customer = $stripeObj->CreateCustomer($email,$description);
        dd($customer);
    }

    public function AddNewCard()
    {
            
        $stripeObj = new Stripe();
        $email = "niru.nstu@gmail.com";    
        $description = "Ok";
        //$customer = $stripeObj->CreateCustomer($email,$description);
        $cardNumber = "4000056655665556";
        $expMonth = "12";
        $expYear = "17";
        $cvc = "123";
        $customerId = 'cus_8VbqZuQTjc7khg';
        $cards = $stripeObj->AddCard($customerId,$cardNumber,$expMonth,$expYear,$cvc);
        dd($cards);
        
    }

    public function PayInvoice()
    {
          
        $stripeObj = new Stripe();  
        $email = "niru.nstu@gmail.com";    
        $description = "Ok";
        $cardNumber = "4242 4242 4242 4242";
        $expMonth = "12";
        $expYear = "17";
        $cvc = "123";
        $price = 15;
        $cardId = "card_18EahpAsKYbBl9hwq05kSyaG";
        $customerId = "cus_8VbqZuQTjc7khg";
        //$customer = $stripeObj->CreateCustomer($email,$description);
        //$cardInfos = $stripeObj->AddCard($customer['customer_id'],$cardNumber,$expMonth,$expYear,$cvc);
        $payment = $stripeObj->CompleteOneTimePayment($customerId,$cardId,$price);
        dd($payment);
    }

    public function DeleteOneCard()
    {
            
        $stripeObj = new Stripe();  
        $email = "niru.nstu@gmail.com";    
        $description = "Ok";
        $cardNumber = "4242 4242 4242 4242";
        $expMonth = "12";
        $expYear = "17";
        $cvc = "123";
        $price = 15;
        //$customer = $stripeObj->CreateCustomer($email,$description);
        //$cardInfos = $stripeObj->AddCard($customer['customer_id'],$cardNumber,$expMonth,$expYear,$cvc);
        $cardId = "card_18EafmAsKYbBl9hw5QEAAC8f";
        $customerId = "cus_8VbqZuQTjc7khg";
        $deleteCard = $stripeObj->DeleteCard($customerId,$cardId);
        dd($deleteCard);
    }
}
