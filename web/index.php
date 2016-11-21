<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use models\Field;

$app = new Silex\Application();
$app['debug'] = true;
// ... definitions

//$TimeRegex = "/([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/";
//if (preg_match($TimeRegex, 'Yondu 4:45', $matches))
//        {
//            //$dto->time = $matches[0];
//            echo 'test' . $matches[0];
//        }
//        exit();

$app->post('/rift', function(Request $request){
    $processor = new framework\MessageProcessor();
    $dto = $processor->Process($request);
    
    $response = array(
        'text' => '*************** *Scheduled Rift* ***************',
        'response_type' => 'in_channel',
        'attachments' => array(
            array(
                'color' => '#439FE0',
                'fields' => array(
                    new Field('Owner', $dto->owner),
                    new Field('Type', $dto->riftKind),
                    new Field('Time', $dto->time, false),
                ),
                'thumb_url' => 'http://onlinefanatic.com/wp-content/uploads/2016/06/Angela.jpg'
            )
        ),
    );
    $responseString = json_encode($response);
    $responseString = preg_replace('/\\\\\\\n/','\n', $responseString);
    
    $responseObject = new Response($responseString, 200);
    $responseObject->headers->set('Content-Type', 'application/json');
    //return $responseObject;
        
    $response_url = $request->get('response_url');
    $restClient = \Httpful\Request::post($response_url)
            ->body($responseString)
            ->send();
    
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