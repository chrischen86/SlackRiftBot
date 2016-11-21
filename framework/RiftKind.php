<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace framework;

/**
 * Description of RiftKind
 *
 * @author chris
 */
class RiftKind {
    //put your code here
    private static $names = null;
    
    public static function getNames()
    {
        if (self::$names == null)
        {
            self::$names = array(
                "Loki",
                "Modok",
                "Yondu",
                "Groot",
                "Nebula",
                "Drax",
                "Ronin",
                "Ultron",
                "Giant Man",
                "Yellow Jacket",
                "Wasp"
            );
        }
        return self::$names;
    }
}
