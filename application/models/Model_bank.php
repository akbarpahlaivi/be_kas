<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_bank extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 
     

    public function databank($srnama, $srnamaakun, $srnoakun, $srnorek, $srpemilik)
    {  
        $this->db->select("b.id, b.kode, b.nama, b.norek, b.pemilik, b.pos, b.alamat, b.telp"); 
        $this->db->select("p.nomor as nomerakun, p.nama as namaakun, u.nama as username");
        $this->db->from("bank as b"); 
        $this->db->join('users as u', 'b.userid = u.id');
        $this->db->join('perkiraan as p', 'b.pos = p.id', 'Left'); 
        $this->db->where("b.hapus", 0); 

        if(!empty($srnama))
        {
            $this->db->like("b.nama", $srnama); 
        } 

        if(!empty($srnorek))
        {
            $this->db->like("b.norek", $srnorek); 
        }

        if(!empty($srpemilik))
        {
            $this->db->like("b.pemilik", $srpemilik); 
        }

        if(!empty($srnamaakun))
        {
            $this->db->like("p.nama", $srnamaakun); 
        }
        
        if(!empty($srnoakun))
        {
            $this->db->like("p.nomor", $srnoakun); 
        }  

        return $this->db->get()->result_array();
    }

    

    public function getbank()
    {
        $this->db->select("*");
        $this->db->from("bank"); 
        $this->db->where("hapus", 0);
        $query = $this->db->get();
        return $query->result_array();
    } 

    

   
}
