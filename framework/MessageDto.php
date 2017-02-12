<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;

/**
 * Description of MessageDto
 *
 * @author chris
 */
class BaseMessageDto {

    public $responseUri;

}

class CancelMessageDto extends BaseMessageDto {

    public $isSuccessful;

}

class MessageDto extends BaseMessageDto {

    //put your code here
    public $riftKind;
    public $time;
    public $owner;
    public $thumbUri;

}
