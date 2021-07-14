<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class colores extends CI_Controller{

    public $user;
    public $pass;
    public $method;

    public function __construct(){
        $this->method = ( isset($_SERVER['REQUEST_METHOD']) ) ? $_SERVER['REQUEST_METHOD'] : false;
        $this->user = ( isset($_SERVER['PHP_AUTH_USER']) ) ? $_SERVER['PHP_AUTH_USER'] : false;
        $this->pass = ( isset($_SERVER['PHP_AUTH_USER']) ) ? $_SERVER['PHP_AUTH_PW'] : false;
    }

    private function checkBasics(){
        if(!$this->method) return false;
        if(!$this->user) return false;
        if(!$this->pass) return false;
        return true;
    }

    public function index(){
        $this->load->model('User_model','user');
        if( !$this->checkBasics() ){
            echo json_encode(['success'=>'false','error'=>'403 Forbidden']);
            return;
        }
        $u = $this->user->getUser([
            'username' => $this->user,
            'password' => $this->pass
        ]);
        print_r($u);
    }
}