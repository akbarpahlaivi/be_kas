<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_setting extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function datasetting()
    {        
        $this->db->select("s.*"); 
        $this->db->from("setting as s");  
        $query = $this->db->get();
        return $query->row_array();
    }

     

     

   

   
}
