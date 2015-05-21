<?php
/**
 * Created by PhpStorm.
 * User: HuuHien
 * Date: 5/15/2015
 * Time: 9:20 PM
 */

class Controller {

    private static $instance;
    public $menu_active = 'home';
    public $submenu_active = 'home';
    public $admin_menus = null;
    public $page_title = 'Codeigniter CMS';
    public $site_title = 'Codeigniter CMS';

    public function __construct()
    {
        self::$instance =& $this;
        $this->load = new Loader();
        $this->load->library('Session');
        $this->session = new Session();
    }

    public static function &get_instance()
    {
        return self::$instance;
    }

    function view_front($dis, $include = true){
        $dis['this'] = $this;
        $dis['page_title'] = $this->page_title;
        $dis['site_title'] = $this->site_title;
        $admin_login = $this->session->userdata('login');
        $dis['userlogin'] = $admin_login;

        if (!isset($dis['menu_active']) || empty($dis['menu_active'])) {
            $dis['menu_active'] = $this->menu_active;
        }
        if( $include ) {
            $this->load->view('front/init/header', $dis);
            $this->load->view('front/'.$dis['view'],$dis);
            $this->load->view('front/init/footer',$dis);
        }else{
            $this->load->view($dis['view'], $dis );
        }
    }
    function view_admin($dis, $include = true){
        $dis['base_url'] = BASE_URL;
        $dis['this'] = $this;
        $dis['page_title'] = $this->page_title;
        $dis['site_title'] = $this->site_title;
        $admin_login = $this->session->userdata('login');
        $this->load->model('Users_Model');
        if( empty( $admin_login ) ) redirect( BASE_URL."Users/login");
        $dis['userlogin'] = Users_Model::find_by_user_id( $admin_login['user_id'] );

        if (!isset($dis['menu_active']) || empty($dis['menu_active'])) {
            $dis['menu_active'] = $this->menu_active;
        }
        if( $include ) {
            $this->load->view('dashboard/init/header', $dis);
            $this->load->view('dashboard/init/menu');
            $this->load->view('dashboard/'.$dis['view']);
            $this->load->view('dashboard/init/footer');
        }else{
            $this->load->view($dis['view'], $dis );
        }
    }
}