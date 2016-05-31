<?php

namespace App\Libraries;
use App\Settings;

class Stripe
{
    function CreateCustomer($email,$description)
    {
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');

        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;

        try 
        {
            
           
            $customerInfo = array(
                                'email' => $email,
                                'description'  => $description
                            );
            dump($customerInfo);
            $customerObj = \Stripe\Customer::create($customerInfo);
            dump($customerObj);
            $stripeCustomerId = $customerObj->id;
            return array('success'=>true,'customer_id'=>$stripeCustomerId,'customer_info'=>$customerObj);
        }
        catch(\Stripe\Error\Card $e) 
        {
          $body = $e->getJsonBody();
          $err  = $body['error'];
          return array('success'=>false,'reason'=>'card_declined','details'=>$err['message']);          
        } 
        catch (\Stripe\Error\InvalidRequest $e) 
        {
            return array('success'=>false,'reason'=>'invalid_parameter_supplied','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
            return array('success'=>false,'reason'=>'secret_key_not_valid','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
            return array('success'=>false,'reason'=>'connection_problem','details'=>'connection to stripe is not working');
        } 
        catch (Exception $e) 
        {
            return array('success'=>false,'reason'=>'other_error','details'=>'connection to stripe is not working');
        }
    }
    function AddCard($customerId,$cardNumber,$expMonth,$expYear,$cvc){
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');

        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;

        try 
        {
           $card = array(
                            "object"=>'card',
                            "number" => $cardNumber,
                            "exp_month" => $expMonth,
                            "exp_year" => $expYear,
                            "cvc" => $cvc
                        ) ;
           
            dump($card);
            $customerRetrieve = \Stripe\Customer::retrieve($customerId);
            dump($customerRetrieve);
            $setSource = $customerRetrieve->sources->create(array("source" => $card));
            dump($setSource);
            $cardId = $setSource->id;
            return array('success'=>true,'customer_id'=>$customerId,'cardId'=>$cardId);   
        } 
        catch(\Stripe\Error\Card $e) 
        {
          $body = $e->getJsonBody();
          $err  = $body['error'];
          return array('success'=>false,'reason'=>'card_declined','details'=>$err['message']);          
        } 
        catch (\Stripe\Error\InvalidRequest $e) 
        {
            return array('success'=>false,'reason'=>'invalid_parameter_supplied','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
            return array('success'=>false,'reason'=>'secret_key_not_valid','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
            return array('success'=>false,'reason'=>'connection_problem','details'=>'connection to stripe is not working');
        } 
        catch (Exception $e) 
        {
            return array('success'=>false,'reason'=>'other_error','details'=>'connection to stripe is not working');
        }
    }
    function CompleteOneTimePayment($customerId,$cardId,$price){
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');
        
        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;
        try 
        {
    
            $price = (int)$price*100;
            $paymentInfo = array(
                'source' => $cardId,
                'customer'=> $customerId,
                'amount'   => $price,
                'currency' => 'usd'
            );
            dump($paymentInfo);
            $charge = \Stripe\Charge::create($paymentInfo);
            dump($charge);
            return array('success'=>true,'charge_info'=>$charge); 
        } 
        catch(\Stripe\Error\Card $e) 
        {
          $body = $e->getJsonBody();
          $err  = $body['error'];
          return array('success'=>false,'reason'=>'card_declined','details'=>$err['message']);          
        } 
        catch (\Stripe\Error\InvalidRequest $e) 
        {
            return array('success'=>false,'reason'=>'invalid_parameter_supplied','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
            return array('success'=>false,'reason'=>'secret_key_not_valid','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
            return array('success'=>false,'reason'=>'connection_problem','details'=>'connection to stripe is not working');
        } 
        catch (Exception $e) 
        {
            return array('success'=>false,'reason'=>'other_error','details'=>'connection to stripe is not working');
        }
    }
    function DeleteCard($customerId,$cardId){
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');
        
        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;
        try 
        {
    
            $customer = \Stripe\Customer::retrieve($customerId);
            dump($customer);
            $returnData = $customer->sources->retrieve($cardId)->delete();
            dump($returnData);
            return array('success'=>true,'customer'=>$customer); 
        } 
        catch(\Stripe\Error\Card $e) 
        {
          $body = $e->getJsonBody();
          $err  = $body['error'];
          return array('success'=>false,'reason'=>'card_declined','details'=>$err['message']);          
        } 
        catch (\Stripe\Error\InvalidRequest $e) 
        {
            return array('success'=>false,'reason'=>'invalid_parameter_supplied','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
            return array('success'=>false,'reason'=>'secret_key_not_valid','details'=>'Invalid parameter supplied to stripe');
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
            return array('success'=>false,'reason'=>'connection_problem','details'=>'connection to stripe is not working');
        } 
        catch (Exception $e) 
        {
            return array('success'=>false,'reason'=>'other_error','details'=>'connection to stripe is not working');
        }
    }
}
