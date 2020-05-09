<?php 

namespace App\Paypal;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

class CreatePlan
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
    public function create()
    {
        $plan = $this->plan();
        $paymentDefinition = $this->PaymentDefinition();
        $chargeModel = $this->chargeModel();

        $paymentDefinition->setChargeModels(array($chargeModel));

        $merchantPreferences = $this->merchantPreferences();

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);
        $output = $plan->create($this->apiContext);
        dd($output);
    }

    public function plan()
    {
        $plan = new Plan();
        $plan->setName('Developer Testing Monthly Plan')
            ->setDescription('Template creation.')
            ->setType('fixed');
        return $plan;
    }


    public function PaymentDefinition()
    {
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('Month')
            ->setFrequencyInterval("2")
            ->setCycles("12")
            ->setAmount(new Currency(array('value' => 100, 'currency' => 'USD')));
        return $paymentDefinition;
    }

    public function chargeModel()
    {
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
            ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
        return $chargeModel;
    }

    public function merchantPreferences()
    {
        $merchantPreferences = new MerchantPreferences();
        // $baseUrl = getBaseUrl();
        $merchantPreferences->setReturnUrl(config('services.paypal.url.executeAgreement.success'))
            ->setCancelUrl(config('services.paypal.url.executeAgreement.failure'))
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));
        return $merchantPreferences;
    }
    public function planList()
    {
        $params = array('page_size' => '2');
        $planList = Plan::all($params, $this->apiContext);
        return $planList;
    }

    public function planDetail($id)
    {
        return $plan = Plan::get($id, $this->apiContext);
    }
    public function deletePlan($id)
    {
        $createdPlan = $this->planDetail($id);
        return $result = $createdPlan->delete($this->apiContext);
    }
    public function activatePlan($id)
    {
        $createdPlan = $this->planDetail($id);
        $patch = new Patch();

        $value = new PayPalModel('{
            "state":"ACTIVE"
            }');

        $patch->setOp('replace')
            ->setPath('/')
            ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);

        $createdPlan->update($patchRequest, $this->apiContext);

        return $plan = Plan::get($createdPlan->getId(), $this->apiContext);
    }
}