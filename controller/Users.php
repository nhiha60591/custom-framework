<?php
/**
 * Created by PhpStorm.
 * User: HuuHien
 * Date: 5/14/2015
 * Time: 6:28 AM
 */
class Users extends Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_Model');
    }
    public function index()
    {
        $dis = array();
        $dis['view'] = 'init/home';
        $this->view_front( $dis );
    }
    public function login(){
        $dis = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $users = Users_Model::find_by_email_and_pass_and_active($_POST['email'], MD5($_POST['password']), NULL);
            if( sizeof( $users ) > 0 ){
                $userdata = array(
                    'user_id' => $users->user_id,
                    'email' => $users->email,
                    'user_level' => $users->user_level,
                    'first_name' => $users->first_name,
                    'last_name' => $users->last_name,
                );
                $this->session->set_userdata('login', $userdata);
                redirect( BASE_URL."Dashboard");
            }else{
                $dis['message'] = '<p class="error">The email or password do not match those on file. Or you have not activated your account.</p>';
            }

        }
        $dis['view'] = 'users/login';
        $this->view_front( $dis );
    }
    public function singup(){
        $dis = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = Users_Model::find_by_email($_POST['email']);
            if( sizeof( $user ) > 0){
                $dis['mes'] = '<p class="error">The email was already used previously. Please use another email address.</p>';
            }else{
                $active = MD5(time());
                $user = new Users_Model();
                $user->first_name = $_POST['first_name'];
                $user->last_name = $_POST['last_name'];
                $user->email = $_POST['email'];
                $user->pass = MD5($_POST['password1']);
                $user->active = $active;
                $user->registration_date = date("Y-m-d H:i:s");
                $user->save();
                $body = "Thank you for registering izCMS page. An activation email has been sent to the email address you provided. Session you click the link to activate your account \n\n ";
                $body .= BASE_URL . "Users/active/".str_replace("'", "", $active);
                if(mail( $_POST['email'], 'Activate account at izCMS', $body, 'FROM: localhost')) {
                    $message = "<p class='success'>Your account has been successfully registered. Email has been sent to your address. You must click the link to activate your account before using it.</p>";
                } else {
                    $message = "<p class='error'>Can not send an email to you. We apologize for this inconvenience.</p>";
                }
                $dis['mes'] = $message;

            }
        }

        $dis['view'] = 'users/singup';
        $this->view_front( $dis );
    }
    function logout(){
        $this->session->unset_userdata( 'login' );
        redirect( BASE_URL );
    }
    function forgot(){
        $dis = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = Users_Model::find_by_email($_POST['email']);
            if( sizeof($user) > 0 ) {
                $user->user_key = md5( $_POST['email'] );
                $subject = 'Please reset your password';
                $to = $_POST['email'];
                $body = "<p>We heard that you lost your password. Sorry about that!</p>

<p>But don't worry! You can use the following link within the next day to reset your password:</p>

<p>".base_url()."change-password/".md5($to) ."</p>

<p>If you don't use this link within 24 hours, it will expire. To get a new password reset link, visit ".base_url()."forgot</p>

<p>Thanks,<br />
Your friends</p>";
                mail($to, $subject, $body);
                $dis['message'] = '<p class="success">We\'ve sent you an email containing a link that will allow you to reset your password for the next 24 hours.<br ><br >
                                                      Please check your spam folder if the email doesn\'t appear within a few minutes.</p>';
            }else{
                $dis['message'] = '<p class="error">Can\'t find that email, sorry.</p>';
            }
        }
        $dis['view'] = 'users/forgot';
        $this->view_front( $dis );
    }
    function active( $active=''){
        $dis = array();
        $user = Users_Model::find_by_active($active);
        $user->active = NULL;
        $user->save();
        $message = '<p class="success">Your acccount has been activated successfully. You may <a href="'.base_url().'login">login </a> now.</p>';
        $dis['message'] = $message;
        $dis['view'] = 'users/active';
        $this->view_front( $dis );
    }
    function change_password( $user_id = '' ){
        $dis = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //if ($_SESSION['captcha'] == $this->input->post('captcha')) {
                $user = Users_Model::find_by_user_id($user_id);
                $user->pass = md5($_POST['pass']);
                $user->user_key = '';
                $user->save();
                $dis['message'] = '<p class="success">Change password success!</p>';
            /*} else {
                $dis['message'] = '<p class="error">The Captcha not math!</p>';
            }*/
        }
        $dis['view'] = 'users/change-password';
        $this->view_front($dis);
    }
    function myaccount(){
        $this->load->library('Upload');
        $dis = array();
        $admin_login = $this->session->userdata('login');
        if( !empty( $admin_login ) ) {
            $user = Users_Model::find_by_user_id($admin_login['user_id']);
            $dis['user'] = $user;
        }else{
            redirect(BASE_URL."Users/login");
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if( isset( $_FILES['avatar'] )) {
                @move_uploaded_file( $_FILES['avatar']['tmp_name'],'uploads/avatars/'.$_FILES['avatar']['name']);
                $user->avatar = 'uploads/avatars/'.$_FILES['avatar']['name'];
            }
            $user->first_name = $_POST['first_name'];
            $user->last_name = $_POST['last_name'];
            $user->email = $_POST['email'];
            $user->website = $_POST['website'];
            $user->bio = $_POST['bio'];
            $user->save();
            $dis['mes'] = '<p class="success">Update success!</p>';
        }
        $dis['view'] = 'users/profile';
        $this->view_front($dis);
    }
}