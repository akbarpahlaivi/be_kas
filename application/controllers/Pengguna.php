<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
 

class Pengguna extends RestController
{
    
    protected $menu= "Pengguna";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        $this->load->model('model_pengguna'); 
        date_default_timezone_set('Asia/Jakarta');
    }

    public function daftarpengguna_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token'); 
        $srnama = $this->post('srnama');    
        $lihat  = 0;        

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
            $acb    = $this->mylib->cekhakakses('Pengguna', 'Master', '*', $user);   
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
                $no     = 1; 
                $list   = $this->model_pengguna->datapengguna($srnama);     

                
                foreach ($list as $key => $value) 
                {
                    $status   = 'AKTIF';
                    if($value['hapus'] == 1)
                    {
                      $status = 'TIDAK AKTIF';
                    }
                    $data[] = [
                                'no'        => $no++,
                                'id'        => $value['id'], 
                                'nama'      => $value['nama'], 
                                'nickname'  => $value['nickname'], 
                                'hapus'     => $value['hapus'],
                                'status'    => $status,
                                'hakakses'  => $this->model_pengguna->hakakses($value['id'])
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
        $nick   = $this->post('nickname');
        $pass   = $this->post('password');   
        $status = $this->post('status');     

        if(empty($user) || empty($token) || empty($idlog) 
        || empty($jenis) || empty($id) || empty($nama) || empty($nick) || empty($status) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 

        $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Demo Only"
            ], 200);

        $hapus = 0;
        if($status == 'TIDAK AKTIF')
        {
            $hapus = 1;
        }

        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Master', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat = $cek['lihat']; 
            }
            else
            {
              $this->response([
                'status'  => 101,
                'message' => "Hak Akses Dibatasi..."
              ], 200); 
            }

            if($jenis == 'tambahdata' && $id == -1)
            {
                if($lihat == 1)
                {
                    if(empty($pass))
                    {
                        $this->response([
                            'status'  => 104,
                            'message' => "Invalid Request, Password Harus di isi..."
                        ], 200);
                    }   
                    $g      = $this->mylib->cekunik('users', 'nama', $nama); 
                    if($g == 0)
                    {
                        $login = $pass;  
                        $data  = array( 
                                        'nama'     => $nama,
                                        'nickname' => $nick,
                                        'password' => $login,  
                                        'it'       => date("Y-m-d H:i:s")
                                      );  
                        $res = $this->mylib->simpaninsert('users', $data); 
                        if($res >= 1)
                        {
                            $id  = $this->db->insert_id(); 
                            $data=array_merge($data, array('id' => $id)); 
                            unset($data['password']);
                            $this->newmenu($id); 
                            $this->response([
                                'status'    => 102,
                                'data'      => $data, 
                                'hakakses'  => $this->model_pengguna->hakakses($id),
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
                                'message'   => 'Gagal Menambah Data, username sudah ada...',
                                'simpan'    => 'Gagal'
                            ], 200); 
                }
            }
            elseif ($jenis == 'editdata' && $id > 0) 
            {
                // update data
                if($lihat == 1)
                {
                    $g  = $this->mylib->cekeditunik($id, 'nama', 'users', $nama);
                    if($g == 0)
                    {
                        if(!empty($pas))
                        { 
                            $login = $login;   
                            $data  = array(
                                        'nama'     => $nama,
                                        'nickname' => $nick,
                                        'password' => $login,  
                                        'et'       => date("Y-m-d H:i:s"), 
                                        'hapus'    => $hapus
                                    );    
                        }
                        else
                        {
                            $data  = array(
                                        'nama'     => $nama,
                                        'nickname' => $nick,
                                        'et'       => date("Y-m-d H:i:s"),   
                                        'hapus'    => $hapus
                                    );   
                        } 
                        $this->mylib->simpanupdate('users', $data, array('id' => $id));  
                        unset($data['password']);
                        $this->newmenu($id); 
                        $this->response([
                                        'status'   => 102,
                                        'data'     => $data, 
                                        'hakakses' => $this->model_pengguna->hakakses($id),
                                        'message'  => 'Sukses Mengupdate Data',
                                        'simpan'   => 'Sukses'
                                        ], 200);   
                    }
                    $this->response([
                                'status'    => 104,
                                'data'      => [],
                                'message'   => 'Gagal Mengupdate Data, username sudah ada...',
                                'simpan'    => 'Gagal'
                            ], 200);  
                }  
                $this->response([
                    'status'  => 101,
                    'message' => 'Hak Akses Dibatasi',
                    'simpan'  => 'Gagal'
                ], 200); 
            }   
        }   
        $this->response([
                'status'  => 100,
                'message' => 'User Tidak Ditemukan',
                'simpan'  => 'Gagal'
            ], 200);
    }

    public function simpanhakakses_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');   

        $id     = $this->post('id');   
        $value  = $this->post('nilai');  
        $jenis  = $this->post('vhakakses');  
        $idakses= $this->post('idakses');   

        if( empty($idlog) || empty($user) || empty($token)  
        || empty($id) || empty($jenis) || empty($idakses) )
        {
          $this->response([
              'status'  => 103,
              'message' => "Invalid Request"
          ], 200);
        } 

        $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Demo Only"
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
            else
            {
                $this->response([
                  'status'  => 101,
                  'message' => "Hak Akses Dibatasi..."
                ], 200); 
            }

            // update data
            if($lihat == 1)
            { 
                if($value == true || $value == 'true' || $value == TRUE || $value == 'TRUE')
                {
                    $value = 1;
                }
                else
                {
                    $value = 0; 
                }
               
                $this->mylib->simpanupdate('hakakses', 
                                    array($jenis => $value), 
                                    array('username' => $id, 'id' => $idakses));  
                $this->response([
                                'status'   => 102,
                                'data'     => array('id' => $idakses, $jenis => $value), 
                                'message'  => 'Sukses Mengupdate Data',
                                'simpan'   => 'Sukses'
                                ], 200);           
            }
        }  
        $this->response([
                'status'  => 100,
                'message' => 'User Tidak Ditemukan',
                'simpan'  => 'Gagal'
            ], 200);
    }

    protected function newmenu($pengguna)
    {
        $master     = array(
                            array('kode'        => 'bank',
                                  'nama'        => 'Bank',
                                  'urutan'      => 0,
                                  'grup'        => '100',
                                  'jenis'       => 'Master',
                                  'username'    => $pengguna, 
                                  'lihat'       => 0,),
                             
                            array('kode'        => 'pengguna',
                                  'nama'        => 'Pengguna',
                                  'urutan'      => 1,
                                  'grup'        => '100',
                                  'jenis'       => 'Master',
                                  'username'    => $pengguna, 
                                  'lihat'       => 0),
                            array('kode'        => 'perkiraan',
                                  'nama'        => 'Perkiraan',
                                  'urutan'      => 2,
                                  'grup'        => '100',
                                  'jenis'       => 'Master',
                                  'username'    => $pengguna,
                                  'lihat'       => 0)  
                            ); 
        $transaksi  = array( 
                            // keuangan
                            array('kode'        => 'kask',
                                  'nama'        => 'Transaksi Kas Keluar',
                                  'urutan'      => 11,
                                  'grup'        => '200',
                                  'jenis'       => 'Transaksi',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'kasm',
                                  'nama'        => 'Transaksi Kas Masuk',
                                  'urutan'      => 12,
                                  'grup'        => '200',
                                  'jenis'       => 'Transaksi',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'jurnalumum',
                                  'nama'        => 'Jurnal Umum',
                                  'urutan'      => 13,
                                  'grup'        => '200',
                                  'jenis'       => 'Transaksi',
                                  'username'    => $pengguna,
                                  'lihat'       => 0)
                            );
        $laporan    = array( 
                            // keuangan
                            array('kode'        => 'laporankeuangan',
                                  'nama'        => 'Neraca',
                                  'urutan'      => 5000,
                                  'grup'        => '300',
                                  'jenis'       => 'Laporan Keuangan',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'laporankeuangan',
                                  'nama'        => 'Neraca Lajur',
                                  'urutan'      => 5001,
                                  'grup'        => '300',
                                  'jenis'       => 'Laporan Keuangan',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'laporankeuangan',
                                  'nama'        => 'Laba Rugi',
                                  'urutan'      => 5002,
                                  'grup'        => '300',
                                  'jenis'       => 'Laporan Keuangan',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'laporankeuangan',
                                  'nama'        => 'Buku Besar',
                                  'urutan'      => 5003,
                                  'grup'        => '300',
                                  'jenis'       => 'Laporan Keuangan',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'laporankeuangan',
                                  'nama'        => 'Jurnal Umum',
                                  'urutan'      => 5004,
                                  'grup'        => '300',
                                  'jenis'       => 'Laporan Keuangan',
                                  'username'    => $pengguna,
                                  'lihat'       => 0)
                            );
        $setting    = array(
                            array('kode'        => 'setting',
                                  'nama'        => 'Setting',
                                  'urutan'      => 0,
                                  'grup'        => '400',
                                  'jenis'       => 'Setting',
                                  'username'    => $pengguna,
                                  'lihat'       => 0),
                            array('kode'        => 'settingpos',
                                  'nama'        => 'Setting Akun',
                                  'urutan'      => 3,
                                  'grup'        => '400',
                                  'jenis'       => 'Setting',
                                  'username'    => $pengguna,
                                  'lihat'       => 0) 
                            );
        $other  = [];
        // kosongkan menutemp sesuai dengan user
        $tabel  = "menutemp";
        $data   = array('username' => $pengguna);
        $res    = $this->mylib->deletedata($tabel,$data); 
        // insert ke menutemp sesuai user
        foreach ($master as $m) 
        {
            $kode       = $m['kode']; 
            $nama       = $m['nama']; 
            $username   = $m['username']; 
            $urutan     = $m['urutan']; 
            $grup       = $m['grup']; 
            $jenis      = $m['jenis'];
            $kirim      = array('kode'        => $kode,
                                'nama'        => $nama,
                                'urutan'      => $urutan,
                                'grup'        => $grup,
                                'jenis'       => $jenis,
                                'username'    => $pengguna,
                                'kategori'    => 'Master');
            $data       = $this->mylib->simpaninsert($tabel, $kirim); 
        } 
        foreach ($transaksi as $t) 
        {
            $kode       = $t['kode']; 
            $nama       = $t['nama']; 
            $username   = $t['username']; 
            $urutan     = $t['urutan']; 
            $grup       = $t['grup']; 
            $jenis      = $t['jenis'];
            $kirim      = array('kode'        => $kode,
                                'nama'        => $nama,
                                'urutan'      => $urutan,
                                'grup'        => $grup,
                                'jenis'       => $jenis,
                                'username'    => $pengguna,
                                'kategori'    => 'Transaksi');
            $data       = $this->mylib->simpaninsert($tabel, $kirim); 
        }
        foreach ($laporan as $lp) 
        {
            $kode       = $lp['kode']; 
            $nama       = $lp['nama']; 
            $username   = $lp['username']; 
            $urutan     = $lp['urutan']; 
            $grup       = $lp['grup']; 
            $jenis      = $lp['jenis'];
            $kirim      = array('kode'        => $kode,
                                'nama'        => $nama,
                                'urutan'      => $urutan,
                                'grup'        => $grup,
                                'jenis'       => $jenis,
                                'username'    => $pengguna,
                                'kategori'    => 'Laporan');
            $data       = $this->mylib->simpaninsert($tabel, $kirim); 
        } 
        foreach ($setting as $s) 
        {
            $kode       = $s['kode']; 
            $nama       = $s['nama']; 
            $username   = $s['username']; 
            $urutan     = $s['urutan']; 
            $grup       = $s['grup'];
            $jenis      = $s['jenis'];
            $kirim      = array('kode'        => $kode,
                                'nama'        => $nama,
                                'urutan'      => $urutan,
                                'grup'        => $grup,
                                'jenis'       => $jenis,
                                'username'    => $pengguna,
                                'kategori'    => 'Setting');
            $data       = $this->mylib->simpaninsert($tabel, $kirim); 
        }
        foreach ($other as $o) 
        {
            $kode       = $o['kode']; 
            $nama       = $o['nama']; 
            $username   = $o['username']; 
            $urutan     = $o['urutan']; 
            $grup       = $o['grup'];
            $jenis      = $o['jenis'];
            $kirim      = array('kode'        => $kode,
                                'nama'        => $nama,
                                'urutan'      => $urutan,
                                'grup'        => $grup,
                                'jenis'       => $jenis,
                                'username'    => $pengguna,
                                'kategori'    => 'Other');
            $data       = $this->mylib->simpaninsert($tabel, $kirim); 
        } 
        // delete hak akses yang tidak ada di menutemp 
        $hpshak = $this->db->query("delete from hakakses where username='$pengguna' and nama not in(select nama from menutemp where username='$pengguna') and kode not in(select kode from menutemp where username='$pengguna') ");
        // cocokan dan insert ke hak akses
        $menu = $this->model_pengguna->getmenutemp($pengguna);
        foreach ($menu as $temp) {
            $kd = $temp['kode'];
            $nm = $temp['nama'];
            $jn = $temp['jenis'];
            $ur = $temp['urutan'];
            $gr = $temp['grup'];
            $kr = $temp['kategori'];

            $hak    = $this->db->query("select kode, nama, username from hakakses 
                where kode='$kd' and nama='$nm' and username='$pengguna' ");
            $numhak = $hak->num_rows();
            if($numhak ==0){ 
                // insert hak akses
                $tbl    = "hakakses"; 
                $data   = array('kode'      => $kd,
                                'nama'      => $nm,
                                'urutan'    => $ur,
                                'grup'      => $gr,
                                'jenis'     => $jn,
                                'username'  => $pengguna,
                                'kategori'  => $kr);
                $data    = $this->mylib->simpaninsert($tbl,$data);
            }else{
                $tbl    = "hakakses";
                $kunci  = array('kode'    => $kd,
                                'nama'    => $nm);
                $data   = array('urutan'  => $ur,
                                'grup'    => $gr,
                                'jenis'   => $jn,
                                'kategori'=> $kr);
                $data    = $this->mylib->simpanupdate($tbl,$data, $kunci);
            }
        } 
        // delete menutemp
        $tabel  = "menutemp";
        $data   = array('username' => $pengguna);
        $res    = $this->mylib->deletedata($tabel,$data);  
    }

    public function hapuspengguna_post()
    { 
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $id     = $this->post('idpengguna');   

        if(empty($user) || empty($token) || empty($idlog) || empty($id) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 

        $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Demo Only"
            ], 200);

        if($id == $user)
        {
            $this->response([
                'status'  => 103,
                'message' => "Tidak Bisa Menghapus User Sendiri"
            ], 200); 
        }
        
        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            $lihat  = 0;
            $acb    = $this->mylib->cekhakakses('Pengguna', 'Master', '*', $user);   
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
                $this->mylib->simpanupdate('users', 
                                          array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                          array('id' => $id));    
                $this->response([
                    'status'    => 102,
                    'message'   => "Sukses Menghapus data"  
                ], 200);    
            } 
            $this->response([
                    'status'    => 100,
                    'message'   => "Hak Akses Dibatasi"  
                ], 200);     

            
        } 
        $this->response([
                'status'  => 100,
                'message' => "User Tidak Ditemukan"
            ], 200); 
    }


    
}
