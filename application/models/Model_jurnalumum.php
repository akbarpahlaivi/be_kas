<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_jurnalumum extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function datajurnalumum($dari, $sampai, $srnobuk, $srtabel)
    {
        $this->db->select("j.id, j.nobukti, j.tgl, j.totaldebet, j.totalkredit, j.userid, j.tabel");  
        $this->db->select("u.nama as username");
        $this->db->from("jurnal_h as j");   
        $this->db->join('users as u', 'j.userid = u.id', 'Left'); 
        $this->db->where("j.hapus", 0);
        
        if(!empty($dari) && !empty($sampai))
        {
            $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') >= $dari");
            $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') <= $sampai"); 
        }
        if(!empty($srnobuk))
        {
            $this->db->like("j.nobukti", $srnobuk);
        }
        if(!empty($srtabel))
        {
            $this->db->like("j.tabel", $srtabel);
        }
          
        return $this->db->get()->result_array();
        
    }    

    public function getdatah($idh)
    { 
        $this->db->select("k.*"); 
        $this->db->from("jurnal_h as k");  
        $this->db->where("k.hapus", 0);
        $this->db->where("k.id", $idh);        
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getdatad($idh)
    { 
        $this->db->select("k.*, p.nomor, p.nama"); 
        $this->db->from("jurnal_d as k");  
        $this->db->join("perkiraan as p", "k.norek = p.id", "Left");  
        $this->db->where("k.hapus", 0);
        $this->db->where("k.idh", $idh);        
        $this->db->order_by("k.id");     
        $query = $this->db->get();
        return $query->result_array();
    }

    public function jurnalumum($periode)
    {
        $this->db->select("h.nobukti, h.tgl, h.tabel"); 
        $this->db->select("d.debet, d.kredit"); 
        $this->db->select("p.nomor as norek, p.nama"); 
        $this->db->from("jurnal_d as d");  
        $this->db->join("jurnal_h as h", "d.idh = h.id", "Left");  
        $this->db->join("perkiraan as p", "p.id = d.norek", "Left");  
        $this->db->where("h.periode", $periode); 
        $query = $this->db->get();
        return $query->result_array();
    }

    

    

   
}
