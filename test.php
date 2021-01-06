<?php

// https://stackoverflow.com/questions/36977596/adobe-echosign-api-v5-php-sample

session_start();


require("C:\wamp\www\Digitalisation-OT\adobe-sign-php-master\src\AdobeSign.php");

$provider = new KevinEm\OAuth2\Client\AdobeSign([
    'clientId'          => 'client_id',
    'clientSecret'      => 'client_secret',
    'redirectUri'       => 'uri',
    'scope'             => [
          'user_read:self',
          'user_write:self',
          'user_login:self',
          'agreement_read:self',
          'agreement_write:self',
          'agreement_send:self'
    ]
]);

$adobeSign = new KevinEm\AdobeSign\AdobeSign($provider);

if (!isset($_GET['code'])) {
    $authorizationUrl = $adobeSign->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authorizationUrl);
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    $accessToken = $adobeSign->getAccessToken($_GET['code']);
    $adobeSign->setAccessToken($accessToken->getToken());
	$file_path = 'test.txt';
	$file_stream = Psr7\FnStream::decorate(Psr7\stream_for(file_get_contents($file_path)), [
	    'getMetadata' => function() use ($file_path) {
	        return $file_path;
	    }
	]);
	
	$multipart_stream   = new Psr7\MultipartStream([
	    [
	        'name'     => 'File',
	        'contents' => $file_stream
	    ]
	]);
	
	$transient_document = $adobeSign->uploadTransientDocument($multipart_stream);
	
	print_r($transient_document);
    
}
?>