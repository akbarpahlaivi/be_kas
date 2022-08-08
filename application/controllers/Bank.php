<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Bank extends RestController
{
    protected $menu= "Bank";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_bank');
        $this->load->model('model_pengguna');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function daftarbank_post()
    { 
        $idlog      = $this->post('idlog');  
        $user       = $this->post('user');
        $token      = $this->post('token');  

        $srnama     = $this->post('srnama');  
        $srnamaakun = $this->post('srnamaakun');  
        $srnoakun   = $this->post('srnoakun');  
        $srnorek    = $this->post('srnorek');  
        $srpemilik  = $this->post('srpemilik');  
  
        if(empty($user) || empty($token) || empty($idlog) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }
        
         
        $out    = []; 
        $data   = [];   
        $lihat  = 0;
       
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            $acb    = $this->mylib->cekhakakses('Bank', 'Master', '*', $user);   
            if(empty($acb))
            {
                $this->response([
                    'status'  => 100,
                    'message' => "Hak Akses Dibatasi"
                ], 200); 
            } 
            else
            {
                $lihat = $acb['lihat'];
            }

            if($lihat > 0)
            {
                $no     = 1; 
                $list   = $this->model_bank->databank($srnama, $srnamaakun, $srnoakun, $srnorek, $srpemilik); 
                
                foreach ($list as $key => $value) 
                {

                    $data[] = [
                                'no'        => $no++,
                                'id'        => $value['id'],
                                'kode'      => $value['kode'],
                                'nama'      => $value['nama'],
                                'norek'     => $value['norek'],
                                'pemilik'   => $value['pemilik'],
                                'alamat'    => $value['alamat'],
                                'telp'      => $value['telp'],
                                'pos'       => $value['pos'],
                                'nomerakun' => $value['nomerakun'],
                                'namaakun'  => $value['namaakun'],
                                'username'  => $value['username']                             
                            ];
                } 
                 
                $this->response([
                    'status'    => 102,
                    'data'      => $data
                ], 200);  
            }
            $this->response([
                    'status'  => 100,
                    'message' => "Hak Akses Dibatasi"
                ], 200); 
            
        } 
        $this->response([
                'status'  => 100,
                'message' => "User Tidak Ditemukan"
            ], 200);
    }

    public function hapusbank_post()
    { 
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $id     = $this->post('idbank');  
        $lihat  = 0;

        if(empty($user) || empty($token) || empty($idlog) || empty($id))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
        
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            $acb    = $this->mylib->cekhakakses('Bank', 'Master', '*', $user);   
            if(empty($acb))
            {
                $this->response([
                    'status'  => 100,
                    'message' => "Hak Akses Dibatasi"
                ], 200); 
            } 
            else
            {
                $lihat = $acb['lihat'];
            } 

            if($lihat == 1)
            { 
                $count = $this->mylib->hapusbank($id);
                if($count == 0)
                { 
                    $this->mylib->simpanupdate('bank', 
                                        array('hapus' => 1, 'userid' => $user, 'dt' => date("Y-m-d H:i:s")),
                                        array('id' => $id));    
                    $this->response([
                        'status'    => 102,
                        'message'   => "Sukses Menghapus data"  
                    ], 200);   
                } 
                $this->response([
                    'status'    => 104,
                    'message'   => "Gagal Menghapus data, data sudah terpakai"  
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
        $kode   = $this->post('kode');  
        $nama   = $this->post('nama');
        $norek  = $this->post('norek');   
        $pemilik= $this->post('pemilik'); 
        $alamat = $this->post('alamat');        
        $telp   = $this->post('telp'); 
        $pos    = $this->post('pos');    
        $lihat  = 0;


        if(empty($user) || empty($token) || empty($idlog) 
        || empty($jenis) || empty($id) || empty($kode) || empty($nama)
        || empty($norek) || empty($pos) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            $cekpos = $this->mylib->cektabeluser('perkiraan', 'id', $pos, ''); 
            if(empty($cekpos))
            {
                $this->response([
                    'status'  => 104,
                    'message' => 'Nomor Akun Pos Tidak ditemukan...',
                    'simpan'  => 'Gagal'
                ], 200); 
            }
            else
            {
                $cekpos = $cekpos['id'];
            }

            $acb    = $this->mylib->cekhakakses('Bank', 'Master', '*', $user);   
            if(empty($acb))
            {
                $this->response([
                    'status'  => 100,
                    'message' => "Hak Akses Dibatasi"
                ], 200); 
            } 
            else
            {
                $lihat = $acb['lihat'];
            }  
            
            if($jenis == 'tambahdata' && $id == -1)
            { 
                if($lihat == 1)
                { 
                    $g      = $this->mylib->cekunik('bank', 'kode', $kode);
                    if($g == 0)
                    {
                        $data  = array(
                                    'kode'         => $kode,
                                    'nama'         => $nama,
                                    'norek'        => $norek, 
                                    'pemilik'      => $pemilik,
                                    'alamat'       => $alamat,
                                    'telp'         => $telp, 
                                    'pos'          => $cekpos,
                                    'it'           => date("Y-m-d H:i:s"), 
                                    'userid'       => $user, 
                                    'search'       => $kode . ' || ' . $nama
                                );  
                        $res = $this->mylib->simpaninsert('bank', $data); 
                        if($res >= 1)
                        {
                            $id  = $this->db->insert_id(); 
                            $data=array_merge($data, array('id' => $id));
                            $this->response([
                                'status'    => 102,
                                'data'      => $data, 
                                'message'   => 'Sukses Menambah Data',
                                'simpan'    => 'Sukses'
                            ], 200);  
                        }
                        $this->response([
                                'status'    => 104,
                                'data'      => [],
                                'message'   => 'Gagal Menambah Data',
                                'simpan'    => 'Gagal'
                            ], 200);   
                    }
                    $this->response([
                                'status'    => 104,
                                'data'      => [],
                                'message'   => 'Gagal Menambah Data, Kode Duplikat',
                                'simpan'    => 'Gagal'
                            ], 200); 
                }
            }
            elseif ($jenis == 'editdata' && $id > 0) 
            {
                // update data
                if($lihat == 1)
                {  
                    $g  = $this->mylib->cekeditunik($id, 'kode', 'bank', $kode);
                    if($g == 0)
                    {
                        $data  = array(
                                    'kode'         => $kode,
                                    'nama'         => $nama,
                                    'norek'        => $norek, 
                                    'pemilik'      => $pemilik,
                                    'alamat'       => $alamat,
                                    'telp'         => $telp, 
                                    'pos'          => $cekpos, 
                                    'et'           => date("Y-m-d H:i:s"), 
                                    'userid'       => $user, 
                                    'search'       => $kode . ' || ' . $nama
                                );  
                        $this->mylib->simpanupdate('bank', $data, array('id' => $id));  
                        $this->response([
                                        'status'   => 102,
                                        'data'     => $data, 
                                        'message'  => 'Sukses Mengupdate Data',
                                        'simpan'   => 'Sukses'
                                        ], 200);   
                    }
                    $this->response([
                                'status'    => 104,
                                'data'      => [],
                                'message'   => 'Gagal Mengupdate Data, Kode Duplikat',
                                'simpan'    => 'Gagal'
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

    public function getnewcode_post()
    { 
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   
        $search = $this->post('search'); 

        if(empty($user) || empty($token) || empty($idlog) || empty($search))
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
                'status'    => 102,
                'data'      => $this->mylib->kodebaru($search, 'bank', 'kode')['NOMOR'] 
            ], 200); 
        } 
        $this->response([
                'status'  => 100,
                'message' => "User Tidak Ditemukan"
            ], 200); 
    }

    private function getakunbank($pos)
    {
        $data  = [];   
        $list  = $this->model_api->getakunperkiraan($pos);
        if(!empty($list))
        {
            foreach ($list as $key => $value) 
            {
                $data [] = ['nomor' => $value['nomor'], 'desk'  => $value['nomor'] .' || '.$value['nama']];
            }
        }
        else
        {
            $data [] = ['nomor'  => '', 'desk'  => '']; 
        } 
        return $data;
    }
    

    
}
