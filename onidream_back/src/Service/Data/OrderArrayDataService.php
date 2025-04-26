<?php

namespace App\Service\Data;

class OrderArrayDataService
{
    public function asc(array $array)
    {   
        $orderArray = $array;
        usort($orderArray, function($a, $b){
            return $a->getName() <=> $b->getName();
        });

        return $orderArray;
    }
}