<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_saldo extends CI_Model
{   

    public function psaldo($periode)
    {
        $this->deletedata('jurnal_h', array('hapus'  => 1));
        $this->deletedata('jurnal_d', array('hapus'  => 1));
        $oldperiode  = $this->get_oldperiode($periode);
        $newperiode  = $this->get_newperiode($periode);
        $pos         = $this->posperkiraan();  

        if(!empty($pos))
        {
            $lababulanberjalan = $pos['lababulanberjalan'];
            $labatahunberjalan = $pos['labatahunberjalan'];  
        }
        else
        {
            $lababulanberjalan = 0;
            $labatahunberjalan = 0;
        } 
        
        $saldoawal         = 0;
        $labajln           = 0;
        $labatahan         = 0;
        $saldolabatahan    = 0;
        $debetrl           = 0;
        $kreditrl          = 0;
        $rld               = 0;
        $rlk               = 0; 
        $tabel             = "perkiraan_saldo";
        // cek jurnal ada data ?
        $cekjurnal         = $this->cekdatajurnal(); 
        if(empty($cekjurnal))
        {
            $hapussaldo = $this->db->query("delete from perkiraan_saldo");
        }
        // hitung saldo akhir periode sebelumnya
        $res               = $this->updtotalpsaldo($oldperiode); 
        // hapus saldo akhir sekarang 
        $hapus             = $this->deletedata("perkiraan_saldo", 
                                         array('periode'   => $periode)); 
        /* saldo akhir periode sebelumnya jadikan saldo awal periode berjalan */       
        $psal   = $this->perkiraan_saldo($oldperiode);    
        if(!empty($psal))
        {
            foreach ($psal as $key => $value) {
                $saldoakhird = $value['saldoakhird'];
                $saldoakhirk = $value['saldoakhirk'];  
                $norek       = $value['nomor'];
                $posisidk    = $value['posisidk'];
                $posisinr    = $value['posisinr']; 
               
                if($norek==$lababulanberjalan)
                {
                    $labajln=$saldoakhirk;
                }
                if($norek==$labatahunberjalan)
                {
                    $labatahan=$saldoakhirk;
                }
                /* Laba Periode Berjalan + Laba di Tahan*/
                $saldolabatahan = $labajln + $labatahan;
                if($posisinr=='NERACA')
                {
                    if($norek==$lababulanberjalan)
                    {
                        $saldoawal = 0;
                    }
                    elseif($norek==$labatahunberjalan)
                    {
                        $saldoawal = $saldolabatahan;                        
                    }
                    else
                    {
                        if($posisidk=='DEBET')
                        {
                            $saldoawal = $saldoakhird;
                        }
                        else
                        {
                            $saldoawal = $saldoakhirk;
                        } 
                    } 
                } 
                /* isi akun awal dari periode sebelumnya*/
                $dataawal  = array(
                                    'nomor'              => $norek,
                                    'saldoawal'          => $saldoawal,
                                    'debet'              => 0,
                                    'kredit'             => 0,
                                    'memorial_debet'     => 0,
                                    'memorial_kredit'    => 0,
                                    'penyesuaian_kredit' => 0,
                                    'penyesuaian_debet'  => 0,
                                    'periode'            => $periode);
                $res   = $this->simpaninsert($tabel, $dataawal); 


            } 
        }
        /* Update Saldo Awal Laba Di tahan periode Berjalan */
        $cklbtahan = $this->cekperkiraan_saldo($labatahunberjalan, $periode);
        if(!empty($cklbtahan))
        {
            $res   = $this->simpanupdate($tabel,
                                   array('saldoawal'          => $saldolabatahan,
                                         'debet'              => 0,
                                         'kredit'             => 0,
                                         'memorial_debet'     => 0,
                                         'memorial_kredit'    => 0,
                                         'penyesuaian_kredit' => 0,
                                         'penyesuaian_debet'  => 0), 
                                   array('periode'            => $periode, 
                                         'nomor'              => $labatahunberjalan,
                                         ));
        }
        else
        {
            $res   = $this->simpaninsert($tabel,
                                   array('nomor'              => $labatahunberjalan,
                                         'saldoawal'          => $saldolabatahan,
                                         'debet'              => 0,
                                         'kredit'             => 0,
                                         'memorial_debet'     => 0,
                                         'memorial_kredit'    => 0,
                                         'penyesuaian_kredit' => 0,
                                         'penyesuaian_debet'  => 0,
                                         'periode'            => $periode,
                                         ));
        }



        /* cek saldo akun data jurnal umum periode berjalan 
           jika ada saldo di update 
           jika tidak ada insert saldo awal perkiraan_saldo
        */
        $jenisjurnal = $this->jenisjurnalsaldo($periode);  
        
        // die;
        $groupsaldo  = count($jenisjurnal['norek']);  
        if($groupsaldo > 0)
        {
            $biasa         = array_filter($jenisjurnal['jenisjurnal'], function($value){
                return $value['jenis']=='BIASA';
            });  
            $penyesuaian   = array_filter($jenisjurnal['jenisjurnal'], function($value){
                return $value['jenis']=='PENYESUAIAN';
            }); 
            $memorial      = array_filter($jenisjurnal['jenisjurnal'], function($value){
                return $value['jenis']=='MEMORIAL';
            }); 

            foreach ($jenisjurnal['norek'] as $key => $d) 
            {
                $norek  = $d['norek']; 
                // hitung total biasa per norek
                $debetbiasa     = 0;
                $kreditbiasa    = 0;
                foreach ($biasa as $key => $value) 
                {
                    if($value['norek']==$norek){
                        $debetbiasa     = $debetbiasa + $value['debet'];
                        $kreditbiasa    = $kreditbiasa + $value['kredit']; 
                    } 
                } 
                // hitung total memorial per norek
                $debetmemorial  = 0;
                $kreditmemorial = 0;
                foreach ($memorial as $key => $value) 
                {
                    if($value['norek']==$norek){
                        $debetmemorial  = $debetmemorial + $value['debet'];
                        $kreditmemorial = $kreditmemorial + $value['kredit']; 
                    } 
                }  
                // hitung total penyesuaian per norek
                $debetpenyesuaian  = 0;
                $kreditpenyesuaian = 0;
                foreach ($penyesuaian as $key => $value) 
                {
                    if($value['norek']==$norek){
                        $debetpenyesuaian  = $debetpenyesuaian + $value['debet'];
                        $kreditpenyesuaian = $kreditpenyesuaian + $value['kredit']; 
                    } 
                } 
                /* insert / update perkiraan_saldo sesuai dengan 
                   saldo akun jurnal periode berjalan 
                   kecuali akun laba bulan berjalan dan laba di tahan
                */
                if($norek <> $lababulanberjalan  &&  $norek <> $labatahunberjalan)
                {
                    $cek = $this->cekperkiraan_saldo($norek, $periode);
                    $cek = count($cek);
                    if($cek > 0)
                    {  
                        $res   = $this->simpanupdate($tabel, 
                                               array('debet'              => $debetbiasa,
                                                     'kredit'             => $kreditbiasa,
                                                     'memorial_debet'     => $debetmemorial,
                                                     'memorial_kredit'    => $kreditmemorial,
                                                     'penyesuaian_kredit' => $kreditpenyesuaian,
                                                     'penyesuaian_debet'  => $debetpenyesuaian), 
                                               array('periode'            => $periode, 
                                                     'nomor'              => $norek, 
                                                     )); 
                    }
                    else
                    {  
                        $res   = $this->simpaninsert($tabel, 
                                               array('saldoawal'          => 0,
                                                     'debet'              => $debetbiasa,
                                                     'kredit'             => $kreditbiasa,
                                                     'memorial_debet'     => $debetmemorial,
                                                     'memorial_kredit'    => $kreditmemorial,
                                                     'penyesuaian_kredit' => $kreditpenyesuaian,
                                                     'penyesuaian_debet'  => $debetpenyesuaian,
                                                     'nomor'              => $norek,
                                                     'periode'            => $periode,
                                                     ));  
                    }
                } 
                
                /* laba periode berjalan */  
                if($norek == $lababulanberjalan  &&  $norek <> $labatahunberjalan)
                { 
                    $rugilaba    = $this->jenisjurnalsaldo($periode); 
                    $rugilaba    = array_filter($rugilaba['jenisjurnal'], function($value){
                        return $value['posisineraca']=='R/L';
                    });
                    // sum debet & kredit rugi laba 
                    foreach ($rugilaba as $key => $value) 
                    {
                        $debetrl    = $debetrl + $value['debet'];
                        $kreditrl   = $kreditrl + $value['kredit']; 
                    }
                    $cek = $this->cekperkiraan_saldo($norek, $periode); 
                    if(!empty($cek))
                    { 
                        $res   = $this->model_hitungulang->simpanupdate($tabel, 
                                                                array('debet'              => 0,
                                                                      'kredit'             => $kreditrl - $debetrl,
                                                                      'memorial_debet'     => 0,
                                                                      'memorial_kredit'    => 0,
                                                                      'penyesuaian_kredit' => 0,
                                                                      'penyesuaian_debet'  => 0), 
                                                                array('periode'            => $periode,
                                                                      'nomor'              => $norek, 
                                                                      )); 
                    }
                    else
                    {  
                        $res   = $this->model_hitungulang->simpaninsert($tabel,  
                                                                array('debet'              => 0,
                                                                      'kredit'             => $kreditrl - $debetrl,
                                                                      'memorial_debet'     => 0,
                                                                      'memorial_kredit'    => 0,
                                                                      'penyesuaian_kredit' => 0,
                                                                      'penyesuaian_debet'  => 0,
                                                                      'saldoawal'          => 0,
                                                                      'nomor'              => $norek,
                                                                      'periode'            => $periode,
                                                                      ));  
                    } 
                }
                
            }
        }
        // $res        = $this->updtotalpsaldo($periode); 
        /* insert akun laba periode berjalan 
           insert akun laba di tahan
        */
        $drl        = $this->getsaldorl2($periode); 
        foreach ($drl as $rl)
        {
            $rld = $rld + $rl['debet'];
            $rlk = $rlk + $rl['kredit']; 
        }
        $laba       = $this->cekperkiraan_saldo($lababulanberjalan, $periode); 
        if(!empty($laba))
        {
            $res   = $this->simpanupdate($tabel, 
                                array('kredit'  => $rlk-$rld), 
                                array('nomor'   => $lababulanberjalan,
                                      'periode' => $periode,
                                      ));   
        }
        else
        {
            $data  = array('nomor'              => $lababulanberjalan,
                           'periode'            => $periode,
                           'debet'              => 0,
                           'kredit'             => $rlk-$rld, 
                           'memorial_debet'     => 0,
                           'memorial_kredit'    => 0,
                           'penyesuaian_debet'  => 0,
                           'penyesuaian_kredit' => 0,
                            );
            $res   = $this->simpaninsert($tabel, $data);
        } 
        
        $res = $this->updtotalpsaldo($periode);         
    }

    public function cekpsaldo($idakun, $periode)
    { 
        $this->db->select("*");
        $this->db->from("perkiraan_saldo"); 
        $this->db->where("nomor", $idakun);  
        $g = $this->db->get()->result_array();
        return $g;
    }

    public function posperkiraan()
    {
        $this->db->select("*");
        $this->db->from("pos");   
        $g = $this->db->get()->row_array();
        return $g; 
    }

    public function cekdatajurnal()
    {
        $this->db->select("*");
        $this->db->from("jurnal_h");          
        $g = $this->db->get()->result_array();
        return $g; 
    }

    public function updtotalpsaldo($periode)
    {
        $res = $this->db->query("UPDATE perkiraan_saldo SET 
            saldoakhirk = saldoawal - debet + kredit - memorial_debet + memorial_kredit - penyesuaian_debet + penyesuaian_kredit, 
            saldoakhird = saldoawal + debet - kredit + memorial_debet - memorial_kredit + penyesuaian_debet - penyesuaian_kredit 
            WHERE nomor=nomor AND periode='$periode'");
        return $res; 
    }

    public function perkiraan_saldo($periode)
    { 
        $this->db->select("s.*, p.nama, p.posisidk, p.posisinr");
        $this->db->from("perkiraan_saldo as s");  
        $this->db->join("perkiraan as p", "s.nomor = p.id", "Left");
        $this->db->where("s.periode", $periode);
        $this->db->order_by("s.nomor");         
        $g = $this->db->get()->result_array();
        return $g;  
    }

     public function cekperkiraan_saldo($nomor, $periode)
     { 
        $this->db->select("*");
        $this->db->from("perkiraan_saldo");    
        $this->db->where("nomor", $nomor);    
        $this->db->where("periode", $periode);            
        $g = $this->db->get()->result_array();
        return $g;  
    }

    public function jenisjurnalsaldo($periode)
    { 
      $jenis  = $this->jenisjurnal($periode); 
      $ju     = [];
      foreach ($jenis as $key => $value)
      {   
        $ju[] = [ 
                          'nobukti'         => $value['nobukti'],
                          'norek'           => $value['norek'],
                          'nama'            => $value['nama'],
                          'debet'           => $value['debet'],
                          'kredit'          => $value['kredit'],
                          'posisidk'        => $value['posisidk'],
                          'posisineraca'    => $value['posisinr'],
                          'jenis'           => $value['jenis'],
                          'periode'         => $periode 
                        ];  
      } 
        
      $result = array();
      foreach ($ju as $element) 
      {
          $result[$element['norek']][] = $element;
      } 
      $group = $result;


      $result_key = array_keys($result);
      $norek = [];
      foreach ($result_key as $key => $value) {
          $norek [] = ['norek'    => $value];            
      }
      // array map = olah isi array 
      $result = array_map(function ($no_rekening) 
      {
        // Ambil debet 
        $all_debet = array_map(function ($detail) {
            $total_debet = $detail['debet'];
            return $total_debet;
        }, $no_rekening);
        // Ambil kredit
        $all_kredit = array_map(function ($detail) {
            $total_debet = $detail['kredit'];
            return $total_debet;
        }, $no_rekening);

        // array reduce akumulasi 
        // Jumlahkan semua debet
        $all_debet = array_reduce($all_debet, function ($total, $item) {
            $total += $item;
            return $total;
        });
        // Jumlahkan semua kredit
        $all_kredit = array_reduce($all_kredit, function ($total, $item) {
            $total += $item;
            return $total;
        });

        return [
            'debet'  => $all_debet,
            'kredit' => $all_kredit
        ];
      }, $result); 

      $output = array(
          'jenisjurnal' => $ju,
          'group'       => $group,
          'norek'       => $norek,
          'total'       => $result
      );   
      return $output; 
    }

    public function jenisjurnal($periode)
    {
        $this->db->select("d.norek, d.debet, d.kredit");
        $this->db->select("h.nobukti, h.periode ");   
        $this->db->select("CASE 
                            WHEN h.jenis = 'PENYESUAIAN' THEN 'PENYESUAIAN'
                            WHEN h.jenis = 'MEMORIAL' THEN 'MEMORIAL'
                          ELSE 'BIASA'
                          END AS jenis");
        $this->db->select("p.nama, p.posisidk, p.posisinr");   
        $this->db->from("jurnal_d as d");    
        $this->db->join("jurnal_h as h", "h.id = d.idh", "Left");    
        $this->db->join("perkiraan as p", "p.id = d.norek", "Left");    
        $this->db->where("h.periode", $periode);            
        $this->db->where("d.hapus", 0);    
        $g = $this->db->get()->result_array();
        return $g;  
    }

    public function getsaldorl2($periode){
        $data = $this->db->query("SELECT COALESCE(SUM(debet),0) AS debet, COALESCE(SUM(kredit),0) AS kredit, norek, posisinr FROM jurnal_d d LEFT JOIN jurnal_h h 
            ON d.idh=h.id
            left join perkiraan p
            on d.norek=p.id
            WHERE periode = '$periode' AND posisinr='R/L' 
            GROUP BY norek");
        return $data->result_array();
    }

    public function simpanupdate($tabel, $data, $kunci)
    {
        $res = $this->db->update($tabel, $data, $kunci);
        return $res;
    }

    public function simpaninsert($tabel, $data)
    {
        $res = $this->db->insert($tabel, $data);
        return $res;
    } 

    public function deletedata($tabel, $kunci){
        $res = $this->db->delete($tabel,$kunci);
        return $res; 
    }

    function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }  

    public function cektabelusercabang($tabel, $field, $value, $branch, $user)
    {
        $g = 0;  
        $this->db->select('id'); 
        $this->db->from($tabel); 
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($field, $value);     
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function cekusercabang($nama, $pass, $branch)
    { 
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("nama", $nama);
        $this->db->where("password", $pass);
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0);
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function getidtabelusercabang($tabel, $field, $value, $branch, $user)
    {
        $g = 0;  
        $this->db->select('*'); 
        $this->db->from($tabel); 
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($field, $value);                  
        $this->db->limit(1);                  
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function get_oldperiode($periode)
    {
        $thn        = substr($periode, 0, 4);  
        $bln        = substr($periode, 5, 6);  
        $bln        = ((int)$bln);
        $oldperiode = null;
        if($bln<=10)
        { 
            if($bln==1)
            {
                $bln        = 12; 
                $thn        = ((int)$thn)-1;
                $oldperiode = $thn. $bln;  
            }
            else
            {
               
                $bln        = $bln-1; 
                $oldperiode = $thn .'0'. $bln;   
            }
        }
        else
        {
            $bln        = $bln-1; 
            $oldperiode = $thn . $bln;    
        }
        return $oldperiode;
    }

    public function get_newperiode($periode)
    {  
        $thn        = substr($periode, 0, 4);  
        $bln        = substr($periode, 5, 6);  
        $bln        = ((int)$bln);
        $newperiode = null;
        if($bln<10){  
            $bln        = $bln+1; 
            $newperiode = $thn . '0'. $bln;       
        }else{
            if($bln==12){
                $bln        = 1; 
                $thn        = ((int)$thn)+1;
                $newperiode = $thn . '0'. $bln;  
            }else{
                $bln        = $bln+1; 
                $newperiode = $thn . $bln;   
            } 
        }
        return $newperiode;
    }


}
