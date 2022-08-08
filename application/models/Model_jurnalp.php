<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_jurnalp extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 

    public function datajurnalp($search, $length, $start, $order, $branch, $dari, $sampai, $lihatall, $user)
    {
        $wh = "PENYESUAIAN, MEMORIAL";
        $wh = explode(",", $wh);
        $root = $this->getroot()['root'];
        $column_search = array('j.nobukti', 'j.tabel', 'j.totaldebet', 'j.totalkredit', 'c.nama');
        $this->db->select("j.id, j.nobukti, j.tgl, j.totaldebet, j.totalkredit, j.userid, j.tabel, j.jenis");  
        $this->db->select("c.nama as cabang, u.nama as username");
        $this->db->from("jurnal_h as j");  
        $this->db->join('cabang as c', 'j.cabang = c.id', 'Left');
        $this->db->join('users as u', 'j.userid = u.id', 'Left'); 
        $this->db->where("j.hapus", 0); 
         $this->db->where_in("j.jenis", $wh);
        if($branch <> $root)
        {
            $this->db->where("j.cabang", $branch);

        }
        if(!empty($dari) && !empty($sampai))
        {
            $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') >= $dari");
            $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') <= $sampai"); 
        }
        if($lihatall == 0)
        {
            $this->db->where("j.userid", $user);
        }

        $i = 0; 
        foreach ($column_search as $item) // looping awal
        {
            if(!empty($search))   
            {
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $search);
                }
                else
                {
                    $this->db->or_like($item, $search);
                }
                if(count($column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }  
       
        if(empty($order))
        {
            $this->db->order_by('j.tgl');  
        }
        else
        {
            $this->db->order_by($order);   
        }
        
        if($start == 0)
        {
            $this->db->limit($length, $start);
        }
        else
        { 
            $this->db->limit($length, $start);
        } 
        
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function countdata($branch, $dari, $sampai, $lihatall, $user)
    {
        $n    = 0; 
        $root = $this->getroot()['root'];
        $this->db->select("count(id) as jml"); 
        $this->db->from("jurnal_h as j"); 
        $this->db->where("j.hapus", 0);
        $this->db->where_in("j.jenis", "PENYESUAIAN, MEMORIAL");
        if($lihatall == $root)
        {
            $this->db->where("k.userid", $user);
        }
        $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') >= $dari");
        $this->db->where("DATE_FORMAT(j.tgl,'%Y%m%d') <= $sampai"); 
        if($branch <> $root)
        {
            $this->db->where("j.cabang", $branch); 
        }
        $query = $this->db->get()->row_array();
        $n     = $query['jml'];  
        return $n;
    }

    public function getroot()
    {  
        $this->db->select("*"); 
        $this->db->from("setting");  
        return $this->db->get()->row_array(); 
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

    public function updtotal($id)
    {
        $q = " UPDATE jurnal_h h 
             INNER JOIN
             (
                SELECT idh, COALESCE(SUM(debet),0) AS totaldebet, COALESCE(SUM(kredit),0) AS totalkredit
                FROM jurnal_d where idh = $id and hapus = 0
                ) d ON h.id = d.idh
                SET h.totaldebet = d.totaldebet, h.totalkredit = d.totalkredit
                WHERE h.id = $id ";
        $d  = $this->db->query($q);
        return $d;   
    }

    

    

   
}
