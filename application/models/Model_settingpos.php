<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_settingpos extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function datasetting()
    {        
        $this->db->select("*"); 
        $this->db->from("pos");    
        $query = $this->db->get();
        return $query->row_array();
    }

    public function datasettingtemp()
    {        
        $this->db->select("*"); 
        $this->db->from("pos_temp");    
        $query = $this->db->get();
        return $query->row_array();
    }

     

     

   

   
}
