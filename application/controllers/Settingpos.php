<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Settingpos extends RestController
{
    protected $menu= "Setting Akun";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_settingpos');
        $this->load->model('model_perkiraan');        
        date_default_timezone_set('Asia/Jakarta');
    } 

    public function datapos_post()
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
                $list   = $this->model_settingpos->datasetting();
                if(!empty($list))
                {
                    $data = [ 
                                'id'                    => $list['id'], 
                                'jualtunai'             => $list['jualtunai'],
                                'jualkredit'            => $list['jualkredit'],
                                'piutangdagang'         => $list['piutangdagang'], 
                                'hutangdagang'          => $list['hutangdagang'],
                                'returjual'             => $list['returjual'], 
                                'returbeli'             => $list['returbeli'],
                                'persediaan'            => $list['persediaan'],
                                'hpp'                   => $list['hpp'],
                                'ppnmasukan'            => $list['ppnmasukan'],
                                'ppnkeluaran'           => $list['ppnkeluaran'],
                                'labatahunlalu'         => $list['labatahunlalu'],
                                'labatahunberjalan'     => $list['labatahunberjalan'],
                                'lababulanberjalan'     => $list['lababulanberjalan'],
                                'pembelian'             => $list['pembelian'],
                                'beli_kas'              => $list['beli_kas'],
                                'beli_discount'         => $list['beli_discount'],
                                'jual_kas'              => $list['jual_kas'],
                                'jual_discount'         => $list['jual_discount'],
                                'rbeli_laba'            => $list['rbeli_laba'],
                                'rbeli_rugi'            => $list['rbeli_rugi'],
                                'opname_fisik_kurang'   => $list['opname_fisik_kurang'],
                                'opname_fisik_lebih'    => $list['opname_fisik_lebih'],
                                'persediaankonsinyasi'  => $list['persediaankonsinyasi'],
                                'bebanbeli'             => $list['bebanbeli'],
                                'pemakaian'             => $list['pemakaian'],
                                'jual_lain'             => $list['jual_lain'], 
                                'edit'                  => 1
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
        $idlog              = $this->post('idlog');  
        $user               = $this->post('user');
        $token              = $this->post('token');   

        $jenis              = $this->post('jenis');  
        $id                 = $this->post('id'); //  new set id = -1 
 
        $belikas            = $this->post('belikas');
        $belihutang         = $this->post('belihutang');
        $beli               = $this->post('beli');
        $belidiskon         = $this->post('belidiskon');
        $belippn            = $this->post('belippn');
        $beliretur          = $this->post('beliretur');
        $belilabaretur      = $this->post('belilabaretur');
        $belirugiretur      = $this->post('belirugiretur');

        $jualkas            = $this->post('jualkas');
        $jualpiutang        = $this->post('jualpiutang');
        $jualtunai          = $this->post('jualtunai');
        $jualkredit         = $this->post('jualkredit');
        $jualdiskon         = $this->post('jualdiskon');
        $jualppn            = $this->post('jualppn');
        $jualpersediaan     = $this->post('jualpersediaan');
        $jualhpp            = $this->post('jualhpp');
        $jualstokkurang     = $this->post('jualstokkurang');
        $jualstoklebih      = $this->post('jualstoklebih');
        $jualpakai          = $this->post('jualpakai');
        $jualpendapatan     = $this->post('jualpendapatan');
        $jualretur          = $this->post('jualretur');

        $modalthlalu        = $this->post('modalthlalu');
        $modalthjalan       = $this->post('modalthjalan');
        $modalperiodejalan  = $this->post('modalperiodejalan');

        if(empty($user) || empty($token) || empty($idlog)  
        || empty($jenis) || empty($id) )
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

            // cek data 
            $akun        = $this->model_perkiraan->getakunpos(0);
            $dbelikas    = $this->mylib->searchdata($akun, 'id', $belikas, '==');
            if(empty($dbelikas))
            {
                $belikas = 0;
            }

            $dbelihutang = $this->mylib->searchdata($akun, 'id', $belihutang, '==');
            if(empty($dbelihutang))
            {
                $belihutang = 0;
            }

            $dbeli       = $this->mylib->searchdata($akun, 'id', $beli, '==');
            if(empty($dbeli))
            {
                $beli = 0;
            }

            $dbelidiskon = $this->mylib->searchdata($akun, 'id', $belidiskon, '==');
            if(empty($dbelidiskon))
            {
                $belidiskon = 0;
            }

            $dbelippn    = $this->mylib->searchdata($akun, 'id', $belippn, '==');
            if(empty($dbelippn))
            {
                $belippn = 0;
            }

            $dbeliretur = $this->mylib->searchdata($akun, 'id', $beliretur, '==');
            if(empty($dbeliretur))
            {
                $beliretur = 0;
            }

            $dbelilabaretur = $this->mylib->searchdata($akun, 'id', $belilabaretur, '==');
            if(empty($dbelilabaretur))
            {
                $belilabaretur = 0;
            }

            $dbelirugiretur = $this->mylib->searchdata($akun, 'id', $belirugiretur, '==');
            if(empty($dbelirugiretur))
            {
                $belirugiretur = 0;
            }

            $djualkas = $this->mylib->searchdata($akun, 'id', $jualkas, '==');
            if(empty($djualkas))
            {
                $jualkas = 0;
            }

            $djualpiutang = $this->mylib->searchdata($akun, 'id', $jualpiutang, '==');
            if(empty($djualpiutang))
            {
                $jualpiutang = 0;
            }

            $djualtunai = $this->mylib->searchdata($akun, 'id', $jualtunai, '==');
            if(empty($djualtunai))
            {
                $jualtunai = 0;
            }

            $djualkredit = $this->mylib->searchdata($akun, 'id', $jualkredit, '==');
            if(empty($djualkredit))
            {
                $jualkredit = 0;
            }

            $djualdiskon = $this->mylib->searchdata($akun, 'id', $jualdiskon, '==');
            if(empty($djualdiskon))
            {
                $jualdiskon = 0;
            }

            $djualppn = $this->mylib->searchdata($akun, 'id', $jualppn, '==');
            if(empty($djualppn))
            {
                $jualppn = 0;
            }

            $djualpersediaan = $this->mylib->searchdata($akun, 'id', $jualpersediaan, '==');
            if(empty($djualpersediaan))
            {
                $jualpersediaan = 0;
            }

            $djualhpp = $this->mylib->searchdata($akun, 'id', $jualhpp, '==');
            if(empty($djualhpp))
            {
                $jualhpp = 0;
            }

            $djualstokkurang = $this->mylib->searchdata($akun, 'id', $jualstokkurang, '==');
            if(empty($djualstokkurang))
            {
                $jualstokkurang = 0;
            }

            $djualstoklebih = $this->mylib->searchdata($akun, 'id', $jualstoklebih, '==');
            if(empty($djualstoklebih))
            {
                $jualstoklebih = 0;
            }

            $djualpakai = $this->mylib->searchdata($akun, 'id', $jualpakai, '==');
            if(empty($djualpakai))
            {
                $jualpakai = 0;
            }

            $djualpendapatan = $this->mylib->searchdata($akun, 'id', $jualpendapatan, '==');
            if(empty($djualpendapatan))
            {
                $jualpendapatan = 0;
            }

            $djualretur = $this->mylib->searchdata($akun, 'id', $jualretur, '==');
            if(empty($djualretur))
            {
                $jualretur = 0;
            }

            $dmodalthlalu = $this->mylib->searchdata($akun, 'id', $modalthlalu, '==');
            if(empty($dmodalthlalu))
            {
                $modalthlalu = 0;
            }

            $dmodalthjalan = $this->mylib->searchdata($akun, 'id', $modalthjalan, '==');
            if(empty($dmodalthjalan))
            {
                $modalthjalan = 0;
            }

            $dmodalperiodejalan = $this->mylib->searchdata($akun, 'id', $modalperiodejalan, '==');
            if(empty($dmodalperiodejalan))
            {
                $modalperiodejalan = 0;
            } 

            if ($jenis == 'editdata' && $id > 0) 
            {
                // update data
                if($lihat == 1)
                {    
                    $data   = array('jualtunai'             => $jualtunai,
                                    'jualkredit'            => $jualkredit, 
                                    'piutangdagang'         => $jualpiutang,  
                                    'hutangdagang'          => $belihutang, 
                                    'returjual'             => $jualretur,
                                    'returbeli'             => $beliretur,
                                    'persediaan'            => $jualpersediaan, 
                                    'hpp'                   => $jualhpp, 
                                    'ppnmasukan'            => $belippn, 
                                    'ppnkeluaran'           => $jualppn, 
                                    'labatahunlalu'         => $modalthlalu, 
                                    'labatahunberjalan'     => $modalthjalan, 
                                    'lababulanberjalan'     => $modalperiodejalan, 
                                    'pembelian'             => $beli, 
                                    'beli_kas'              => $belikas, 
                                    'beli_discount'         => $belidiskon, 
                                    'jual_kas'              => $jualkas,  
                                    'jual_discount'         => $jualdiskon,  
                                    'rbeli_laba'            => $belilabaretur,  
                                    'rbeli_rugi'            => $belirugiretur,  
                                    'opname_fisik_kurang'   => $jualstokkurang,  
                                    'opname_fisik_lebih'    => $jualstoklebih,   
                                    'pemakaian'             => $jualpakai,  
                                    'jual_lain'             => $jualpendapatan,  
                                    );   

                    $cd     = $this->mylib->cektabeluser('pos', 'id', $id, ''); 
                    if($cd > 0)
                    {
                        $res = $this->mylib->simpanupdate('pos', $data, array('id' => $id));  
                        $this->response([
                                        'status'   => 102,
                                        'message'  => 'Sukses Mengupdate Data',
                                        'simpan'   => 'Sukses'
                                        ], 200);   
                    }
                    $this->response([
                            'status'    => 101,
                            'message'   => "Gagal Mengupdate data, Hak Akses Dibatasi"  
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
