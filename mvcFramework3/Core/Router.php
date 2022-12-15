<?php

namespace Core;

class Router{

    // array of routes , routing table
    protected $routes = [];

    // parameters from matched routes 
    protected $params = [];

    // add routes to routing table
    public function add($route, $params = []){
        // convert route to reg ex to escape forward slashes 
        $route = preg_replace('/\//', '\\/', $route);

        // convert variables 
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // custom regular expression 
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        // add start and end delimeters and convert to insensitive
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    // return the routing table 
    public function getRoutes(){
        return $this->routes;
    }

    public function match($url){
        // foreach($this->routes as $route=>$params){
        //     if($url == $route){
        //         $this->params = $params;
        //         return true;
        //     }
        // }

        // match to the fixed URL format /controller/action 
        // $regEx = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        // if(preg_match($regEx, $url, $matches)){
        //     // get capture group values by name 
        //     $params = [];

        //     foreach($matches as $key => $match){
        //         if(is_string($key)){
        //             $params[$key] = $match;
        //         }
        //     }
        //     $this->params = $params;
        //     return true;
        // }

        foreach($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                // get the name captured group values

                foreach($matches as $key => $match){
                    if(is_string($key)){
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    // get the matched parameters 
    public function getParams(){
        return $this->params;
    }

    public function dispatch($url){

        // remove query string variables from URL 
        $url = $this->removeQueryStringVariables($url);

        if($this->match($url)){
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            // $controller = 'App\Controllers\\' .$controller;
            $controller = $this->getNamespace() . $controller;

            if(class_exists($controller)){
                $controllerObject = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if(is_callable([$controllerObject, $action])){
                    $controllerObject->$action();
                }else{
                    echo $action . 'method not found in ' . $controller;
                }
            }else{
                echo $controller . ' - not found';
            }
        }else{
            echo 'No route matched';
        }
    }

    protected function convertToStudlyCaps($string){
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string){
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url){
        if($url != ''){
            $parts = explode('&', $url, 2);

            if(strpos($parts[0], '=') === false){
                $url = $parts[0];
            }else{
                $url = '';
            }
        }
        return $url;
    }
    protected function getNamespace(){
        $namespace = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->params)){
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}