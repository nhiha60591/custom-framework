<?php
/**
 * Created by PhpStorm.
 * User: HuuHien
 * Date: 5/16/2015
 * Time: 8:38 PM
 */

class Dashboard extends Controller{
    public function index(){
        $dis = array();
        $dis['view'] = 'init/home';
        $this->view_admin( $dis );
    }
}