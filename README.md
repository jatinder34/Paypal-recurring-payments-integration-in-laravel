
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