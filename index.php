<?php
require 'vendor/autoload.php';
require_once __DIR__.'/server.php';


// function validateToken() use($server){
// 	//$isValid = false;
// 	if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
// 		$app->flash('error', 'Login required');
//         //$app->redirect('/login');
// 	}
// 	//return $isValid;
// }


$app = new \Slim\Slim();

$validateToken = function () use ($server, $app){
    return function () use ($server, $app ) {
        if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
			$app->flash('error', 'Not authorized');
			$error = array(
				"error" => "NOT_AUTHORIZED",
				"message" => "Not authorized, invalid access_token",
				"code" => 403
			);
			$app->halt(403, json_encode($error));
		}
    };
};

$app->post('/auth/', function () use ($server) {
	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
});

$app->get('/me/', $validateToken(), function(){
	$me = array(
		'first_name' => 'Gary',
		'last_name' => 'Torres',
		'phone' => '555-555-555',
	);

	echo json_encode($me);
});

$app->run();

?>