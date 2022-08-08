<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kask extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function datakaskeluar($srnobukti, $srkodebank, $srnamabank, $srdesk, $dari, $sampai)
    {
        $this->db->select("k.id, k.nobukti, k.tgl, k.kodebank, k.totaldebet, k.totalkredit, k.userid, k.deskripsi"); 
        $this->db->select("b.kode, b.nama as namabank");
        $this->db->select("u.nama as username");
        $this->db->from("trankask_h as k"); 
        $this->db->join('bank as b', 'b.id = k.kodebank', 'Left'); 
        $this->db->join('users as u', 'k.userid = u.id', 'Left'); 
        $this->db->where("k.hapus", 0);
        
        if(!empty($dari) && !empty($sampai))
        {
            $this->db->where("DATE_FORMAT(k.tgl,'%Y%m%d') >= $dari");
            $this->db->where("DATE_FORMAT(k.tgl,'%Y%m%d') <= $sampai"); 
        }
        

        // $srnobukti, $srkodebank, $srnamabank, $srdesk
        if(!empty($srnobukti))
        {
            $this->db->like("k.nobukti", $srnobukti); 
        }

        if(!empty($srkodebank))
        {
            $this->db->like("b.kode", $srkodebank); 
        }

        if(!empty($srnamabank))
        {
            $this->db->where("b.nama", $srnamabank); 
        }

        if(!empty($srdesk))
        {
            $this->db->like("k.deskripsi", $srdesk); 
        }

        return $this->db->get()->result_array();
    }     

    public function getroot()
    {  
        $this->db->select("*"); 
        $this->db->from("setting");  
        return $this->db->get()->row_array(); 
    }

    public function getdatah($idh)
    { 
        $this->db->select("k.*, b.nama as namabank"); 
        $this->db->from("trankask_h as k");  
        $this->db->join("bank as b", "k.kodebank = b.id", "Left");  
        $this->db->where("k.hapus", 0);
        $this->db->where("k.id", $idh);        
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getdatad($idh)
    { 
        $this->db->select("k.*, p.nomor, p.nama"); 
        $this->db->from("trankask_d as k");  
        $this->db->join("perkiraan as p", "k.norek = p.id", "Left");  
        $this->db->where("k.hapus", 0);
        $this->db->where("k.idh", $idh);        
        $this->db->order_by("k.id");     
        $query = $this->db->get();
        return $query->result_array();
    }

    public function datakask($id)
    {
        $this->db->select("d.debet, d.kredit, d.keterangan"); 
        $this->db->select("h.id, h.nobukti, h.tgl, h.totaldebet, h.totalkredit, h.userid, h.deskripsi"); 
        $this->db->select("b.kode as kodebank, b.nama as namabank");
        $this->db->select("u.nama as username");
        $this->db->select("p.nomor, p.nama as namaperkiraan");
        $this->db->from("trankask_d as d"); 
        $this->db->join('trankask_h as h', 'h.id = d.idh', 'Left');
        $this->db->join('perkiraan as p', 'p.id = d.norek', 'Left');
        $this->db->join('bank as b', 'b.id = h.kodebank', 'Left');
        $this->db->join('users as u', 'h.userid = u.id', 'Left'); 
        $this->db->where("d.hapus", 0);
        $this->db->where("d.idh", $id);        
        $this->db->order_by("d.id");     
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updtotal($id)
    {
        $q = " UPDATE trankask_h h 
             INNER JOIN
             (
                SELECT idh, COALESCE(SUM(debet),0) AS totaldebet, COALESCE(SUM(kredit),0) AS totalkredit
                FROM trankask_d where idh = $id and hapus = 0
                ) d ON h.id = d.idh
                SET h.totaldebet = d.totaldebet, h.totalkredit = d.totalkredit
                WHERE h.id = $id ";
        $d  = $this->db->query($q);
        return $d;   
    }

    

   
}
