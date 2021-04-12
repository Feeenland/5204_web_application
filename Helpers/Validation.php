<?php


namespace Helpers;


class Validation
{

    public function isValid($value)
    {
        $d = new Disinfect();
        $d->disinfect($value);

        if(! isset($value) || trim($value) == '' && strlen($value) > 30){
           return false; // 'its required'
        }else{
            return $value;
        }
    }
}