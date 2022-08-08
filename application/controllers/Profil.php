<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Profil extends RestController
{
    protected $menu= "Setting Akun";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');    
        date_default_timezone_set('Asia/Jakarta');
    } 

    public function simpandata_post()
    {
        $idlog              = $this->post('idlog');  
        $user               = $this->post('user');
        $token              = $this->post('token');  
        $branch             = $this->post('branch');  

        $jenis              = $this->post('jenis');  
        $id                 = $this->post('id'); //  new set id = -1 
 
        $nama               = $this->post('nama');
        $passlama           = $this->post('passlama');
        $passbaru           = $this->post('passbaru');  

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) 
        || empty($jenis) || empty($id) || empty($nama) || empty($passlama) || empty($passbaru) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            $passlama = md5(PASSKEY . $passlama) ;  
            $passbaru = md5(PASSKEY . $passbaru) ;  
            if ($jenis == 'editdata' && $id > 0) 
            {
                $data   = array('password' => $passbaru);   
                $cd     = $this->mylib->cekusercabang($nama, $passlama, $branch);  
                
                if($cd > 0)
                {
                    $res = $this->mylib->simpanupdate('users', $data, array('id' => $id, 'cabang' => $branch));  
                    $this->response([
                                    'status'   => 102,
                                    'message'  => 'Sukses Mengupdate Data',
                                    'simpan'   => 'Sukses'
                                    ], 200);   
                }
                $this->response([
                        'status'    => 101,
                        'message'   => "Gagal Mengupdate data, Data User Salah"  
                    ], 200);  
            } 
            $this->response([
                'status'  => 101,
                'message' => 'Gagal Mengupdate data',
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
