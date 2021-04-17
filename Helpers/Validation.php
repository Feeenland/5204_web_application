<?php


namespace Helpers;


use Models\UserModel;

class Validation
{
    public $errorFeedback = [
        'required' => 'This field must be filled.',
        'max15chars' => 'This field can have a maximum of 15 characters',
        'max35chars' => 'This field can have a maximum of 35 characters',
        'min5chars' => 'This field must have at least 5 characters',
        'freeNickname' => 'This Nickname is not free!',
        'password' => 'The password must have at least 8 characters, 2 uppercase letters, 1 special character, 2 numbers and 3 lowercase letters' //not now in use
    ];

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

    public function validateFields($fieldRules){
        global $errorFeedback;
        $errors = [];
        foreach($fieldRules as $field => $rules){
            $fieldErrors = [];
            $value = $_REQUEST[$field];

            $d = new Disinfect();
            $d->disinfect($value);

            foreach($rules as $rule){


                if($rule == 'required'){
                    if(! isset($value) || trim($value) == '' ){
                        $fieldErrors[] = $this->errorFeedback[$rule]; // 'its required'
                    }
                }

                if($rule == 'freeNickname'){
                    $u = new UserModel();
                    $nickexist = $u->getUsersByNickname($value);
                    if (!$nickexist == false) {
                        $fieldErrors[] = $this->errorFeedback[$rule]; // 'nickname not free'
                    }
                }

                if($rule == 'max15chars'){
                    if($value != '' && strlen($value) > 15){
                        $fieldErrors[] = $this->errorFeedback[$rule]; // 'max chars 15'
                    }
                }

                if($rule == 'max35chars'){
                    if($value != '' && strlen($value) > 35){
                        $fieldErrors[] = $this->errorFeedback[$rule]; // 'max chars 35'
                    }
                }

                if($rule == 'min5chars'){
                    if($value != '' && strlen($value) < 5){
                        $fieldErrors[] = $this->errorFeedback[$rule]; // 'min chars 5'
                    }
                }

                if($rule == 'password'){ // not in use jet!

                    if($value != '' && ! preg_match('/^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$/', $value)){
                        $fieldErrors[] = $this->errorFeedback[$rule];
                        //'its a password with = 8 characters length oe more, 2 letters in Upper Case, 1 Special Character, 2 numerals, 3 letters in Lower Case'
                        // TODO use the Password validation if you want you passwords really save (and annoy the users)
                    }
                }
            }
            // Are there some errors?
            if(count($fieldErrors) != 0){
                $errors[$field] = $fieldErrors;
            }
        }
        return $errors;
    }

}
