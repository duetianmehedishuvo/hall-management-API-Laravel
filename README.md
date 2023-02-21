# Contact App With JWT Authentication

# Installation

1. Clone this repo

```
git clone https://github.com/duetianmehedishuvo/lumen_practice_boiler_plate.git
```

2. Install packages

```

$ composer install
$ https://github.com/flipboxstudio/lumen-generator
$ https://github.com/firebase/php-jwt
```

3. Create and setup .env file

```
make a copy of .env.example
$ copy .env.example .env
$ php artisan key:generate
put database credentials in .env file
```

Please visit my Linkedin.
[linkedin](https://www.linkedin.com/in/duetianmehedishuvo/)


Here Has This type off API:

<h1>Welcome Titas Gas Service Api Limited. Creator By Mehedi Hasan Shuvo</h1>

$router->group(['prefix'=>'api'],function() use ($router){
// TODO: for Authentication
   
   
    $router->post('/register', 'RegistrationController@onRegister');
    $router->post('/login', 'LoginController@onLogin');
    $router->post('/logout', 'LoginController@logout');
   
    
    
   $router->group(['middleware'=>'auth'],function() use ($router){
    
    // TODO: for Test Token
        $router->post('/tokenTest', 'LoginController@tokenTest');

     // TODO: for User
        $router->get('/allUser', 'RegistrationController@getAllUser');
        $router->get('/getUserByID', 'RegistrationController@getUserByID');
        $router->post('/updateUser', 'RegistrationController@updateUser');
        $router->post('/changePassword', 'LoginController@changePassword');
        $router->get('/user', 'RegistrationController@getUserProfile');

    // TODO: for Transaction
        $router->get('/getAllTranaction','TransactionController@getAllTransctionList');
        $router->post('/addTranaction','TransactionController@addTranaction');
        $router->post('/updateTransaction','TransactionController@updateTransaction');
        $router->get('/getTranactionById','TransactionController@getTranactionByID');
        $router->get('/deleteTransaction','TransactionController@deleteTransaction');
        

    // TODO: for Meter
        $router->get('/meter', 'MeterController@onSelect');
        $router->post('/meter', 'MeterController@onInsert');
        $router->get('/deletemeterByID', 'MeterController@deleteMeter');
        $router->get('/meterByID', 'MeterController@getMeterByID');
        $router->post('/meterUpdate', 'MeterController@updateMeter');

        

    // TODO: for Customer
        $router->get('/allCustomer', 'CustomerController@allCustomers');
        $router->post('/addCustomer', 'CustomerController@addCustomer');
        $router->get('/deleteCustomer', 'CustomerController@deleteCustomer');
        $router->get('/customerByID', 'CustomerController@getCustomerByID');
        $router->post('/customerUpdate', 'CustomerController@updateCustomer');
   });
});
