<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paypal\CreatePlan;
use App\Paypal\PaypalAgreement;

class SubscriptionController extends Controller
{
    public function createPlan()
    {
        $plan = new CreatePlan();
        $plan->create();
    }

    public function listPlans()
    {
        $plan = new CreatePlan();
        return $plan->planList();
    }
    public function planDetail($id)
    {
        $plan = new CreatePlan();
        return $plan->planDetail($id);
    }
    public function deletePlan($id)
    {
        $plan = new CreatePlan();
        return $plan->deletePlan($id);
    }
    public function activatePlan($id)
    {
        $plan = new CreatePlan();
        return $plan->activatePlan($id);
    }

    public function createAgreement($id)
    {       
        $agreement = new PaypalAgreement();
        return $agreement->create($id);
    }

    public function excuteAgreement($status)
    {
        if($status == 'true'){
            $agreement = new PaypalAgreement();
            // return $agreement->execute(request('token'));
            $agreementDetail = $agreement->execute(request('token'));
            $responseData = $agreementDetail->toArray();
            $params = [
                'agreement_id' => $responseData->id,
                'status' => $responseData->state,
                'start_date' => $responseData->start_date,
                'next_billing_date' => $responseData->agreement_details->next_billing_date,
                'paypal_response' =>json_encode($responseData)
            ];
            
            echo "<pre>";
            print_r($agreementDetail);
            exit;
            return 'done';
        }
    }

    public function getAgreement($id)
    {
        $agreement = new PaypalAgreement();
        $agreementDetail = $agreement->getAgreementDetail($id);
        $next_billing_date = $agreementDetail->agreement_details->next_billing_date;
        $next_billing_date = date('Y-m-d h:i:s',strtotime($next_billing_date));
        echo "<pre>";
        print_r($next_billing_date);
        exit;
    }
}
