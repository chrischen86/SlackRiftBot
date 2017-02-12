<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use dal\DataAccessAdapter;

$app = new Silex\Application();
$app['debug'] = true;
// ... definitions

//$TimeRegex = "/\d+.*$/";
//if (preg_match($TimeRegex, 'Yondu 10 minutes before rollover', $matches))
//        {
//            //$dto->time = $matches[0];
//            echo 'test' . $matches[0];
//        }
//        exit();

$messageProcessorFactory = new \framework\MessageProcessorFactory();

$app->get('', function (Request $request){
    $adapter = new DataAccessAdapter();
    $adapter->CreateRift();
    $adapter->GetRifts();
});

$app->post('/rift', function(Request $request){
    $messageProcessorFactory = new \framework\MessageProcessorFactory();
    $processor = $messageProcessorFactory->CreateProcessor($request);
    
    $processor->Process($request);
    $processor->SendResponse();  
    
    return new Response('', 200);
});

$app->post('/slack/verify', function(Request $request){
    $data = json_decode($request->getContent(), true);
    if ($data['type'] != 'url_verification')
    {
        return new Response('Wrong type of request', 400);
    }
    if ($data['token'] != Config::$SlackToken)
    {
        return new Response('Invalid token', 400);
    }    
    return new Response($data['challenge'], 200);
});

$app->run();
?>