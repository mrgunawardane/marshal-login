<?php

namespace Core;
use App\Models\User;

class Validator{

    private $_passed = false,
    $_errors = array();

    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){
                
                $value = $source[$item];
                // $item = escape($item);
                
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is required");
                }else{
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be minimum of {$rule_value}");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} must be maximum of {$rule_value}");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match with {$item}");
                            }
                            break;
                        case 'contain_letter':
                            if(!preg_match('/[a-z]/i', $value)){
                                $this->addError("{$item} must contain at least one letter");
                            }
                            break;
                        case 'contain_number':
                            if(!preg_match('/[0-9]/', $value)){
                                $this->addError("{$item} must contain at least one number");
                            }
                            break;
                        case 'unique':
                            $check = User::findUser($value);
                            if($check){
                                $this->addError("{$item} already exists");
                            }
                            break;
                        case 'isEmail':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                $this->addError("Please enter valid email");
                            }

                    }
                }
            }
        }
        if(empty($this->_errors)){
            $this->_passed = true;
        }
        return $this;
    }

    private function addError($error){
        $this->_errors[] = $error;
    }

    public function errors(){
        return $this->_errors;
    }

    public function passed(){
        return $this->_passed;
    }
}