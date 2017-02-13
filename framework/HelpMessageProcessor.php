<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of HelpMessageProcessor
 *
 * @author chris
 */
class HelpMessageProcessor implements IMessageProcessor {

    private $response;
    
    public function Process(Request $message) {
        $this->response = new HelpMessageDto();
        $this->response->responseUri = $message->get('response_url');
        $commandArguments = $message->get('text');
        
        if ($commandArguments == "man")
        {
            $this->response->topic = "Topics";
            $this->response->message = "```DESCRIPTION " .
                    "\n     RIFT COMMAND HELP MANUAL" .
                    "\nSYNTAX" .
                    "\n     /rift [man] [topic <string>]" .
                    "\nEXAMPLE" .
                    "\n     /rift man config" .
                    "\nTOPICS" .
                    "\n     - rift" .
                    "\n     - cancel" . 
                    "\n     - config```";
            return;
        }
        
        if (preg_match("/(man|help) \K.*/i", $commandArguments, $matches))
        {
            $this->response->topic = $matches[0];
            switch ($matches[0])
            {
                case 'rift':
                    $this->response->message = "```NAME" .
                        "\n     rift" .
                        "\nSYNTAX" .
                        "\n     /rift [type <string>] [time <string>]" .
                        "\nEXAMPLE" .
                        "\n     /rift Yondo now" .
                        "\nREMARKS" .
                        "\n     This command will let you organize a rift for completion rewards.  For more information on type see /rift man types.```";
                    break;
                case 'types':
                    $this->response->message = "```NAME" .
                        "\n     types" .
                        "\nEXAMPLE" .
                        "\n     /rift Loki now" .
                        "\nVALUES" .
                        "\n     Loki" .
                        "\n     Modok" .
                        "\n     Yondu" .
                        "\n     Groot" .
                        "\n     Nebula" .
                        "\n     Drax" .
                        "\n     Ronin" .
                        "\n     Ultron" .
                        "\n     Giant Man" .
                        "\n     Yellow Jacket" .
                        "\n     Wasp" .
                        "\n     Ant Man```";
                    break;
                case 'cancel':
                    $this->response->message = "```NAME" .
                        "\n     cancel" .
                        "\nSYNTAX" .
                        "\n     /rift cancel" .
                        "\nREMARKS" .
                        "\n     This command will let you cancel your last schelduled rift of the day." .
                        "\n     There may be a slight delay in detecting the last scheduled rift." .
                        "\n     Please try again after a few moments." .
                        "```";
                    break;
                case 'config':
                    $this->response->message = "```NAME" .
                        "\n     config" .
                        "\nSYNTAX" .
                        "\n     /rift config [setting <string>] [value <integer>]" .
                        "\nEXAMPLE" .
                        "\n     /rift config vip 10" .
                        "\nREMARKS" .
                        "\n     This command will let you set your VIP level and indicate your rift capacity and rewards.```";
                    break;
                default:
                    return;
            }
        }
    }

    public function SendResponse() {
        $response = array(
            'text' => $this->response->message,
//            'attachments' => array(
//                array(
//                    'title' => $this->response->topic,
//                    'text' => $this->response->message,
//                )
//            ),
        );
        
        $responseString = json_encode($response);
        $responseString = preg_replace('/\\\\\\\n/', '\n', $responseString);
        
        $response_url = $this->response->responseUri;
        $restClient = \Httpful\Request::post($response_url)
                ->body($responseString)
                ->send();
    }

//put your code here
}
