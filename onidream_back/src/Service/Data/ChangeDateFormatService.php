<?php 

namespace App\Service\Data;

class ChangeDateFormatService{

    private $dateTimeKeys = [
       'publishedAt',
       'birthDate',
       'bookStartedOn',
       'bookEndedOn'
    ];


    public function convertDateTimeToStringInArray(array $array){    
            
        foreach($array as $key => $data){
            if(in_array($key, $this->dateTimeKeys)){
                $dateInString = $data->format('Y-m-d');

                $array[$key] = $dateInString;
            }
        }

        return $array;
    }
}