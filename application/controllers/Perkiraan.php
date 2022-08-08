<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Perkiraan extends RestController
{
    protected $menu= "Perkiraan";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_perkiraan');
        $this->load->model('model_pengguna'); 
        date_default_timezone_set('Asia/Jakarta');
    }

    public function daftarperkiraan_post()
    {
        $idlog      = $this->post('idlog');  
        $user       = $this->post('user');
        $token      = $this->post('token');   
        $lihat      = 0; 

        $srnomor    = $this->post('srnomor');  
        $srnama     = $this->post('srnama');  
        $srnoheader = $this->post('srnoheader');  
        $srposisidk = $this->post('srposisidk');  
        $srposisinr = $this->post('srposisinr');      
         
         
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
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $no     = 1; 
                $list   = $this->model_perkiraan->dataperkiraan($srnomor, $srnama, $srnoheader, $srposisidk, $srposisinr);                   
                
                foreach ($list as $key => $value) 
                {  
                    $data[] = [
                                'no'            => $no++,
                                'id'            => $value['id'],
                                'nomor'         => $value['nomor'],
                                'nama'          => $value['nama'],
                                'keterangan'    => $value['keterangan'],
                                'jenislevel'    => $value['jenislevel'],
                                'levelno'       => $value['levelno'],
                                'nomorheader'   => $value['nomorheader'],
                                'noheader'      => $value['noheader'],
                                'namaheader'    => $value['namaheader'],
                                'posisidk'      => $value['posisidk'],
                                'posisinr'      => $value['posisinr'],
                                'posisineraca'  => $value['posisineraca'],
                                'username'      => $value['username'], 
                            ];
                } 
                 
                $this->response([
                    'status'    => 102,
                    'data'      => $data],200);
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

    public function hapusperkiraan_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $id     = $this->post('idperkiraan');  

        if(empty($user) || empty($token) || empty($idlog) || empty($id) )
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
            $lihat  = 0;
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            { 
                $lihat = $cek['lihat'];
            }

            if($lihat == 1)
            {
                $count = $this->mylib->hapusperkiraan($id);
                if($count == 0)
                {
                    $this->mylib->simpanupdate('perkiraan', 
                                                    array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
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
        $idlog        = $this->post('idlog');  
        $user         = $this->post('user');
        $token        = $this->post('token');  

        $jenis        = $this->post('jenis');  
        $id           = $this->post('id'); //  new set id = -1

        $nomor        = $this->post('nomor');  
        $nama         = $this->post('nama');
        $keterangan   = $this->post('keterangan');   
        $jenislevel   = $this->post('jenislevel'); 
        $levelno      = $this->post('levelno');        
        $nomorheader  = $this->post('nomorheader'); 
        $posisidk     = $this->post('posisidk');             
        $posisinr     = $this->post('posisinr');  
        $posisineraca = $this->post('posisineraca');
        
        if(empty($user) || empty($token) || empty($idlog) 
        || empty($jenis) || empty($id) || empty($nomor) || empty($nama)
        || empty($jenislevel) || empty($levelno) 
        || empty($levelno) || empty($posisidk) || empty($posisinr) || empty($posisineraca))
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
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat = $cek['lihat']; 
            }



            if($nomorheader > 0)
            {
                $cekpos = $this->mylib->cektabeluser('perkiraan', 'id', $nomorheader, ''); 
                if(empty($cekpos))
                {
                    if($nomorheader == '-0000-' || $nomorheader == '0' || $nomorheader == NULL)
                    {
                        $cekpos = 0; 
                    }
                    else
                    {
                        $this->response([
                            'status'  => 104,
                            'message' => 'Nomor Akun Pos Tidak ditemukan...',
                            'simpan'  => 'Gagal'
                        ], 200); 
                    } 
                }
                else
                {
                    $cekpos = $cekpos['id'];
                } 
            }
            else
            {
                $cekpos = 0;  
            }

                

            if($jenis == 'tambahdata' && $id == -1)
            {
                if($lihat == 1)
                {  
                    $g      = $this->mylib->cekunik('perkiraan', 'nomor', $nomor);
                    if($g == 0)
                    {
                        $data  = array(
                                    'nomor'        => $nomor,
                                    'nama'         => $nama,
                                    'levelno'      => $levelno, 
                                    'nomorheader'  => $cekpos,
                                    'posisidk'     => $posisidk,
                                    'posisinr'     => $posisinr, 
                                    'posisineraca' => $posisineraca, 
                                    'keterangan'   => $keterangan, 
                                    'jenislevel'   => $jenislevel,  
                                    'userid'       => $user,
                                    'it'           => date("Y-m-d H:i:s"),
                                    'search'       => $nomor . ' || ' . $nama
                                );  
                        $res = $this->mylib->simpaninsert('perkiraan', $data); 
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
                                'message'   => 'Gagal Menambah Data, Nomor Perkiraan Sudah Ada',
                                'simpan'    => 'Gagal'
                            ], 200); 
                }
            }
            elseif ($jenis == 'editdata' && $id > 0) 
            {
                // update data
                if($lihat == 1)
                {  
                    $g  = $this->mylib->cekeditunik($id, 'nomor', 'perkiraan', $nomor);
                    if($g == 0)
                    {
                        $data  = array(
                                    'nomor'        => $nomor,
                                    'nama'         => $nama,
                                    'levelno'      => $levelno, 
                                    'nomorheader'  => $cekpos,
                                    'posisidk'     => $posisidk,
                                    'posisinr'     => $posisinr, 
                                    'posisineraca' => $posisineraca, 
                                    'keterangan'   => $keterangan, 
                                    'jenislevel'   => $jenislevel,  
                                    'userid'       => $user,
                                    'et'           => date("Y-m-d H:i:s"),
                                    'search'       => $nomor . ' || ' . $nama
                                );  
                        $res = $this->mylib->simpanupdate('perkiraan', $data, array('id' => $id));  
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

    public function daftarpos4_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  

        if(empty($user) || empty($token) || empty($idlog) )
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
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $list   = $this->model_perkiraan->akunpos(4);   
                foreach ($list as $key => $value) 
                {  
                    $data[] = [ 
                                'nomor' => $value['id'],
                                'desk'  => $value['search']
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

    public function daftarpos_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   
        $search = $this->post('search'); 

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
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $list   = $this->model_perkiraan->akunpos(0);   
                foreach ($list as $key => $value) 
                {   
                    $data[] = [ 
                                'nomor' => $value['id'],
                                'desk'  => $value['search']
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
    
    public function getpos_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $search = $this->post('search'); 

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
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $list   = $this->model_perkiraan->getpos($search);   
                foreach ($list as $key => $value) 
                {  
                    $data[] = [ 
                                'id'    => $value['id'],
                                'text'  => $value['nomor'] . ' || ' . $value['nama']
                            ];
                }  
                $this->response([
                    'status'    => 103,
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


    public function datapos_post()
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
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
            }

            if($lihat == 1)
            {  
                $list   = $this->model_perkiraan->getakunpos(0);    
                foreach ($list as $key => $value) 
                {   
                    $data[] = [ 
                                'nomor' => $value['id'],
                                'desk'  => $value['nomor'] . ' || ' . $value['nama']
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
    

   

    
}
