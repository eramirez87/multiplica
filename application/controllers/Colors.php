<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Colors extends CI_Controller{

    public function index(){
        $this->load->model('Color_model','color');
        $sub['custom'] = ['js' => 'list', 'css' => 'list'];
        $params['head'] = $this->load->view("layer/head.php",$sub,true);
        $params['nav'] = $this->load->view("layer/nav.php",false,true);

        $params['colors'] = $this->color->getAll();

        $this->load->view('colores/list',$params);

    }

    public function create(){
        $this->load->model('Color_model','color');
        $params['head'] = $this->load->view("layer/head.php",false,true);
        $params['nav'] = $this->load->view("layer/nav.php",false,true);
        $params['action'] = base_url('colors/store');
        $params['action_btn'] = "Crear";
        $params['can_delete'] = false;
        $this->load->view('colores/form',$params);
    }

    public function store(){
        $this->load->model('Color_model','color');
        $post = $this->input->post();
        $this->color->save( $post );
        return header('Location: '. base_url('colors'));
    }

    public function edit($id){
        $this->load->model('Color_model','color');
        $sub['custom'] = ['js' => 'edit'];
        $params['head'] = $this->load->view("layer/head.php",$sub,true);
        $params['nav'] = $this->load->view("layer/nav.php",false,true);
        $params['action'] = base_url('colors/update');
        $params['action_btn'] = "Actualizar";
        $params['stored'] = $this->color->get($id);
        $params['can_delete'] = true;
        return $this->load->view('colores/form',$params);
    }

    public function update(){
        $this->load->model('Color_model','color');
        $post = $this->input->post();
        $this->color->update( $post['id'],$post );
        return header('Location: '. base_url('colors'));
    }

    public function drop(){
        $this->load->model('Color_model','color');
        $post = $this->input->post();
        $this->color->delete( $post['id'] );
        echo json_encode(['success' => true]);
        return;
    }
}