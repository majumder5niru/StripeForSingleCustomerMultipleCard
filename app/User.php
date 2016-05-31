<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function CreateCustomer()
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
                                'email' => 'niru.nstu@gmail.com',
                                'description'  => 'ok'
                            );
            $customer = \Stripe\Customer::create($customerInfo);
            $stripeCustomerId = $customer->id;
            return array('success'=>true,'customer_id'=>$stripeCustomerId,'customer_info'=>$customer);
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

     public function AddCard($customerId){
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
                        "number" => '4242424242424242',
                        "exp_month" => '12',
                        "exp_year" => '17',
                        "cvc" => '1423'
                        ) ;
         
            $customerRetrieve = \Stripe\Customer::retrieve($customerId);

            $setSource = $customerRetrieve->sources->create(array("source" => $card));
        
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

    public function CompleteOneTimePayment($customerId,$cardId)
    {
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');
        
        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;
        try 
        {
            $price = 15;
            $price = (int)$price*100;
            $paymentInfo = array(
                'source' => $cardId,
                'customer'=>$customerId,
                'amount'   => $price,
                'currency' => 'usd'
            );
            $charge = \Stripe\Charge::create($paymentInfo);
            
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

    public function DeleteCard($customerId,$cardId){
        $settingsObj = new Settings();
        $stripeSecretKey = $settingsObj->GetValue('sk_test_ht2xSOdTIhQoNYsAzG8wnjYP');
        
        if($stripeSecretKey=="")
            return array('success'=>false,'reason'=>'secret_key_not_found','details'=>'Please set stripe api key on settings page');
        
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        $planInfo = null;
        try 
        {
    
            $customer = \Stripe\Customer::retrieve($customerId);
            $customer->sources->retrieve($cardId)->delete();
            
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
