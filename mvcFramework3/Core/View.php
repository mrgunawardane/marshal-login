<?php

namespace Core;

class View{

    public static function render($view, $args = []){
        
        // access the view files relative to Core directory 
        $file = '../App/Views/' . $view;

        if(is_readable($file)){
            $data = json_encode($args);
            require $file;
        }else{
            echo $file . 'not found';
        }   
    
    }
}