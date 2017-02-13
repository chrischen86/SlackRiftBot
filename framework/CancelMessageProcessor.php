<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CancelMessageProcessor
 *
 * @author chris
 */
class CancelMessageProcessor implements IMessageProcessor {
    private $response;

    public function Process(Request $message) {
        $this->response = new CancelMessageDto();
        $this->response->responseUri = $message->get('response_url');
        
        $user_name = $message->get("user_name");

        $request_uri = "https://slack.com/api/search.messages?token=" .
                \Config::$SlackToken .
                "&query=on%3Atoday%20from%3Arift-bot%20owner%20" .
                $user_name;

        $restClient = \Httpful\Request::get($request_uri)
                ->expectsJson()
                ->send();

        $jsonData = $restClient->body;
        
        if ($jsonData->messages->total <= 0)
        {
            $this->response->isSuccessful = false;
            return;
        }
        
        $message_id = $jsonData->messages->matches[0]->ts;
        $channel_id = $jsonData->messages->matches[0]->channel->id;

        $delete_uri = "https://slack.com/api/chat.delete?token=" .
                \Config::$SlackToken .
                "&ts=" .
                $message_id .
                "&channel=" .
                $channel_id;
        $restClient = \Httpful\Request::get($delete_uri)
                ->send();
        
        $this->response->isSuccessful = true;
    }

    public function SendResponse() {
        $response = array(
            'text' => $this->response->isSuccessful ? 
                'Got it!  Your last scheduled rift has been cancelled!' :
                'Sorry, I could not find a rift to cancel you have scheduled today :persevere: \nIf you have just scheduled, please wait a few moments before trying again.',
        );
        
        $responseString = json_encode($response);
        $responseString = preg_replace('/\\\\\\\n/', '\n', $responseString);
        
        $response_url = $this->response->responseUri;
        $restClient = \Httpful\Request::post($response_url)
                ->body($responseString)
                ->send();
    }
}
