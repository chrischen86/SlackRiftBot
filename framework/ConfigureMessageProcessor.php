<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;
use Symfony\Component\HttpFoundation\Request;
use dal\DataAccessAdapter;
/**
 * Description of ConfigureMessageProcessor
 *
 * @author chris
 */
class ConfigureMessageProcessor implements IMessageProcessor{
    private $VipRegex = "/vip \K(\d)+$/i";
    private $adapter;
    private $response;
    
    public function __construct() {
        $this->adapter = new DataAccessAdapter();
    }
    
    public function Process(Request $message) {
        $this->response = new ConfigureMessageDto();
        $this->response->responseUri = $message->get('response_url');
        
        $commandArguments = $message->get('text');
        $userId = $message->get('user_id');
        $userName = $message->get('user_name');
        
        if (preg_match($this->VipRegex, $commandArguments, $matches))
        {
            $this->response->setting = "VIP level";
            $this->adapter->UpsertUser($userId, $userName, $matches[0]);
            $this->response->value = $matches[0];
            return;
        }        
    }

    public function SendResponse() {
        $response = array(
            'text' => "Awesome!  I've adjusted your *" . $this->response->setting . "* to *" .
            $this->response->value . "*!",
        );
        
        $responseString = json_encode($response);
        $responseString = preg_replace('/\\\\\\\n/', '\n', $responseString);
        
        $response_url = $this->response->responseUri;
        $restClient = \Httpful\Request::post($response_url)
                ->body($responseString)
                ->send();
    }

}
