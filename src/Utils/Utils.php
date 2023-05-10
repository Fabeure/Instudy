<?php

namespace App\Utils;

class Utils
{
    static public function generateUniqueNumber()
    {
        $randomNumber = mt_rand(10000, 99999); // Generate a random number
        $uniqueNumber = $randomNumber; // Combine timestamp and random number

        return $uniqueNumber;
    }
}