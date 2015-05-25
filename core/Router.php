<?php
/**
 * Created by PhpStorm.
 * User: enclaveit
 * Date: 5/21/15
 * Time: 8:25 AM
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
class Router {

    public $controller = 'Home';
    public $method = 'index';
    public $param = array();
    public $routers = array();
    public $include = '';

    public function __construct(){
        $this->parseUrl();
    }

    public function parseUrl(){
        $uri = '';
        if( isset( $_GET['url'] ) ){
            $uri = filter_var( rtrim( $_GET['url'], "/" ), FILTER_SANITIZE_URL );
        }
        include(ABSPATH."config/router.php");
        $this->routers = isset($route) && !empty($route) ? $route : $this->routers;
        // Loop through the route array looking for wildcards
        foreach($this->routers as $k=>$v){
            $c = str_replace( array(':any',':num'), array('[a-zA-Z0-9]', '[0-9]'), rtrim($k, "/") );
            if(preg_match('/'.$c.'/', $uri)) {
                $s = explode( "/", rtrim( $v, "/" ));
                if( file_exists( ABSPATH."controller/".$s[0].".php" ) || file_exists( ABSPATH."controller/".ucfirst($s[0]).".php" ) ){
                    $this->controller = ucfirst($s[0]);
                    $this->method = $s[1];
                    $this->include = "controller/".$this->controller.".php";
                    unset($s[0]);
                    unset($s[1]);
                }elseif(file_exists( ABSPATH."controller/".$s[0]."/".$s[1].".php" ) || file_exists( ABSPATH."controller/".$s[0]."/".ucfirst($s[1]).".php" )){
                    $this->controller = ucfirst($s[1]);
                    $this->include = "controller/".$s[0]."/".$this->controller.".php";
                    $this->method = $s[2];
                    unset($s[0]);
                    unset($s[1]);
                    unset($s[2]);
                }else{
                    $this->include = "controller/".$this->controller.".php";
                }
                $param = $s ? array_values( $s ) : array();
                $this->param = array_merge($this->param, $param);
            }
        }
        if(empty($this->include)){
            $this->include = "controller/".$this->controller.".php";
        }
        include( ABSPATH.$this->include );

        /*
         * Load Controller
         */

         $controller = new $this->controller;


        call_user_func_array(array($controller,$this->method), $this->param);
    }
} 