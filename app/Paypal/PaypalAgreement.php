<?php 

namespace App\Paypal;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

class PaypalAgreement
{
    public function __construct()
    {
        $client_id = env('PAYPAL_CLIENT_ID');
        $secret_id = env('PAYPAL_SECRET_ID');
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $client_id,     // ClientID
                $secret_id      // ClientSecret
            )
        );
 
    }
    public function create($id)
    {
        // $agreement = $this->agreement($id);
        return redirect($this->agreement($id));
       
    }
    
    public function agreement($id)
    {
        $startDate = date('c', time() + 3600);
        $agreement = new Agreement();

        $agreement->setName('Developer Testing Agreement')
            ->setDescription('Developer Testing Agreement Description')
            ->setStartDate($startDate); 
        
        $agreement->setPlan($this->plan($id));

        $agreement->setPayer($this->payer());
        $agreement->setShippingAddress($this->shippingAddress());
        $agreement = $agreement->create($this->apiContext);
        return $agreement->getApprovalLink();
        // return $agreement;
    }

    public function plan($id)
    {
        $plan = new Plan();
        return $plan->setId($id);
    }

    public function payer()
    {
        $payer = new Payer();
        return $payer->setPaymentMethod('paypal');
    }

    public function shippingAddress()
    {
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1('111 First Street')
            ->setCity('Mohali')
            ->setState('PU')
            ->setPostalCode('160055')
            ->setCountryCode('IN');
        return $shippingAddress;
    }

    public function execute($token)
    {
        $agreement = new Agreement();
        return $agreement->execute($token, $this->apiContext);
    }
    public function getAgreementDetail($id)
    {
        $agreement = new Agreement();
        return $agreement->get($id, $this->apiContext);
    }
    
}