<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class Utils
{

    static public function Realtime($topic , $data, HubInterface $hub){
        //create the new update that will be passed to the mercure HUB
        $update = new Update(
            $topic,
            json_encode($data)
        );


        //publish update to the mercure HUB
        $hub->publish($update);
    }


    static public function generateUniqueNumber()
    {
        $randomNumber = mt_rand(10000, 99999); // Generate a random number
        $uniqueNumber = $randomNumber; // Combine timestamp and random number

        return $uniqueNumber;
    }
}