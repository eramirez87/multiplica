<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$params['head'] = $this->load->view("layer/head.php",false,true);
		if( isset($this->session->userdata('user')->isLogged ) AND  $this->session->userdata('user')->isLogged == true ){
			$params['nav'] = $this->load->view("layer/nav.php",false,true);
			$this->load->view('welcome_message',$params);
		}else{
			$params['nav'] = $this->load->view("layer/nav.php",false,true);
			$this->load->view('login_message',$params);
		}
	}
	public function login(){
		$this->load->model('User_model','user');
		$data = $this->input->post();
		$getUser = $this->user->getUser($data);
		$getUser->isLogged = true;
		if( isset( $getUser->create_time ) ){
			$this->session->set_userdata('user',$getUser);
			header('Location: '. base_url());
		}
	}
}
