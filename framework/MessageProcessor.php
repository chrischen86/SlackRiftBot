<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;

use models\Field;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use dal\DataAccessAdapter;

/**
 * Description of MessageProcessor
 *
 * @author chris
 */
class MessageProcessor implements IMessageProcessor {

    //put your code here
    private $TimeRegex = "/(([01]?[0-9]|2[0-3])\:+[0-5][0-9]$)|(\d+.*$)/";
    private $response;
    
    public function Process(Request $message) {
        $toReturn = new MessageDto();

        $this->ProcessOwner($message, $toReturn);
        $this->ProcessType($message, $toReturn);
        $this->ProcessTime($message, $toReturn);
        $this->ProcessThumbUri($toReturn);
        
        $toReturn->responseUri = $message->get('response_url');
        $this->response = $toReturn;
    }

    public function SendResponse() {
        $dto = $this->response;
        $colour = $this->GetColour();
        $response = array(
            'text' => '*************** *Scheduled Rift* ***************',
            'response_type' => 'in_channel',
            'attachments' => array(
                array(
                    'color' => $colour,
                    'fields' => array(
                        new Field('Owner', $dto->owner),
                        new Field('Type', $dto->riftKind),
                        new Field('Time', $dto->time, false),
                    ),
                    'thumb_url' => $dto->thumbUri
                )
            ),
        );
        $responseString = json_encode($response);
        $responseString = preg_replace('/\\\\\\\n/', '\n', $responseString);

        $responseObject = new Response($responseString, 200);
        $responseObject->headers->set('Content-Type', 'application/json');
        //return $responseObject;

        $response_url = $dto->responseUri;
        $restClient = \Httpful\Request::post($response_url)
                ->body($responseString)
                ->send();
    }

    private function ProcessThumbUri(MessageDto &$dto) {
        $type = $dto->riftKind;
        switch ($type) {
            case 'Yondu':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/yondu01.png";
                return;
            case 'Ant Man':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/antman01.png";
                return;
            case 'Drax':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/drax01.png";
                return;
            case 'Giant Man':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/giantman01.png";
                return;
            case 'Groot':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/groot01.png";
                return;
            case 'Loki':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/loki01.png";
                return;
            case 'Modok':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/modok01.png";
                return;
            case 'Nebula':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/nebula01.png";
                return;
            case 'Ronin':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/ronin01.png";
                return;
            case 'Ultron':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/ultron_boss.png";
                return;
            case 'Wasp':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/wasp01.png";
                return;
            case 'Yellow Jacket':
                $dto->thumbUri = "http://projectr.ca/RiftBot/res/yellowjacket01.png";
                return;
            default:
                return;
        }
    }

    private function ProcessTime(Request $message, MessageDto &$dto) {
        $text = $message->get("text");
        $time = str_ireplace($dto->riftKind, '', $text);
        $dto->time = $time;
//        if (preg_match($this->TimeRegex, $text, $matches))
//        {
//            $dto->time = $matches[0];
//        }
    }

    private function ProcessOwner(Request $message, MessageDto &$dto) {
        $dto->owner = $message->get("user_name");
        $dto->userId = $message->get("user_id");
    }

    private function ProcessType(Request $message, MessageDto &$dto) {
        $text = $message->get("text");
        foreach (RiftKind::getNames() as $name) {
            if (stripos($text, $name) !== FALSE) {
                $dto->riftKind = $name;
                return;
            }
        }
    }

    private function GetColour()
    {
        $userId = $this->response->userId;
        $adapter = new DataAccessAdapter();
        $user = $adapter->GetUser($userId);
        if ($user == null)
        {
            return RiftLevel::$Normal;
        }
        
        switch ($user["vip"])
        {
            case 1:
            case 2:
            case 3:
            case 4:
                return RiftLevel::$Advanced;
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                return RiftLevel::$Rare;
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
                return RiftLevel::$Heroic;
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
                return RiftLevel::$Legendary;
            case 20:
                return RiftLevel::$Mythic;
            default:
                return RiftLevel::$Normal;
        }
    }
}
