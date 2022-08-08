<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_pengguna');
        $this->load->model('model_bank');
        $this->load->model('model_perkiraan');
    }

    public function cariperkiraan_post()
    {
        $cri  = $this->post('param');
        $pjg  = strlen($cri);
        $list = $this->model_api->daftarperkiraan(null, null);
        $data = [];
        foreach ($list as $key => $value) {
            if ($pjg >= MIN_CARI) {
                if (
                    $this->model_api->caridata($cri, $value['nomor']) <> false ||
                    $this->model_api->caridata($cri, $value['nama'])  <> false ||
                    $this->model_api->caridata($cri, $value['levelno']) <> false ||
                    $this->model_api->caridata($cri, $value['nomorheader']) <> false ||
                    $this->model_api->caridata($cri, $value['posisidk'])  <> false ||
                    $this->model_api->caridata($cri, $value['posisinr']) <> false ||
                    $this->model_api->caridata($cri, $value['jenisjurnal']) <> false ||
                    $this->model_api->caridata($cri, $value['posisineraca']) <> false ||
                    $this->model_api->caridata($cri, $value['jenislevel']) <> false
                ) {
                    $data[] = [
                        'id'           => $value['id'],
                        'nomor'        => $value['nomor'],
                        'nama'         => $value['nama'],
                        'levelno'      => $value['levelno'],
                        'nomorheader'  => $value['nomorheader'],
                        'posisidk'     => $value['posisidk'],
                        'posisinr'     => $value['posisinr'],
                        'jenisjurnal'  => $value['jenisjurnal'],
                        'posisineraca' => $value['posisineraca'],
                        'jenislevel'   => $value['jenislevel']
                    ];
                }
            } else {
                $data[] = [
                    'id'           => $value['id'],
                    'nomor'        => $value['nomor'],
                    'nama'         => $value['nama'],
                    'levelno'      => $value['levelno'],
                    'nomorheader'  => $value['nomorheader'],
                    'posisidk'     => $value['posisidk'],
                    'posisinr'     => $value['posisinr'],
                    'jenisjurnal'  => $value['jenisjurnal'],
                    'posisineraca' => $value['posisineraca'],
                    'jenislevel'   => $value['jenislevel']
                ];
            }
        }
        $this->response($data, 200);
    }

    public function test_post()
    {
        $where = $this->post('param');
        $data  = $this->model_api->testing($where);
        $this->response($data, 200);
    }

    public function datamaster_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   

        if(empty($user) || empty($token) || empty($idlog))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }
       
        $data   = [];   
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            $this->response([
                'status'        => 102,
                'datauser'      => $this->datauser(),
                'databank'      => $this->databank(),
                'dataakun'      => $this->dataakun(),
                'dataakun1'     => $this->dataakun1(),
                'dataakun2'     => $this->dataakun2(),
                'dataakun3'     => $this->dataakun3(),
                'dataakun4'     => $this->dataakun4(),
            ], 200); 
            
        } 
        $this->response([
                'status'        => 100,
                'datauser'      => [],
                'databank'      => [],
                'dataakun'      => [],
                'dataakun1'     => [],
                'dataakun2'     => [],
                'dataakun3'     => [],
                'dataakun4'     => [],
            ], 200); 
    }


    private function datauser()
    { 
        $out    = [];
        $list   = $this->model_pengguna->daftarpengguna('');    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['nama']
                    ];
        }  
        return $out;
    } 
     
    private function databank()
    {
        $out    = [];
        $list   = $this->model_bank->getbank();    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out;
    } 

    private function dataakun()
    {
        $out    = [];
        $list   = $this->model_perkiraan->getakunpos(0);    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out; 
    }

    private function dataakun1()
    {
        $out    = [];
        $list   = $this->model_perkiraan->getakunpos(1);    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out; 
    }

    private function dataakun2()
    {
        $out    = [];
        $list   = $this->model_perkiraan->getakunpos(2);    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out; 
    }

    private function dataakun3()
    { 
        $out    = [];
        $list   = $this->model_perkiraan->getakunpos(3);    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out;
        
    }

    private function dataakun4()
    {     
        $out    = [];    
        $list   = $this->model_perkiraan->getakunpos(4);    
        foreach ($list as $key => $value) 
        {   
            $out[] = [ 
                        'nomor' => $value['id'],
                        'desk'  => $value['search']
                    ];
        }  
        return $out; 
    } 
    
}
