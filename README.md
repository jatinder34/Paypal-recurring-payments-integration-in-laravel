
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
















  

  



*Note*: To get a client ID and secret, use the Developer Dashboard to [get credentials](https://developer.paypal.com/docs/api/overview/#get-credentials).



  

#### Install Package Manager

  

  

Steps to install NVM are documented [in the nvm repository](https://github.com/nvm-sh/nvm#installing-and-updating).

  

Install npm using nvm

  

  

```

  

nvm install 13.1.0

  

nvm use 13.1.0

  

npm install

  

```

  

#### Setup Environment

Refer [.env.template](.env.template) for environment variables to be exported to your environment.

#### Setup Database

1. Create databases and users mentioned exported in your environment.
1. Grant database user superuser privilege to the database to create POSTGIS extension and setup other tables. Reduce this privilege later to just create and modify tables or tuples in this database after you run the migration for the first time.
1. Install [PostGIS extension](https://postgis.net/install/).

#### Knex migrations and seed the database

  

  

Install Knex globally

  

  

```

  

npm install knex -g

  

```

  

  

Run migrations

  

  

```

  

knex migrate:latest --env test

  

knex migrate:latest --env development

  

```

  

  

Seed the database

  

  

```

  

knex seed:run --env test

  

knex seed:run --env development

  

```

  

  

#### Mocha unit tests

  

  

Install mocha globally.

  

  

```

  

npm install mocha -g

  

```

  

  

Run testing through mocha to see if unit tests pass

  

  

```

  

mocha

  

```

  

  

### Deploy using Docker

  

*Note*:  
1. The installation assumes you have already installed Postgres DB in your local environment listening for connections at port 5432.
2. Your Postgres instance should listen to '*' instead of 'localhost' by setting the `listen_addresses` parameter, [this setting can be found in your pgconfig file](https://www.postgresql.org/docs/current/runtime-config-connection.html).
3. Your `pg_hba.conf` should have a rule added for `host all all <docker-subnet> md5`. Replace `<docker-subnet>` with the actual CIDR for your docker installation's subnet. Note that `172.18.0.0/16` is usually the default.

 

Clone this repository





```

  

cd safeplaces-backend/expressjs

  

```
  

  



#### Build Dockerfile

  

  

```

  

docker build -t safeplaces-backend-expressjs .

  

```

  

  

#### Run Dockerfile

```

docker run --rm --name safeplaces-expressjs --env-file=.env -p 3000:3000 safeplaces-backend-expressjs

```

  

*Note*: sample env file can be found at .env.template`.

  

#### Deploy via docker-compose


 *Using docker-compose will bring a postgres server along with the application container* 
 
Ensure to create application Environment variables  file .env from .env.template

Ensure to create Postgres Environment variables file  .database.env from .database.env.template


#### Run the following:

```


docker-compose build
docker-compose up


```


### Testing Your Deployment

Run:

```


curl http://localhost:3000/health


```

Should respond with:

```


{
  "message": "All Ok!"
}



```