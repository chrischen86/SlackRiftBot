<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace framework;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of MessageProcessorFactory
 *
 * @author chris
 */
class MessageProcessorFactory {
    //put your code here
    private $CancelRegex = "/cancel/";
    
    public function CreateProcessor(Request $request)
    {        
        $commandArguments = $request->get('text');
        if (preg_match($this->CancelRegex, $commandArguments))
        {
            return new CancelMessageProcessor();
        }
        return new MessageProcessor();
    }
}
