<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace framework;
use Symfony\Component\HttpFoundation\Request;
/**
 *
 * @author chris
 */
interface IMessageProcessor {
    public function Process(Request $message);
    public function SendResponse();
}
