
# Paypal Recurring Payments Integration in Laravel
  
## First, let’s create a new typical Laravel application

```

laravel new paypal-demo


```

## Next – usual installation steps:

```

composer install
cp .env.example .env (and then editing .env with credentials)
php artisan key:generate
php artisan migrate


```


Next, for this tutorial we will be using a package called [PayPal-PHP-SDK](https://github.com/paypal/PayPal-PHP-SDK/wiki/Installation-Composer)


```

  

composer require "paypal/rest-api-sdk-php"

  

```

First, create ``` Paypay ``` folder in ``` app ``` folder and in ``` Paypay ``` folder you need to create ``` CreatePlan.php ``` class.

In ``` CreatePlan.php ``` class

```
<?php 

namespace App\Paypal;

class CreatePlan
{

    //Write code here

}
```
After that import these libraries in ``` CreatePlan.php ``` class under the ``` namespace ``` .

```
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

```

Once you have completed these steps, you could make the first call for Paypal configration.


Create ``` __construct() ``` function in ``` CreatePlan.php ``` class 

```
  public function __construct()
  {
    $this->apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylRSjk',     // ClientID
            'EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKYQvQSBngp4PTTVWksdags'      // ClientSecret
        )
    );
  }

```

*Note*: To get a client ID and secret, use the Developer Dashboard to [get credentials](https://developer.paypal.com/docs/api/overview/#get-credentials).

*Here's how*:
#### 1. Create a billing plan.
#### 2. Activate the billing plan.
##### 3. Create a billing agreement.
#### 4. Execute the billing agreement.

### Now let's start the integration

### Create a billing plan.

Let's create function called ``` createPlan ``` in ``` CreatePlan.php ``` class 

 ```
    public function createPlan()
    {
        $plan = new Plan();
        $plan->setName('Developer Testing Monthly Plan')
            ->setDescription('Template creation.')
            ->setType('fixed');
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('Month')
            ->setFrequencyInterval("2")
            ->setCycles("12")
            ->setAmount(new Currency(array('value' => 100, 'currency' => 'USD')));
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
            ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
        $paymentDefinition->setChargeModels(array($chargeModel));
        $merchantPreferences = new MerchantPreferences();
        $baseUrl = getBaseUrl();
        $merchantPreferences->setReturnUrl($baseUrl."/excute-agreement/true")
            ->setCancelUrl($baseUrl."/excute-agreement/false")
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));
        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);
        $output = $plan->create($this->apiContext);
        dd($output);
    }

 ```


New we need to create controller using this command or manually.

```
php artisan make:controller SubscriptionController

```

Open ```app/http/Controllers/SubscriptionController.php``` controller

After create controller import CreatePlan class ``` use App\Paypal\CreatePlan  ``` in this controller

In side this create new function called ``` createPlan ``` like this

```

    public function createPlan()
    {
        $plan = new CreatePlan();
        $plan->create();
    }

```

New create route for this go to ``` routes/web.php ``` file and create route for create plan 

```
  Route::get('plan/create','SubscriptionController@createPlan');

```

After that run this command for start the Laravel serve

```

php artisan serve

```
Run this url in browser
```
http://localhost:8000/plan/create

```

![Create Plan](https://i.ibb.co/rMX5Lw6/Screenshot-2020-05-11-at-6-30-41-PM.png)

You can see this response and in this response you can see Plan id ``` P-3SH54433KE695700CAPXXXXX ```

For getting the plan detail we need to create another function in ``` createPlan ``` class called ```getPlanDetail```

```
    public function getPlanDetail($id)
    {
        return $plan = Plan::get($id, $this->apiContext);
    }

```
After create function go to ```SubscriptionController``` controller and crate new function called ``` planDetail ```

```
  public function planDetail($plan_id)
  {
      $plan = new CreatePlan();
      return $plan->planDetail($plan_id);
  }

```
Now create route for plan detail 

```
Route::get('plan/{planid}','SubscriptionController@planDetail');

```

```
  {
    "id": "P-3SH54433KE695700CAPJHEBI",
    ```"state": "CREATED"```,
    "name": "Developer Testing Monthly Plan",
    "description": "Template creation.",
    "type": "FIXED",
    "payment_definitions": [
      {
        "id": "PD-9WY851217E545260DAPJHEBI",
        "name": "Regular Payments",
        "type": "REGULAR",
        "frequency": "Month",
        "amount": {
          "currency": "USD",
          "value": "100"
        },
        "cycles": "12",
        "charge_models": [
          {
            "id": "CHM-2GR18283DN1858322APJHEBI",
            "type": "SHIPPING",
            "amount": {
              "currency": "USD",
              "value": "10"
            }
          }
        ],
        "frequency_interval": "2"
      }
    ],
    "merchant_preferences": {
      "setup_fee": {
        "currency": "USD",
        "value": "1"
      },
      "max_fail_attempts": "0",
      "return_url": "http://localhost:3001/excute-agreement?status=true",
      "cancel_url": "http://localhost:3001/excute-agreement?status=false",
      "auto_bill_amount": "YES",
      "initial_fail_amount_action": "CONTINUE"
    },
    "create_time": "2020-05-11T13:00:22.917Z",
    "update_time": "2020-05-11T13:00:22.917Z",
    "links": [
      {
        "href": "https://api.sandbox.paypal.com/v1/payments/billing-plans/P-3SH54433KE695700CAPJHEBI",
        "rel": "self",
        "method": "GET"
      }
    ]
  }
```
### Activate the billing plan.
 
For activating this plan we need to create another function in ``` createPlan ``` class

Create function for activating plan called ``` activatePlan ```

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