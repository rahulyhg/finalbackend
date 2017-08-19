<?php

require_once './include/DbHandler.php';
require_once './include/PassHash.php';
require '././libs/Slim/Slim.php';
require './vendor/autoload.php';
require './include/simple_html_dom.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$path1 = dirname(__DIR__);
define('pic_image',$path1."/v1/uploads");
ini_set('display_errors', 'On');
error_reporting(E_ALL);
// User id from db - Global Variable
$user_id = NULL;
phpinfo();

$app->post('/register', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'password'));

    $response = array();

    // reading post params
    $name = 'DefaultName';
    $email = $app->request->post('email');
    $password = $app->request->post('password');

    // validating email address
    validateEmail($email);

    $db = new DbHandler();
    $res = $db->createUser($name, $email, $password);

    if ($res == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
    } else if ($res == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
    }
    // echo json response
    echoResponse(201, $response);
});
function verifyRequiredParams($required_fields) {



    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {


        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';

        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        $app->stop();
    }
}
$app->run();
?>