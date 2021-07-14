<?php

class User_model extends CI_Model{

    private $table = 'user';

    public $username;
    public $email;
    public $password;

    public function createTable(){
        $sql  = "CREATE TABLE `ci_sessions` (
            `session_id` varchar(40) NOT NULL DEFAULT '0',
            `ip_address` varchar(45) NOT NULL DEFAULT '0',
            `user_agent` varchar(120) NOT NULL,
            `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
            `user_data` text NOT NULL,
            PRIMARY KEY (`session_id`),
            KEY `last_activity_idx` (`last_activity`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

        $sql .= "CREATE TABLE `user` (
            `username` varchar(16) NOT NULL,
            `email` varchar(255) DEFAULT NULL,
            `password` varchar(32) NOT NULL,
            `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`username`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    }

    public function getUser($arr=[]){
        $this->db->where('username', $arr['username']);
        $this->db->where('password', $arr['password']);
        return $this->db->get($this->table)->row();
    }
}