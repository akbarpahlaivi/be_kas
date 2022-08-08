<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Login extends RestController
{

    function __construct()
    {
        parent::__construct(); 
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('mylib');
        date_default_timezone_set('Asia/Jakarta');
    } 

    public function login_post()
    { 
        $this->mylib->hapuslogexp();
        $prd   = date("Y-m-d");     
        $user  = $this->post('telp');
        $pass  = $this->post('pass'); 
        $ck    = $this->model_api->ceklogin($user, $pass);   

        if (!empty($ck)) 
        {
            // set log
            $iduser = $ck['id'];
            $otp    = date("dHis"); 
            $otp    = rand('1000',$otp);
            $otp    = $otp .'0000';
            $otp    = substr($otp, 0,8);   
            $idlog  = $this->setlog($iduser, $otp, $ck);  

            $this->response([
                'status'        => 102,
                'message'       => "Login Berhasil",
                'token'         => $otp,
                'userkey'       => $iduser,
                'idlog'         => $idlog,
                'startdate'     => $this->mylib->get_startdate(),
                'newdate'       => date("Y-m-d"),
                'periode'       => date("Ym"),
                'developed'     => "KAS"
            ], 200);
        } 
        $this->response([
                'status'  => 100,
                'message' => "Login Gagal, Username / Password Salah"
            ], 200); 
    }

    public function logout_post()
    {
        $token  = $this->post('token');
        $idlog  = $this->post('idlog'); 
        $tabel  = "loglogin";  
        $kunci  = array('id'     => $idlog, 'token' => $token);         
        $data   = array('status' => 'off');  
        $res    = $this->mylib->simpanupdate($tabel,$data, $kunci); 
        $this->response('Logout Sukses', 200);
    }

    public function getmenu_post()
    {
        /*
            dashboard mdi-home-city
            master mdi-folder-multiple-outline
            transaksi mdi-chart-areaspline
            laporan mdi-fax
            setting mdi-hammer-wrench
        */
        $user  = $this->post('username');
        $pass  = $this->post('token');
        $idlog = $this->post('idlog');
        $data  = [];
        $get   = $this->mylib->mainmenu($user, $pass, 'header');
        $gicon = '';
        if(!empty($get))
        {
            foreach ($get as $key => $value) {
                $itemd   = [];
                $jenis   = $value['kategori'];
                $grup    = $value['grup'];
                $gicon   = $this->geticon($jenis);
                if($grup <> '300')
                {
                    $item    = $this->mylib->mainmenu($user, $pass, $jenis);  
                    foreach ($item as $key => $vdet) 
                    { 
                        $itemd [] = [
                                        'title'    => $vdet['nama'],
                                        'kode'     => $vdet['kode']
                                    ];
                    } 
                }
                else
                {
                    $item    = $this->mylib->menulaporan($user, $pass, $jenis);
                    foreach ($item as $key => $vdet) 
                    { 
                        $itemd [] = [
                                        'title'    => str_replace("Laporan","",$vdet['jenis']),
                                        'kode'     => $vdet['kode']
                                    ];
                    } 
                }
                 
                $data [] = [
                                'action'   => $gicon,
                                'active'   => false,
                                'items'    => $itemd,
                                'title'    => $jenis
                            ];   
            }
            $this->response(array('status'  => 102, 'data'  => $data),200);  
        } 
        $this->response(array('status'  => 104, 'data'  => []),200);
    }

    public function getitemmenu($iduser, $token, $idlog)
    {
        /*
            dashboard mdi-home-city
            master mdi-folder-multiple-outline
            transaksi mdi-chart-areaspline
            laporan mdi-fax
            setting mdi-hammer-wrench
        */
        $data  = [];
        $get   = $this->mylib->mainmenu($iduser, $token, 'header');
        $gicon = '';
        if(!empty($get))
        {
            foreach ($get as $key => $value) {
                $itemd   = [];
                $jenis   = $value['kategori'];
                $grup    = $value['grup'];
                $gicon   = $this->geticon($jenis);
                if($grup <> '300')
                {
                    $item    = $this->mylib->mainmenu($iduser, $token, $jenis);  
                    foreach ($item as $key => $vdet) 
                    { 
                        $itemd [] = [
                                        'title'    => $vdet['nama'],
                                        'kode'     => $vdet['kode']
                                    ];
                    } 
                }
                else
                {
                    $item    = $this->mylib->menulaporan($iduser, $token, $jenis);
                    foreach ($item as $key => $vdet) 
                    { 
                        $itemd [] = [
                                        'title'    => str_replace("Laporan ","L. ",$vdet['jenis']),
                                        'kode'     => $vdet['kode']
                                    ];
                    } 
                }
                 
                $data [] = [
                                'action'   => $gicon,
                                'active'   => false,
                                'items'    => $itemd,
                                'title'    => $jenis
                            ];   
            }
            
        } 
        return $data;
    } 

    private function geticon($jenis)
    { 
        switch ($jenis) {
            case 'Master':
            $icon = 'mdi-folder-multiple-outline';
            break;
            case 'Transaksi':
            $icon = 'mdi-chart-areaspline';
            break;
            case 'Laporan':
            $icon = 'mdi-fax';
            break; 
            default:
            $icon = 'mdi-hammer-wrench';
        } 
        return $icon;
    }

    private function setlog($pengguna, $otp, $data){
        date_default_timezone_set('Asia/Jakarta');
        $exp    = date('YmdHis', strtotime(date('YmdHis'). ' + 2 hours'));  
        $tabel  = "loglogin";
        $data   = array('user'        => $pengguna,
                        'ip'          => $this->input->ip_address(), 
                        'it'          => date('Y-m-d H:i:s'), 
                        'expdperiode' => $exp,
                        'token'       => $otp,
                        'data'        => json_encode($data)
                        ); 
        $data   = $this->mylib->simpaninsert($tabel,$data);
        $id     = $this->db->insert_id();
        return $id;
    }

    
    
}
