<?php
/**
 * Created by PhpStorm.
 * User: enclaveit
 * Date: 5/21/15
 * Time: 8:25 AM
 */

class Router {

    public $controller = 'Home';
    public $method = 'index';
    public $param = array();

    public function __construct(){
        $this->parseUrl();
    }

    public function parseUrl(){
        $url = array();
        if( isset( $_GET['url'] ) ){
            $url = explode( "/",filter_var( rtrim( $_GET['url'], "/" ), FILTER_SANITIZE_URL ));
        }
        if( file_exists( ABSPATH."controller/".$url[0].".php" ) || file_exists( ABSPATH."controller/".ucfirst($url[0]).".php" ) ){
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        /*
         * Include Controller File
         */
        include( ABSPATH."controller/".$this->controller.".php");

        /*
         * Load Controller
         */

         $controller = new $this->controller;

        /*
         * Load Method and Param
         */
        if( method_exists( $controller, $url[1])){
            $this->method = $url[1];
            unset($url[1]);
        }
        $this->param = $url ? array_values( $url ) : [];
        call_user_func_array(array($controller,$this->method), $this->param);
    }
} 