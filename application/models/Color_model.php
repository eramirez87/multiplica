<?php

class Color_model extends CI_Model{

    private $table = 'color';

    public $id;
    public $name;
    public $color;
    public $pantone;
    public $year;

    public function getAll(){
        return $this->db->get($this->table)->result();
    }

    public function get($id){
        $this->db->where('id',$id);
        return $this->db->get($this->table)->row();
    }

    public function save($data){
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();
    }

    public function update($id,$data){
        $this->db->where('id',$id);
        return $this->db->update($this->table,$data);
    }
    public function delete($id){
        $this->db->where('id',$id);
        return $this->db->delete($this->table);
    }

}