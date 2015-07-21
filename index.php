<?php
require 'vendor/autoload.php';
require_once __DIR__.'/server.php';

$app = new \Slim\Slim();

$validateToken = function () use ($server, $app){
    return function () use ($server, $app ) {
    	$app->response->headers->set('Content-Type', 'application/json');

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

//Obtain access token with an auth token (code)
$app->post('/token/', function () use ($server) {
	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
});


//Obtain personal data (Sample rotected Enpoint)
$app->get('/treasure/', $validateToken(), function() use($app){
	$app->response->headers->set('Content-Type', 'application/json');

	$me = array(
		'first_name' => 'Gary',
		'last_name' => 'Smith',
		'phone' => '555-555-555',
		'email' => 'email@domain.com',
		'bank_account': '888444555222332444487886655'
	);

	echo json_encode($me);
});


//View to accept or decline access to personal data
$app->get('/auth/', function() use($server, $app){
	$code = $app->request()->get('response_type');
	$client = $app->request()->get('client_id');
	$state = $app->request()->get('state');

	$app->render(
	    'authForm.php',
	    array( 
	    	'clientId' => $client,
	    	'authorizePath' => "auth?response_type=$code&client_id=$client&state=$state",
    	)
	);
});

//Process auth request
$app->post('/auth/', function() use($server, $app){
	$app->response->headers->set('Content-Type', 'application/json');

	$request = OAuth2\Request::createFromGlobals();
	$response = new OAuth2\Response();
	
	
	if (!$server->validateAuthorizeRequest($request, $response)) {
    	$response->send();
    	die;
	}

	$allowAccess = ($_POST['authorized'] === 'yes');
	if ($allowAccess) {
		$server->handleAuthorizeRequest($request, $response, $allowAccess);
		
		//Parse code from Location
		$code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
		
		$grantResponse = array(
			'grant_type'=> 'authorization_code',
			'code'=> $code
		);

		//Post to  token endpoint to forward flow and return JSON on a single call
		$url = 'http://localhost:81/apps/Oauth2/index.php/token';
		$auth = base64_encode('TestApp:testpass');
		$options = array(
				'http' => array(
				'header'  => array(
					"Content-type: application/json",
					"Authorization: Basic $auth",
				),
				'method'  => 'POST',
				'content' => json_encode($grantResponse)
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
	}
	else{
		$app->response->setBody(json_encode(array(
			'access' => 'not_granted'
		)));
	}

	echo $result;
});

$app->run();
?>