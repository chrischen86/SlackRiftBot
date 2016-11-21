<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;
use Symfony\Component\HttpFoundation\Request;
/**
 * Description of MessageProcessor
 *
 * @author chris
 */
class MessageProcessor {
    //put your code here
    private $TimeRegex = "/([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/";
    
    public function Process(Request $message)
    {
        $toReturn = new MessageDto();
        
        $this->ProcessOwner($message, $toReturn);
        $this->ProcessType($message, $toReturn);
        $this->ProcessTime($message, $toReturn);
        
        return $toReturn;
    }
    
    private function ProcessTime(Request $message, MessageDto &$dto)
    {
        $text = $message->get("text");
        if (preg_match($this->TimeRegex, $text, $matches))
        {
            $dto->time = $matches[0];
        }
    }
    
    private function ProcessOwner(Request $message, MessageDto &$dto)
    {
        $dto->owner = $message->get("user_name");
    }
    
    private function ProcessType(Request $message, MessageDto &$dto)
    {
        $text = $message->get("text");
        foreach (RiftKind::getNames() as $name)
        {
            if (stripos($text, $name) !== FALSE)
            {
                $dto->riftKind = $name;
                return;
            }
        }
    }
}
