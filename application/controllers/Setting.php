<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Setting extends RestController
{
    protected $menu= "Setting";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_setting'); 
        date_default_timezone_set('Asia/Jakarta');
    }

    public function daftarsetting_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   
        $lihat  = 0;
        $lihata = 0;        
        
        if(empty($user) || empty($token) || empty($idlog))
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
            $cek    = $this->mylib->cekhakakses($this->menu, 'Setting', 'lihat', $user);  
            if(!empty($cek))
            {
                $lihat  = $cek['lihat']; 
            }

            if($lihat == 1)
            {  
                $list   = $this->model_setting->datasetting();   
                if(!empty($list))
                {
                    $data[] = [ 
                                'id'        => $list['id'], 
                                'nama'      => $list['nama'],
                                'alamat'    => $list['alamat'],
                                'kota'      => $list['kota'], 
                                'telp'      => $list['telp'],
                                'provinsi'  => $list['provinsi'],  
                                'edit'      => 0
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

    public function simpandata_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   

        $jenis  = $this->post('jenis');  
        $id     = $this->post('id'); //  new set id = -1 
 
        $nama   = $this->post('nama');
        $kota   = $this->post('kota');    
        $alamat = $this->post('alamat');  
        $telp   = $this->post('telp');   
        $prov   = $this->post('provinsi');      


        if(empty($user) || empty($token) || empty($idlog)
        || empty($jenis) || empty($id) || empty($nama) || empty($kota) || empty($alamat)
        || empty($telp || empty($provinsi))
         )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $this->response([
                'status'  => 103,
                'message' => "Demo Only"
            ], 200);

        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Setting', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if ($jenis == 'editdata' && $id > 0) 
            {
                // update data
                if($lihat == 1)
                {  
                    $data   = array('nama'         => $nama,
                                    'alamat'       => $alamat, 
                                    'kota'         => $kota,  
                                    'telp'         => $telp, 
                                    'provinsi'     => $prov,
                                    'et'           => date("Y-m-d H:i:s") 
                                    );   
                    $res = $this->mylib->simpanupdate('setting', $data, array('id' => $id));  
                    $this->response([
                                    'status'   => 102,
                                    'data'     => $data, 
                                    'message'  => 'Sukses Mengupdate Data',
                                    'simpan'   => 'Sukses'
                                    ], 200);   
                }  
                $this->response([
                    'status'  => 101,
                    'message' => 'Hak Akses Dibatasi',
                    'simpan'  => 'Gagal'
                ], 200); 
            } 
            $this->response([
                'status'  => 101,
                'message' => 'Gagal Merubah data',
                'simpan'  => 'Gagal'
            ], 200);
        }  

        $this->response([
                'status'  => 100,
                'message' => 'User Tidak Ditemukan',
                'simpan'  => 'Gagal'
            ], 200);
    }
    
    
    
}
