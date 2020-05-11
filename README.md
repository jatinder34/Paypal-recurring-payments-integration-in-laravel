
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

After that run this command for start the Laravel serve

```

php artisan serve

```


Next, for this tutorial we will be using a package called [PayPal-PHP-SDK](https://github.com/paypal/PayPal-PHP-SDK/wiki/Installation-Composer)


```

  

composer require "paypal/rest-api-sdk-php"

  

```

First, create ``` Paypay ``` folder in ``` app ``` folder and in ``` Paypay ``` folder you need to create ``` CreatePlan.php ``` file.

In ``` CreatePlan.php ``` file

```
<?php 

namespace App\Paypal;

class CreatePlan
{

    //Write code here

}
```
After that import these libraries in ``` CreatePlan.php ``` file under the ``` namespace ``` .

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


Create ``` __construct() ``` function in ``` CreatePlan.php ``` file 

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

First, Create controller using this command or manually.

```
php artisan make:controller SubscriptionController

```

Open ```app/http/controller/SubscriptionController.php``` controller

#### Create a billing plan.

