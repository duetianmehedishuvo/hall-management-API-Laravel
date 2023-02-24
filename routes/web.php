<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return '<h1>Welcome Sta Hall Api . Creator By Mehedi Hasan Shuvo</h1>';
});


$router->group(['prefix'=>'api'],function() use ($router){

   // TODO: for Authentication
    $router->post('/register', 'RegistrationController@onRegister');
    $router->post('/login', 'LoginController@onLogin');
    
    
   $router->group(['middleware'=>'auth'],function() use ($router){
    
    // TODO: for Test Token
        $router->post('/tokenTest', 'LoginController@tokenTest');

     // TODO: for Student
        $router->get('/allStudent', 'RegistrationController@getAllCustomer');
        $router->get('/getUserByStudentID', 'RegistrationController@getUserByStudentID');
        $router->get('/updateStudentBalance', 'RegistrationController@updateStudentBalance');
        $router->get('/getUserBalance', 'RegistrationController@getUserBalance');
        $router->get('/userProfile', 'RegistrationController@getUserProfile');
        $router->get('/countTotalCustomer', 'RegistrationController@countTotalCustomer');
        $router->post('/updateUser', 'RegistrationController@updateUser');
        $router->post('/updateFingerRFID', 'RegistrationController@updateFingerRFID');
        $router->post('/resetPassword', 'RegistrationController@resetPassword');
        $router->get('/searchStudent', 'RegistrationController@searchStudent');
        $router->post('/shareBalance', 'RegistrationController@shareBalance');
        $router->post('/logout', 'LoginController@logout');

        // TODO: for Room Controller
        $router->post('/addRoom','RoomController@addRoom');
        $router->get('/getAllRooms','RoomController@getAllRooms');
        $router->get('/getAllRoomsByYear','RoomController@getAllRoomsByYearRoomNo');
        $router->get('/updateRoomStatusByStudentID','RoomController@updateRoomStatusByStudentID');
        $router->delete('/deleteStudentsRoom','RoomController@deleteStudentsRoom');
        

        // TODO: for Other Controllers
        $router->post('/updateMealRate','OtherController@updateMealRate');
        $router->post('/updateGuestMealRate','OtherController@updateGuestMealRate');
        $router->post('/chanegGuestMealAddedStatus','OtherController@chanegGuestMealAddedStatus');
        $router->get('/getConfig','OtherController@getConfig');
        $router->post('/updateOfflineTakaCollectTime','OtherController@updateOfflineTakaCollectTime');

        // TODO: for Meal Controllers
        $router->post('/addMeal','MealController@addMeal');
        $router->post('/addGuestMeal','MealController@addGuestMeal');
        $router->get('/getAllMealByStudentID','MealController@getAllMealByStudentID');
        $router->delete('/deleteMealByID','MealController@deleteMealByID');
        $router->delete('/deleteGuestMealByID','MealController@deleteGuestMealByID');

        // TODO: for Transaction Controllers
        $router->get('/getAllTransctionList','TransactionController@getAllTransctionList');
        $router->get('/getUserAllTransAction','TransactionController@getUserAllTransAction');

        // TODO: for Transaction Controllers
        $router->post('/addComplain','ComplainController@addComplain');
        $router->post('/replyComplain','ComplainController@replyComplain');
        $router->delete('/deleteComplain','ComplainController@deleteComplain');
        $router->post('/editComplain','ComplainController@editComplain');
        $router->get('/getUserAllComplainByID','ComplainController@getUserAllComplainByID');

   });
});
