<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 08/07/2018
 * Time: 21:59
 */

namespace App\Websockets;


abstract class ChannelAbstract {

    private $isSuscription = false;

    public abstract function load();
    public function __construct($data)
    {
        if(array_key_exists("_action", $data) && $data["_action"] !== null && $data["_action"] !== "")
        {
            switch($data["_action"])
            {
                case "subscribe":
                    $this->isSuscription = true;
                break;
            }
        }
    }
    public function isSuscription() {
        return $this->isSuscription;
    }
}