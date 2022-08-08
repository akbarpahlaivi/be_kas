<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
 
class Laporankeuangan extends RestController
{ 
    protected $menu= "Laporan Keuangan";

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_jurnalumum');       
        $this->load->model('jurnal');        
    } 

    public function request_post()
    {
        $idlog   = $this->post('idlog');  
        $user    = $this->post('user');
        $token   = $this->post('token');   
        $jenis   = $this->post('jenis'); 
        $aksi    = $this->post('aksi');
        $periode = $this->post('periode'); 
        $pfilter1= $this->post('pfilter1'); 
        $pfilter2= $this->post('pfilter2'); 
        $pfilter3= $this->post('pfilter3'); 
        $pfilter4= $this->post('pfilter4');  
        $lihat   = 0;

        if(empty($user) || empty($token) || empty($idlog) || empty($jenis) || empty($periode))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
         
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($jenis, 'Laporan Keuangan', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat']; 
            }

            if($lihat == 1)
            {
                if($pfilter1 == 'SEMUA')
                {
                    $pfilter1 = 'YES';
                }
                else
                {
                    $pfilter1 = 'TIDAK';
                }

                if($pfilter2 == 'Pilih Field')
                {
                    $pfilter2 = '';
                }
                $token = NOINV; 
                $this->mylib->simpaninsert('cetaklapkeuangan', 
                                     array('periode'   => $periode, 
                                           'it'        => date("Y-m-d H:i:s"), 
                                           'exp'       => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"). ' + 1 days')),
                                           'token'     => $token,
                                           'jenis'     => $jenis,
                                           'pfilter1'  => $pfilter1,
                                           'pfilter2'  => $pfilter2,
                                           'pfilter3'  => $pfilter3,
                                           'pfilter4'  => $pfilter4, 
                                           'aksi'      => $aksi
                                        )); 
                $this->response([
                    'status'    => 102,
                    'data'      => $token
                ], 200); 
            }
            $this->response([
                'status'  => 101,
                'message' => "Hak Akses Dibatasi"
            ], 200);
        } 
        $this->response([
                'status'  => 100,
                'message' => "User Tidak Ditemukan"
            ], 200); 
    } 



    
    
   

    

     
    

    


     


    

     

    

    
}
