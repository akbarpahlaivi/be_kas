<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_api extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function daftarperkiraan($level, $nomor)
    {
        $this->db->select("*");
        $this->db->from("perkiraan");
        if ((!empty($level)) && ($level <> null)) {
            $this->db->where("levelno", $level);
        }
        if ((!empty($nomor)) && ($nomor <> null)) {
            $this->db->where("nomor", $nomor);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getakunperkiraan($pos)
    {
        $this->db->select("*");
        $this->db->from("perkiraan");
        $this->db->where("id", $pos);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function simpaninsert($tabel, $data)
    {
        $res = $this->db->insert($tabel, $data);
        return $res;
    }

    public function simpanupdate($tabel, $data, $kunci)
    {
        $res = $this->db->update($tabel, $data, $kunci);
        return $res;
    }  

    public function caridata($dpost, $text)
    {
        $x = 0;
        $data = stripos($text, $dpost);
        if (strlen($data) > 0) {
            return $x = 1;
        }
    }

    public function nomorbaru($Ymd, $tabel, $field)
    {
        $sql = "SELECT CONCAT($Ymd, 
                RIGHT(CONCAT('000000000000', 
                IFNULL(MAX(SUBSTR($field,9,8)),'000000000000')+1),8)) AS NOMOR 
                FROM $tabel
                WHERE dperiode = $Ymd";
        $data = $this->db->query($sql);
        return $data->row_array();
    }

    public function nomorbandung($kode, $tabel, $field, $kodeakhir)
    {
        $sql = "SELECT CONCAT($kode, 
        RIGHT(CONCAT('000000000000', 
        IFNULL(MAX(SUBSTR($field,4,4)),'000000000000')+1),4), $kodeakhir) AS NOMOR 
        FROM $tabel";
        $data = $this->db->query($sql);
        return $data->row_array();
    }

    public function ceklogin($nama, $pass)
    {
        $this->db->select("u.*");
        $this->db->from("users as u"); 
        $this->db->where("u.nama", $nama);
        $this->db->where("u.password", $pass);        
        $this->db->where("u.hapus", 0);         
        $this->db->limit(1);       
        $query = $this->db->get();
        return $query->row_array();
    }

    public function testing($where)
    {
        
        $id = 0;
        $this->db->trans_start(); 
        $this->db->select("*");
        $this->db->from("perkiraan"); 
        $this->db->where('link is null');
        if(!empty($where))
        {
            $param = explode(",", $where); 
            $this->db->where_in("nama", $param);        
        } 
        $this->db->order_by('ID'); 
        $this->db->limit(1);
        $query = $this->db->get()->row_array();
        if(!empty($query))
        {
            $id = $query['id'];    
        }

        $this->db->set('link',1);
        $this->db->where('id', $id);
        $this->db->update('perkiraan');

        $this->db->select("*");
        $this->db->from("perkiraan"); 
        $this->db->where('id', $id);
        $query = $this->db->get()->row_array();
        $this->db->trans_complete();
        return $query; 
    }

    public function hapusdata($jns, $id)
    {
        $this->db->set('link',1);
        $this->db->where('id', $id);
        $this->db->update('perkiraan');
    }

    public function gettoken()
    {
        $otp    = date("dHis"); 
        $otp    = rand('1000',$otp);
        $otp    = $otp .'0000';
        $otp    = substr($otp, 0,8);   
        return $otp;
    }


    
}
