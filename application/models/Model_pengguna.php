<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_pengguna extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function daftarstatus()
    {
        $this->db->select("*");
        $this->db->from("status"); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function daftarpengguna($iduser)
    {
        $this->db->select("*");
        $this->db->from("users");
        if (!empty($iduser)) {
            $this->db->where("id", $iduser);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function datapengguna($srnama)
    {
       
        $this->db->select("g.id, g.nama, g.nickname, g.hapus"); 
        $this->db->from("users as g");  
        if(!empty($srnama))
        {
            $this->db->like("g.nama", $srnama); 
            $this->db->like("g.nickname", $srnama); 
        }  
        return $this->db->get()->result_array();
    }

    public function countdata()
    {
        $n        = 0; 
        $this->db->select("count(id) as jml"); 
        $this->db->from("users"); 
        $query = $this->db->get()->row_array();
        $n     = $query['jml'];  
        return $n;
    }

    public function getmenutemp($user)
    {
        $this->db->select("*");
        $this->db->from("menutemp");
        $this->db->where("username", $user); 
        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function getmenu($user)
    {
        $this->db->select("*");
        $this->db->from("hakakses");
        $this->db->where("username", $user); 
        $this->db->where("nama", "Cabang"); 
        $query = $this->db->get();
        return $query->row_array(); 
    }

    public function hakakses($user)
    {
        $this->db->select("id, nama, urutan, jenis, grup"); 
        $this->db->select("IF(lihat>0, 'true', 'false') as lihat"); 
        $this->db->from("hakakses");
        $this->db->where("username", $user); 
        $this->db->order_by("grup, urutan"); 
        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function cektabelusercabang($tabel, $field, $value, $id)
    {
        $g = 0;  
        $this->db->select($field); 
        $this->db->from($tabel);  
        $this->db->where("hapus", 0); 
        if(!empty($id)) 
        {
            $this->db->where("id", $id);                   
        }
        $this->db->where($field, $value);                  
        $g = $this->db->get()->num_rows();
        return $g;
    }

   

   
}
