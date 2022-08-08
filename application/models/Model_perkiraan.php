<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_perkiraan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function dataperkiraan($srnomor, $srnama, $srnoheader, $srposisidk, $srposisinr)
    {  
        $this->db->select("p.*, p2.nomor as noheader, p2.nama as namaheader, u.nama as username"); 
        $this->db->from("perkiraan as p");  
        $this->db->join('perkiraan as p2', 'p.nomorheader = p2.id', 'left');
        $this->db->join('users as u', 'p.userid = u.id', 'left');
        $this->db->where("p.hapus", 0);           
        if(!empty($srnomor))
        {
            $this->db->like("p.nomor", $srnomor);    
        }

        if(!empty($srnama))
        {
            $this->db->like("p.nama", $srnama);     
        }

        if(!empty($srnoheader))
        {
            $this->db->like("p2.nomor", $srnoheader);     
        }

        if(!empty($srposisidk))
        {
            $this->db->like("p.posisidk", $srposisidk);     
        }

        if(!empty($srposisinr))
        {
            $this->db->like("p.posisinr", $srposisinr);    
        } 
        return $this->db->get()->result_array();
    }

    public function akunpos($level)
    { 
        $this->db->select("*");
        $this->db->from("perkiraan"); 
        if($level > 0)
        {
            $this->db->where("levelno", $level); 
        } 
        $i = 0;  
        $query = $this->db->get();
        return $query->result_array();
    } 

    public function getpos($id)
    {
        $this->db->select("*");
        $this->db->from("perkiraan"); 
        $this->db->where("id", $id); 
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getakunpos($level)
    {
        $this->db->select("*");
        $this->db->from("perkiraan"); 
        if($level > 0)
        {
            $this->db->where("levelno", $level); 
        }
        $this->db->where("hapus", 0);    
        $this->db->order_by('nomor');
        $query = $this->db->get();
        return $query->result_array();
    } 

    public function getakun($id)
    {
        $this->db->select("*");
        $this->db->from("perkiraan"); 
        $this->db->where("id", $id); 
        $query = $this->db->get();
        return $query->row_array();
    } 

     
    

    

    

   
}
