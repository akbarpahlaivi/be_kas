<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
 
class Jurnalumum extends RestController
{ 
    protected $menu= "Jurnal Umum";

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_jurnalumum');       
        $this->load->model('jurnal');       
        $this->load->model('p_saldo');   
    }

    public function daftarjurnalumum_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   
        $lihat  = 0;  
         
        $dari   = date("Ymd",strtotime($this->post('dari')));
        $sampai = date("Ymd",strtotime($this->post('sampai'))); 
        $srnobuk= $this->post('srnobukti');   
        $srtabel= $this->post('srtabel');   
        
        if(empty($user) || empty($token) || empty($idlog) ||
        empty($dari) || empty($sampai))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
        
         
        
        $out    = []; 
        $data   = [];   
       
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $no     = 1;
                $list   = $this->model_jurnalumum->datajurnalumum($dari, $sampai, $srnobuk, $srtabel);    
                
                foreach ($list as $key => $value) 
                { 
                    $data[] = [
                                'no'          => $no++,
                                'id'          => $value['id'],
                                'nobukti'     => $value['nobukti'],
                                'tgl'         => $value['tgl'], 
                                'tabel'       => $value['tabel'],  
                                'totaldebet'  => $value['totaldebet'],
                                'totalkredit' => $value['totalkredit'],
                                'username'    => $value['username'],
                                'datad'       => $this->model_jurnalumum->getdatad($value['id'])
                            ];
                } 
                 
                $this->response([
                    'status'    => 102,
                    'data'      => $data
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

    public function hapusjurnal_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $id     = $this->post('idjurnal');  

        if(empty($user) || empty($token) || empty($idlog) || empty($id) )
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
            $hapus  = 0;
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];  
            }

            if($lihat == 1)
            { 
                $this->mylib->simpanupdate('jurnal_h', 
                                    array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                    array('id'    => $id)); 
                $this->response([
                    'status'    => 102,
                    'message'   => "Sukses Menghapus data"  
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
