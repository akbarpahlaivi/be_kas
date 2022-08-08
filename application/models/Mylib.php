<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mylib extends CI_Model
{

    public function getsetting()
    { 
        $this->db->select("*") ;
        $this->db->from("setting"); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function CekUser($user, $pass)
    {
        $res = 100;
        $this->hapuslogexp();
        $this->updatelog();
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("nama", $user);
        $this->db->where("password", $pass);
        $q = $this->db->get();
        $this->db->last_query();
        if($q->num_rows()==1)
        {
            $res = 101;
        }             
        return $res;
    }

    public function mainmenu($user, $pass, $jenis)
    {
        if($jenis == 'header')
        {
            $this->db->select('h.kategori, h.grup');
            $this->db->from("hakakses as h");
            $this->db->join("users as u", "h.username = u.id"); 
            $this->db->join("loglogin as l", "h.username = l.user"); 
            $this->db->where('h.username', $user);
            $this->db->where('l.token', $pass);
            $this->db->where('l.status', 'on'); 
            $this->db->where('h.grup <> 900'); 
            $this->db->group_by('kategori');
            $this->db->order_by('grup');
        }
        else
        {
            $this->db->select('h.kode, h.nama');
            $this->db->from("hakakses as h");
            $this->db->join("users as u", "h.username = u.id"); 
            $this->db->join("loglogin as l", "h.username = l.user"); 
            $this->db->where('l.token', $pass);
            $this->db->where('l.status', 'on'); 
            $this->db->where('h.lihat', 1);
            $this->db->where('h.kategori', $jenis); 
            $this->db->order_by('urutan'); 
        } 

        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function menulaporan($user, $pass, $jenis)
    {
        $this->db->select('h.kode, h.jenis');
        $this->db->from("hakakses as h");
        $this->db->join("users as u", "h.username = u.id"); 
        $this->db->join("loglogin as l", "h.username = l.user"); 
        $this->db->where('l.token', $pass);
        $this->db->where('l.status', 'on'); 
        $this->db->where('h.lihat', 1);
        $this->db->where('h.kategori', $jenis); 
        $this->db->group_by('jenis');
        $this->db->order_by('urutan'); 
        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function daftarsettingpos()
    { 
        $this->db->select("*") ;
        $this->db->from("pos"); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updatelog($idlog)
    {
        date_default_timezone_set('Asia/Jakarta');
        $exp    = date('YmdHis', strtotime(date('YmdHis') . ' + 2 hours'));
        $tabel  = "loglogin"; 
        $kunci  = array(
            'id'     => $idlog,
            'status' => 'on'
        );
        $data   = array('expdperiode' => $exp);
        $data   = $this->simpanupdate($tabel, $data, $kunci);
    }

    public function hapusloguser()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tabel  = "loglogin";
        $id     = $this->session->userdata('idlog');
        $kunci  = array(
            'user'   => $this->session->userdata('pengguna'),
            'id'     => $id,
            'status' => 'on'
        );
        $data   = array(
            'status' => 'off',
            'ot'     => date('Y-m-d H:i:s')
        );
        $data   = $this->simpanupdate($tabel, $data, $kunci);
    }

    public function hapuslogexp()
    {
        date_default_timezone_set('Asia/Jakarta');
        $now      = date('YmdHis');
        $query    = "update loglogin set status = 'off'   
                     where expdperiode <= $now ";
        $data     = $this->db->query($query);
        return $data;  
    }

    public function ceklog()
    {
        date_default_timezone_set('Asia/Jakarta');
        $g   = 0;
        $exp = date('YmdHis');
        $this->db->select("expdperiode");
        $this->db->from("loglogin");
        $this->db->where("expdperiode >= $exp ");
        $this->db->where("status", 'on');
        $rnt = $this->db->get();
        $g   = $g + $rnt->num_rows();
        return $g;
    }

    public function tutup()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id  = $this->session->userdata('idlog');
        $g   = 0;
        $exp = date('YmdHis');
        $this->db->select("id");
        $this->db->from("loglogin");
        $this->db->where("id", $id);
        $this->db->where("expdperiode <= $exp");
        $rnt = $this->db->get();
        $g   = $g + $rnt->num_rows();
        if ($g > 0) {
            redirect(base_url('login/logout'));
        }
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

    public function gewnewid($tabel)
    {
        $data = $this->db->query("call ai('$tabel')");
        mysqli_next_result($this->db->conn_id);
        return $data->result_array();
    }

    public function daftarmenu($jenis)
    {
        $pengguna   = $this->session->userdata('pengguna');
        $data       = $this->db->query("select kode, nama, jenis from hakakses where username='$pengguna' and jenis='$jenis' and lihat=1 order by urutan ");
        return $data->result_array();
    }
    
    public function hitungsaldo($datahitung)
    {
        // setting 
        $setting        = $this->getsetting();
        // param
        $periode        = $datahitung['periode'];
        $oldperiode     = $datahitung['oldperiode'];

        $labablnjln     = 0;
        $labathjln      = 0;
        $pos = $this->getpos(); 
        if(!empty($pos))
        {
            $labablnjln = $pos['lababulanberjalan'];
            $labathjln  = $pos['labatahunberjalan'];  
        } 
        
        
        $saldoawal      = 0;
        $labajln        = 0;
        $labatahan      = 0;
        $saldolabatahan = 0;
        $debetrl        = 0;
        $kreditrl       = 0;
        $rld            = 0;
        $rlk            = 0; 
        $tabel          = "perkiraan_saldo";
        
        // hitung saldo akhir periode sebelumnya 
        $this->updatesaldoakhir($periode);

        $this->db->trans_start();  
        // hapus perkiraan_saldo
        $this->deletedata($tabel, array("periode" => $periode));
        
        /* saldo akhir periode sebelumnya jadikan saldo awal periode berjalan */   
        $this->db->select("*");   
        $this->db->from($tabel);   
        $this->db->where("periode", $oldperiode);  
        $psaldo = $this->db->get()->result_array();  
        foreach ($psaldo as $key => $value) 
        {
            $saldoakhird = $value['saldoakhird'];
            $saldoakhirk = $value['saldoakhirk'];  
            $norek       = $value['nomor'];
            $posisidk    = $value['posisidk'];
            $posisinr    = $value['posisinr']; 
           
            if($norek==$labablnjln)
            {
                $labajln=$saldoakhirk;
            }
            if($norek==$labathjln)
            {
                $labatahan=$saldoakhirk;
            }
            /* Laba Periode Berjalan + Laba di Tahan*/
            $saldolabatahan    = $labajln + $labatahan;
            if($posisinr=='NERACA')
            {
                if($norek==$labablnjln)
                {
                    $saldoawal = 0;
                }
                elseif($norek==$labathjln)
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
            $this->simpaninsert($tabel, 
                        array(
                           'nomor'              => $norek,
                           'saldoawal'          => $saldoawal,
                           'debet'              => 0,
                           'kredit'             => 0,
                           'memorial_debet'     => 0,
                           'memorial_kredit'    => 0,
                           'penyesuaian_kredit' => 0,
                           'penyesuaian_debet'  => 0,
                           'periode'            => $periode)
                        ); 
        }

        /* Update Saldo Awal Laba Di tahan periode Berjalan */
        $cklbtahan = $this->ceksaldo($labathjln, $periode);   
        if(!empty($cklbtahan))
        { 
            $this->simpanupdate($tabel, 
                            array( 
                                'saldoawal'          => $saldolabatahan,
                                'debet'              => 0,
                                'kredit'             => 0,
                                'memorial_debet'     => 0,
                                'memorial_kredit'    => 0,
                                'penyesuaian_kredit' => 0,
                                'penyesuaian_debet'  => 0),
                            array(
                                'periode'            => $periode, 
                                'nomor'              => $labathjln)
                        );
        }
        else
        { 
            $this->simpaninsert($tabel, 
                            array( 
                                'nomor'              => $labathjln,
                                'saldoawal'          => $saldolabatahan,
                                'debet'              => 0,
                                'kredit'             => 0,
                                'memorial_debet'     => 0,
                                'memorial_kredit'    => 0,
                                'penyesuaian_kredit' => 0,
                                'penyesuaian_debet'  => 0,
                                'periode'            => $periode)
                        );
        }
       
        /* cek saldo akun data jurnal umum periode berjalan 
           jika ada saldo di update 
           jika tidak ada insert saldo awal perkiraan_saldo
        */
        $jenisjurnal  = $this->jurnal->jenisjurnalsaldo($periode);   
        $groupsaldo   = count($jenisjurnal['norek']);   
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
                    if($value['norek']==$norek)
                    {
                        $debetbiasa     = $debetbiasa + $value['debet'];
                        $kreditbiasa    = $kreditbiasa + $value['kredit']; 
                    } 
                } 
                // hitung total memorial per norek
                $debetmemorial  = 0;
                $kreditmemorial = 0;
                foreach ($memorial as $key => $value) 
                {
                    if($value['norek']==$norek)
                    {
                        $debetmemorial  = $debetmemorial + $value['debet'];
                        $kreditmemorial = $kreditmemorial + $value['kredit']; 
                    } 
                }  
                // hitung total penyesuaian per norek
                $debetpenyesuaian  = 0;
                $kreditpenyesuaian = 0;
                foreach ($penyesuaian as $key => $value) 
                {
                    if($value['norek']==$norek)
                    {
                        $debetpenyesuaian  = $debetpenyesuaian + $value['debet'];
                        $kreditpenyesuaian = $kreditpenyesuaian + $value['kredit']; 
                    } 
                } 
                /* insert / update perkiraan_saldo sesuai dengan 
                   saldo akun jurnal periode berjalan 
                   kecuali akun laba bulan berjalan dan laba di tahan
                */
                if($norek <> $labablnjln  &&  $norek <> $labathjln)
                {
                    $cek = $this->ceksaldo($norek, $periode);                    
                    if(!empty($cek))
                    {  
                        $this->simpanupdate($tabel, 
                            array(
                               'debet'              => $debetbiasa,
                               'kredit'             => $kreditbiasa,
                               'memorial_debet'     => $debetmemorial,
                               'memorial_kredit'    => $kreditmemorial,
                               'penyesuaian_kredit' => $kreditpenyesuaian,
                               'penyesuaian_debet'  => $debetpenyesuaian), 
                            array(
                               'periode'           => $periode, 
                               'nomor'             => $norek)); 
                    }
                    else
                    {  
                        $this->simpaninsert($tabel, 
                            array(
                               'saldoawal'          => 0,
                               'debet'              => $debetbiasa,
                               'kredit'             => $kreditbiasa,
                               'memorial_debet'     => $debetmemorial,
                               'memorial_kredit'    => $kreditmemorial,
                               'penyesuaian_kredit' => $kreditpenyesuaian,
                               'penyesuaian_debet'  => $debetpenyesuaian,
                               'nomor'              => $norek,
                               'periode'            => $periode)
                        );  
                    }
                }  
            }
        }   
        $this->updatesaldoakhir($periode); 
        /* insert akun laba periode berjalan 
           insert akun laba di tahan
        */
        
        $drl  = $this->jurnal->getsaldorl2($periode); 
        foreach ($drl as $rl)
        {
            $rld = $rld + $rl['debet'];
            $rlk = $rlk + $rl['kredit']; 
        } 

        $laba = $this->ceksaldo($labablnjln, $periode);
        if(!empty($laba))
        {
            $this->simpanupdate($tabel, 
                        array(
                            'kredit'  => $rlk-$rld), 
                        array(
                            'nomor'   => $labablnjln,
                            'periode' => $periode)
                    );   
        }
        else
        { 
            $this->simpaninsert($tabel,  
                        array(
                           'nomor'              => $labablnjln,
                           'periode'            => $periode,
                           'debet'              => 0,
                           'kredit'             => $rlk-$rld, 
                           'memorial_debet'     => 0,
                           'memorial_kredit'    => 0,
                           'penyesuaian_debet'  => 0,
                           'penyesuaian_kredit' => 0)
                    );
        } 
        $this->updatesaldoakhir($periode);
        $this->db->trans_complete();
    }

    public function ceksaldo($nomor, $periode)
    {
        $this->db->select("*"); 
        $this->db->from("perkiraan_saldo"); 
        $this->db->where("nomor", $nomor);  
        $this->db->where("periode", $periode);  
        return $this->db->get()->row_array(); 
    } 
     
    public function newsaldo($datahitung)
    {
        $periode    = $datahitung['periode'];
        $oldperiode = $datahitung['oldperiode'];
        $newperiode = $datahitung['newperiode'];

        $labablnjln = 0;
        $labathjln  = 0; 
        $pos = $this->getpos(); 
        if(!empty($pos))
        {
            $labablnjln = $pos['lababulanberjalan'];
            $labathjln  = $pos['labatahunberjalan'];  
        }  
         
        $labajln        = 0;
        $labatahan      = 0;
        $saldolabatahan = 0;  
        $saldoawal      = 0; 
        $debetrl        = 0;
        $kreditrl       = 0;
        $rld            = 0;
        $rlk            = 0; 

        $tabel          = "perkiraan_saldo";  

        $this->db->trans_start(); 
        // hitung saldo akhir periode sebelumnya  
        $this->updatesaldoakhir($periode);  
        /* hapus saldo new periode */
        $this->deletedata($tabel, array("periode" => $newperiode));

        /* saldo akhir periode berjalan jadikan saldo awal new periode*/ 
        $this->db->select("*");   
        $this->db->from($tabel);   
        $this->db->where("periode", $oldperiode);  
        $psaldo = $this->db->get()->result_array(); 
        foreach ($psaldo as $key => $value) 
        {
            $saldoakhird = $value['saldoakhird'];
            $saldoakhirk = $value['saldoakhirk'];  
            $norek       = $value['nomor'];
            $posisidk    = $value['posisidk'];
            $posisinr    = $value['posisinr']; 
           
            if($norek==$labablnjln)
            {
                $labajln=$saldoakhirk;
            }
            if($norek==$labathjln)
            {
                $labatahan=$saldoakhirk;
            }
            /* Laba Periode Berjalan + Laba di Tahan*/
            $saldolabatahan = $labajln + $labatahan;
            if($posisinr=='NERACA')
            {
                if($norek==$labablnjln)
                {
                    $saldoawal = 0;
                }
                elseif($norek==$labathjln)
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
            /* isi akun awal dari periode berjalan*/            
            $this->simpaninsert($tabel, 
                        array(
                           'nomor'              => $norek,
                           'saldoawal'          => $saldoawal,
                           'debet'              => 0,
                           'kredit'             => 0,
                           'memorial_debet'     => 0,
                           'memorial_kredit'    => 0,
                           'penyesuaian_kredit' => 0,
                           'penyesuaian_debet'  => 0,
                           'periode'            => $newperiode)
                    ); 
        } 


        /* Update saldo awal laba Di tahan newperiode */
        $cklbtahan = $this->ceksaldo($labathjln, $newperiode);   
        if(!empty($cklbtahan))
        {  
            $this->simpanupdate($tabel, 
                            array('saldoawal'        => $saldolabatahan,
                                'debet'              => 0,
                                'kredit'             => 0,
                                'memorial_debet'     => 0,
                                'memorial_kredit'    => 0,
                                'penyesuaian_kredit' => 0,
                                'penyesuaian_debet'  => 0), 
                            array('periode'          => $newperiode, 
                                'nomor'              => $labathjln)
                            );
        }
        else
        { 
            $this->simpaninsert($tabel, 
                            array( 
                                'nomor'              => $labathjln,
                                'saldoawal'          => $saldolabatahan,
                                'debet'              => 0,
                                'kredit'             => 0,
                                'memorial_debet'     => 0,
                                'memorial_kredit'    => 0,
                                'penyesuaian_kredit' => 0,
                                'penyesuaian_debet'  => 0,
                                'periode'            => $newperiode 
                            ));
        }  

        /* cek saldo akun data jurnal umum periode berjalan 
           jika ada saldo di update 
           jika tidak ada insert saldo awal perkiraan_saldo
        */ 
        $jenisjurnal  = $this->jurnal->jenisjurnalsaldo($newperiode);
        $groupsaldo   = count($jenisjurnal['norek']);  
 
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
                    if($value['norek']==$norek)
                    {
                        $debetbiasa     = $debetbiasa + $value['debet'];
                        $kreditbiasa    = $kreditbiasa + $value['kredit']; 
                    } 
                } 
                // hitung total memorial per norek
                $debetmemorial  = 0;
                $kreditmemorial = 0;
                foreach ($memorial as $key => $value) 
                {
                    if($value['norek']==$norek)
                    {
                        $debetmemorial  = $debetmemorial + $value['debet'];
                        $kreditmemorial = $kreditmemorial + $value['kredit']; 
                    } 
                }  
                // hitung total penyesuaian per norek
                $debetpenyesuaian  = 0;
                $kreditpenyesuaian = 0;
                foreach ($penyesuaian as $key => $value) 
                {
                    if($value['norek']==$norek)
                    {
                        $debetpenyesuaian  = $debetpenyesuaian + $value['debet'];
                        $kreditpenyesuaian = $kreditpenyesuaian + $value['kredit']; 
                    } 
                } 
                /* insert / update perkiraan_saldo sesuai dengan 
                   saldo akun jurnal periode berjalan 
                   kecuali akun laba bulan berjalan dan laba di tahan
                */
                if($norek <> $labablnjln  &&  $norek <> $labathjln)
                { 
                    $cek = $this->ceksaldo($norek, $newperiode);                    
                    if(!empty($cek))
                    {   
                        $this->simpanupdate($tabel, 
                                    array(
                                           'debet'              => $debetbiasa,
                                           'kredit'             => $kreditbiasa,
                                           'memorial_debet'     => $debetmemorial,
                                           'memorial_kredit'    => $kreditmemorial,
                                           'penyesuaian_kredit' => $kreditpenyesuaian,
                                           'penyesuaian_debet'  => $debetpenyesuaian), 
                                    array(
                                           'periode'            => $newperiode,
                                           'nomor'              => $norek)
                                    ); 
                    }
                    else
                    { 
                        $this->simpaninsert($tabel, 
                                    array(
                                           'saldoawal'          => 0,
                                           'debet'              => $debetbiasa,
                                           'kredit'             => $kreditbiasa,
                                           'memorial_debet'     => $debetmemorial,
                                           'memorial_kredit'    => $kreditmemorial,
                                           'penyesuaian_kredit' => $kreditpenyesuaian,
                                           'penyesuaian_debet'  => $debetpenyesuaian,
                                           'nomor'              => $norek,
                                           'periode'            => $newperiode)
                                    );  
                    }
                } 
                
                /* laba periode berjalan */
                if($norek == $labablnjln  &&  $norek <> $labathjln)
                {
                    $rugilaba    = $this->jurnal->jenisjurnalsaldo($newperiode); 
                    $rugilaba    = array_filter($rugilaba['jenisjurnal'], function($value){
                                        return $value['posisineraca']=='R/L';
                                    });
                    // sum debet & kredit rugi laba 
                    foreach ($rugilaba as $key => $value) 
                    {
                        $debetrl    = $debetrl + $value['debet'];
                        $kreditrl   = $kreditrl + $value['kredit']; 
                    }

                    $cek = $this->ceksaldo($norek, $newperiode);                    
                    if(!empty($cek))
                    { 
                        // update 
                        $this->simpanupdate($tabel, 
                                array('debet'              => 0,
                                      'kredit'             => $kreditrl - $debetrl,
                                      'memorial_debet'     => 0,
                                      'memorial_kredit'    => 0,
                                      'penyesuaian_kredit' => 0,
                                      'penyesuaian_debet'  => 0),
                                array('periode'            => $newperiode, 
                                      'nomor'              => $norek)
                                    ); 
                    }
                    else
                    {  
                        $this->simpaninsert($tabel, 
                                array('debet'              => 0,
                                      'kredit'             => $kreditrl - $debetrl,
                                      'memorial_debet'     => 0,
                                      'memorial_kredit'    => 0,
                                      'penyesuaian_kredit' => 0,
                                      'penyesuaian_debet'  => 0,
                                      'saldoawal'          => 0,
                                      'nomor'              => $norek,
                                      'periode'            => $newperiode)
                                    );
                    } 
                }                
            }
        }   
        $this->updatesaldoakhir($newperiode);       
        /* insert akun laba periode berjalan 
           insert akun laba di tahan
        */
        $drl  = $this->jurnal->getsaldorl2($newperiode); 
        foreach ($drl as $rl)
        {
            $rld = $rld + $rl['debet'];
            $rlk = $rlk + $rl['kredit']; 
        }
        $laba = $this->ceksaldo($labablnjln, $newperiode);                    
        if(!empty($laba))
        {  
            $this->simpanupdate($tabel, 
                        array('kredit'   => $rlk-$rld), 
                        array('nomor'    => $labablnjln,
                              'periode'  => $newperiode)
                        );   
        }
        else
        { 
            $this->simpaninsert($tabel, 
                        array('nomor'              => $labablnjln,
                              'periode'            => $newperiode,
                              'debet'              => 0,
                              'kredit'             => $rlk-$rld, 
                              'memorial_debet'     => 0,
                              'memorial_kredit'    => 0,
                              'penyesuaian_debet'  => 0,
                              'penyesuaian_kredit' => 0)
                        );
        }   
        $this->updatesaldoakhir($newperiode);
        $this->db->trans_complete(); 
    } 

    public function updatesaldoakhir($periode)
    {
        $this->db->trans_start(); 
        $this->db->set('saldoakhirk', 
            'saldoawal - debet + kredit - memorial_debet + memorial_kredit - penyesuaian_debet + penyesuaian_kredit', FALSE);
        $this->db->set('saldoakhird', 
            'saldoawal + debet - kredit + memorial_debet - memorial_kredit + penyesuaian_debet - penyesuaian_kredit', FALSE);
        $this->db->where("nomor = nomor");  
        $this->db->where('periode', $periode);  
        $this->db->update("perkiraan_saldo");  
        $this->db->trans_complete(); 
    }
    
    public function hitungdiskon($rupiah, $diskon)
    {
        $rp      = $rupiah;
        if (empty($rp)) 
        {
            $rp = 0;
        }
        if (empty($diskon)) 
        {
            $diskon = 0;
        }
        if ($diskon <> 0) 
        {
            $dis     = explode("+", $diskon);
            foreach ($dis as $key => $value) 
            {
                $dscn    = $value;
                if ($dscn >= 100) 
                {
                    $rp     = $rp - $dscn;
                } 
                else 
                {
                    $rp     = $rp-($rp*$dscn/100);
                }
            }
            $rp     = $rupiah - $rp;
        } 
        else 
        {
            $rp     = 0;
        }
        return round($rp, 2); 
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

    public function cekdata($tabel, $where, $val)
    {
        $this->db->select("*"); 
        $this->db->from($tabel); 
        $this->db->where($where, $val);   
        $this->db->where('hapus', 0);   
        $g = $this->db->get()->result_array();
        return $g; 
    } 

    public function cektabel($tabel, $where, $val)
    {
        $this->db->select("*"); 
        $this->db->from($tabel); 
        $this->db->where($where, $val);   
        $this->db->where('hapus', 0);   
        $g = $this->db->get()->row_array();
        return $g; 
    } 

    public function cektabelmaster($tabel, $id)
    {
        $this->db->select("*"); 
        $this->db->from($tabel); 
        $this->db->where("hapus", 0);  
        $this->db->where("id", $id);         
        $g = $this->db->get()->row_array();
        return $g; 
    }

    public function cektabeluser($tabel, $field, $value, $user)
    {
        $g = 0;  
        $this->db->select('id'); 
        $this->db->from($tabel); 
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($field, $value);         
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function cektabelduser($tabel, $fieldh, $valueh, $fieldd, $valued, $user)
    {
        $g = 0;  
        $this->db->select('id'); 
        $this->db->from($tabel); 
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($fieldh, $valueh);         
        $this->db->where($fieldd, $valued); 
        $g = $this->db->get()->row_array();
        return $g;
    } 

    public function getidtabeluser($tabel, $field, $value, $user)
    {
        $g = 0;  
        $this->db->select('*'); 
        $this->db->from($tabel); 
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

    public function hapusbank($id)
    {
        $g     = 0; 
        $query = " SELECT kodebank AS kode FROM trankask_h 
                   WHERE kodebank = $id and hapus = 0
                   UNION ALL
                   SELECT kodebank AS kode FROM trankasm_h 
                   WHERE kodebank = $id and hapus = 0 ";
        $g     = $this->db->query($query)->num_rows();
        return $g;
    }

    public function hapusperkiraan($id)
    {
        $g     = 0;
        $query = " SELECT nomorheader AS kode FROM perkiraan WHERE nomorheader = $id
                   UNION ALL
                   SELECT norek AS kode FROM bank WHERE norek = $id
                   UNION ALL
                   SELECT norek AS kode FROM trankask_d WHERE norek = $id  
                   UNION ALL
                   SELECT norek AS kode FROM trankasm_d WHERE norek = $id  
                   UNION ALL
                   SELECT norek AS kode FROM jurnal_d WHERE norek = $id    
                    ";
        $g     = $this->db->query($query)->num_rows(); 
        return $g;
    }

    public function getpos()
    {    
        $this->db->select("*");   
        $this->db->from("pos"); 
        $this->db->where('hapus', 0); 
        return $this->db->get()->row_array();    
    }

    public function clean($string)
    {
        $clean = '';
        if (!empty($string)) {
            $clean = str_replace('removed', '', $this->security->sanitize_filename($string));
        }
        return $clean;
    }

    public function clean2($string)
    {
        $clean = '';
        if (!empty($string)) {
            $string = str_replace('<script>', '', $string);
            $string = str_replace('</script>', '', $string);
            $string = str_replace('<style>', '', $string);
            $string = str_replace('</style>', '', $string);
            $clean  = $string;
        }
        return $clean;
    }

    public function secclean($post)
    {
        $secclean   = trim($this->input->post($post, TRUE), " ");
        $secclean   = str_replace('removed', '', $this->security->sanitize_filename($secclean));
        return $secclean;
    }

    public function secclean2($post)
    {
        $secclean = trim($this->input->post($post, TRUE), " ");
        $secclean = str_replace('<script>', '', $secclean);
        $secclean = str_replace('</script>', '', $secclean);
        $secclean = str_replace('<style>', '', $secclean);
        $secclean = str_replace('</style>', '', $secclean);
        return $secclean;
    }

    public function hapuscache($cont, $func)
    {
        $this->db->cache_delete($cont, $func);
    }

    public function caridata($dpost, $text)
    {
        $x = 0;
        $data = stripos($text, $dpost);
        if (strlen($data) > 0) {
            return $x = 1;
        }
    }

    public function get_oldperiode($periode)
    {
        $thn        = date("Y",strtotime($periode));  
        $bln        = date("m",strtotime($periode));
        $bln        = ((int)$bln);
        $oldperiode = null;
        if($bln<=10){
            if($bln==1){
                $bln        = 12; 
                $thn        = ((int)$thn)-1;
                $oldperiode = $thn. $bln;  
            }else{
                $bln        = $bln-1; 
                $oldperiode = $thn .'0'. $bln;      
            }
        }else{
            $bln        = $bln-1; 
            $oldperiode = $thn . $bln;    
        }
        return $oldperiode;
    }

    public function get_newperiode($periode)
    { 
        $thn        = date("Y",strtotime($periode));  
        $bln        = date("m",strtotime($periode));
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

    public function nomorawal($Ymd, $tabel, $field)
    {
        // $this->db->select("CONCAT($Ymd, 
        //     RIGHT(CONCAT('000000000000', 
        //     IFNULL(MAX(SUBSTR($field,9,8)),'000000000000')+1),8)) AS NOMOR ") ;
        $this->db->select("CONCAT($Ymd, 
            RIGHT(CONCAT('000000000000', 
            IFNULL(MAX(SUBSTR($field,14,8)+1),'000000000001')),8)) AS NOMOR ") ;
        $this->db->from($tabel); 
        $this->db->where("DATE_FORMAT(tgl,'%Y%m%d')", $Ymd); 
        $this->db->where("hapus", 0); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function nomorbaru($Ymd, $tabel, $field)
    {
        // $this->db->select("CONCAT($Ymd, 
        //     RIGHT(CONCAT('000000000000', 
        //     IFNULL(MAX(SUBSTR($field,9,8)),'000000000000')+1),8)) AS NOMOR ") ;
        $this->db->select("CONCAT($Ymd, 
            RIGHT(CONCAT('000000000000', 
            IFNULL(MAX(SUBSTR($field,9,8)+1),'000000000001')),8)) AS NOMOR ") ;
        $this->db->from($tabel); 
        $this->db->where("DATE_FORMAT(tgl,'%Y%m%d')", $Ymd);  
        $this->db->where("hapus", 0); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function kodebaru($src, $tabel, $field)
    {
        $this->db->select("CONCAT(SUBSTR('$src',1,1), 
                    RIGHT(CONCAT('00000', 
                    IFNULL(MAX(SUBSTR($field,2,5)+1),'00001')),5)) AS NOMOR") ;
        $this->db->from($tabel); 
        $this->db->where("SUBSTR($field,1,1) = SUBSTR('$src',1,1)");  
        $this->db->where("hapus", 0); 
        $query = $this->db->get();
        return $query->row_array();
    } 
    
    public function cekhakakses($menu, $jenis, $field, $user)
    {
        $res = []; 
        $this->db->select($field);
        $this->db->from("hakakses as h"); 
        $this->db->where("h.nama",$menu);
        $this->db->where("h.jenis",$jenis);
        $this->db->where("h.username",$user);  
        $this->db->limit(1); 
        return $this->db->get()->row_array();
    } 

    public function cekhakakseslaporan($jenis, $field, $user)
    {
        $res = []; 
        $this->db->select($field);
        $this->db->from("hakakses as h");  
        $this->db->where("h.jenis",$jenis);
        $this->db->where("h.username",$user);  
        $this->db->where("h.lihat",1);   
        $query = $this->db->get();
        $res   = $query->result_array();
        return $res;
    } 

    public function cektoken($user, $token, $idlog)
    { 
        date_default_timezone_set('Asia/Jakarta');
        $this->hapuslogexp();
        $this->updatelog($idlog); 
        $res = 100;
        $exp = date('YmdHis');
        if(!empty($token))
        {
            $this->db->select("l.*");
            $this->db->from("loglogin as l"); 
            $this->db->join("users as u", "l.user = u.id", "Left"); 
            $this->db->where("l.user", $user);
            $this->db->where("l.token", $token);
            $this->db->where("l.expdperiode > '$exp' "); 
            $this->db->where("u.hapus", 0);
            $this->db->limit(1); 
            $q = $this->db->get();
            $this->db->last_query();
            if($q->num_rows()==1)
            {
                $res = 101;
            }       
        } 
        return $res ;
    }

    public function cekunik($tabel, $field, $value)
    {
        $g = 0;
        $this->db->select($field);
        $this->db->from($tabel);
        $this->db->where($field, $value); 
        $this->db->where("hapus", 0);
        $g = $this->db->get()->num_rows();  
        return $g; 
    }

    public function cekeditunik($id, $field, $tabel, $value)
    {
        $c = 0;
        $n = 0;
        $o = ''; 
        // kode lama 
        $this->db->select($field);
        $this->db->from($tabel); 
        $this->db->where('id', $id);
        $this->db->where("hapus", 0);
        $g = $this->db->get()->row_array();
        if(!empty($g))
        {
            $o = $g[$field]; 
        }
        if($o != $value) // jika kode lama tidak sesuai dengan kode baru, cek kode baru terpakai??
        {
            $n = $this->cekunik($tabel, $field, $value);
            return $n;
        } 
        return $n;
    }

    public function get_startdate()
    { 
        $thn        = date("Y");  
        $bln        = date("m");
        $day        = "01";
        $start      = $thn.$bln.$day;
        return date("Y-m-d",strtotime($start));  
    }

    public function getroot()
    {  
        $n = 0;
        $this->db->select("*"); 
        $this->db->from("setting");  
        $query = $this->db->get()->row_array();
        if(!empty($query))
        {
            $n = $query['root'];    
        } 
        return $n;
    } 
     
    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function simpanlog($grup, $api)
    {
        return $this->simpaninsert('log', [
                          'ip'      => $this->input->ip_address(),
                          'grup'    => $grup, 
                          'api'     => $api,
                          'desk'    => json_encode($_POST),
                          'it'      => date("Y-m-d H:i:s"), 
                          'dperiode'=> date("Ymd")
                          ]); 
    }


    public function searchdata($data, $field, $val, $kondisi)
    {
        $out = [];
        if(!empty($data))
        {
            switch ($kondisi) 
            {
                case "==": 
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] == $val;
                    });  
                    break;
                case "<>":
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] <> $val;
                    });  
                    break;
                case ">":
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] > $val;
                    });  
                    break;
                case "<":
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] < $val;
                    });  
                    break;
                case ">=":
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] >= $val;
                    });  
                    break;
                case "<=":
                    $out  = array_filter($data, function($value) use ($val, $field){ 
                    return $value[$field] <= $val;
                    });  
                    break; 
            }
        } 
       return $out;
    }

    public function cekrequest($token)
    { 
        $this->updatereqfaktur();
        $this->db->select("*");
        $this->db->from("cetakfaktur");  
        $this->db->where("token", $token);  
        $this->db->where("status", 0);
        $this->db->where("exp > now()");  
        $query = $this->db->get();
        return $query->row_array(); 
    }

    public function cekrequestlapkeu($token)
    { 
        $this->updatereqfaktur();
        $this->db->select("*");
        $this->db->from("cetaklapkeuangan");  
        $this->db->where("token", $token);  
        $this->db->where("status", 0);
        $this->db->where("exp > now()");  
        $query = $this->db->get();
        return $query->row_array(); 
    } 

    public function updatereqfaktur()
    { 
        $query    = "update cetakfaktur set status = 1   
                     where exp <= now() ";
        $data     = $this->db->query($query);
        return $data; 
    } 
 
    public function gettabel($periode, $tabel)
    { 
        $this->db->select("*");
        $this->db->from($tabel); 
        $this->db->where("periode", $periode);  
        $this->db->where("hapus", 0);
        $query = $this->db->get();
        return $query->result_array();
    }




}
