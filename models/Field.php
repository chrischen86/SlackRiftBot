<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models;

/**
 * Description of FieldModel
 *
 * @author chris
 */
class Field {
    //put your code here
    public $title;
    public $value;
    public $short;
    
    public function __construct($title, $value, $isShort=true) {
        $this->title = $title;
        $this->value = $value;
        $this->short = $isShort;
    }
}
