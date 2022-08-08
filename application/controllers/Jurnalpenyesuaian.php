<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
 
class Jurnalpenyesuaian extends RestController
{ 
    protected $menu= "Jurnal Penyesuaian";

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('apilib');
        $this->load->model('model_bank');
        $this->load->model('model_api');
        $this->load->model('model_jurnalp');       
        $this->load->model('model_perkiraan');
        $this->load->model('jurnal'); 
    }

    public function daftarjurnalp_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $branch = $this->post('branch');  
        $lihat  = 0;
        $lihata = 0;          
         
        $page   = trim($this->post('page'));
        $length = trim($this->post('itemsPerPage'));   
        $search = $this->post('search'); 
        $orderby= $this->post('orderby');   
        $dari   = date("Ymd",strtotime($this->post('dari')));
        $sampai = date("Ymd",strtotime($this->post('sampai'))); 
        
        if(empty($user) || empty($token) || empty($idlog) || empty($page) 
        || empty($length) || empty($dari) || empty($sampai))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
        
        if($page == '1')
        {
            $start  = 0;
        }
        else
        { 
            $start = ($page - 1) * $length ;
        }  
        
        $out    = []; 
        $data   = [];   
       
        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat,lihatall', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];
                $lihata = $cek['lihatall'];
            }

            if($lihat == 1)
            {  
                $list   = $this->model_jurnalp->datajurnalp($search, $length, $start, $orderby, $branch, $dari, $sampai, 
                    $lihata, $user );   
                if($page == 1)
                {
                    $no = 1; 
                }
                else
                {
                    $no = $page * $length - $length + 1;
                }
                
                foreach ($list as $key => $value) 
                { 
                    $data[] = [
                                'no'          => $no++,
                                'id'          => $value['id'],
                                'nobukti'     => $value['nobukti'],
                                'tgl'         => $value['tgl'], 
                                'jenis'       => $value['jenis'], 
                                'tabel'       => $value['tabel'],  
                                'totaldebet'  => $value['totaldebet'],
                                'totalkredit' => $value['totalkredit'],
                                'username'    => $value['username'],
                                'cabang'      => $value['cabang'],
                                'datad'       => $this->model_jurnalp->getdatad($value['id'])
                            ];
                    // $jrnl = $this->jurnal->jurnalkas($value['id'], 'KAS KELUAR'); 
                } 
                 
                $this->response([
                    'status'    => 102,
                    'data'      => $data,
                    'totaldata' => $this->model_jurnalp->countdata($branch, $dari, $sampai, $lihata, $user),
                    'totalpage' => $this->apilib->divpage($this->model_jurnalp->countdata($branch, $dari, $sampai, $lihata, $user), $length),
                    'perpage'   => $length 
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
        $branch = $this->post('branch');  

        $jenis  = $this->post('jenis');  
        $idh    = $this->post('idh'); //  new set id = -1
        $idd    = $this->post('idd'); //  new set id = -1
        // header
        $nobukti= $this->post('nobukti');  
        $tgl    = $this->post('tanggal');            
        $jenisp = $this->post('jenisjurnal'); 
        // detail
        $idakun = $this->post('idakun');
        $desk2  = $this->post('desk2');
        $debet  = $this->post('debet');
        $kredit = $this->post('kredit');; 

        
        if($this->mylib->validateDate($tgl) !== true) {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Format Tanggal Tidak Sesuai, Y-m-d"
            ], 200);
        } 

        if($jenisp != 'PENYESUAIAN' && $jenisp != 'MEMORIAL') {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Jenis Jurnal Tidak Sesuai"
            ], 200);
        } 

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) 
        || empty($jenis) || empty($idh) || empty($idd) || empty($nobukti) 
        || empty($jenisp))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
        

        $tgl    = date("Y-m-d", strtotime($tgl));
        $periode= date("Ym", strtotime($tgl));
        $dperiod= date("Ymd", strtotime($tgl));

        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'tambah,edit', $user); 
            if(!empty($cek))
            {
                $tambah  = $cek['tambah'];
                $edit    = $cek['edit'];
            } 

            if($jenis == 'tambahdata' && $idh == -1)
            {
                if($tambah == 1)
                {   
                    if(!empty($idakun) || $idakun != 0)
                    {
                        // cek pos 
                        $pos        = $this->model_perkiraan->getakunpos(0, $branch);
                        $dpos       = $this->mylib->searchdata($pos, 'id', $idakun, '==');
                        if(empty($dpos))
                        { 
                            $this->response(['status'    => 104,
                                             'message'   => 'Data Pos Perkiraan tidak ditemukan'],200);
                        }
                        $g      = $this->mylib->cekunik('jurnal_h', 'nobukti', $nobukti, $branch);
                        if($g == 0)
                        {
                            $data  = array(
                                        'nobukti'   => $nobukti,
                                        'tgl'       => $tgl,
                                        'it'        => date("Y-m-d H:i:s"), 
                                        'periode'   => $periode, 
                                        'dperiode'  => $dperiod, 
                                        'jenis'     => $jenisp,
                                        'tabel'     => 'PENYESUAIAN',
                                        'userid'    => $user,
                                        'cabang'    => $branch
                                    );  
                            $res = $this->mylib->simpaninsert('jurnal_h',$data); 
                            if($res >= 1)
                            {
                                $idh = $this->db->insert_id();  
                                // simpan detail...
                                if($idd < 1)
                                { 
                                    $datad = array(
                                        'idh'        => $idh, 
                                        'norek'      => $idakun,
                                        'keterangan' => $desk2,
                                        'deskripsi'  => 'PENYESUAIAN',
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit 
                                    );  
                                    $resd = $this->mylib->simpaninsert('jurnal_d',$datad);  
                                }  
                                $total= $this->model_jurnalp->updtotal($idh);
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_jurnalp->getdatah($idh),
                                    'datad'     => $this->model_jurnalp->getdatad($idh),  
                                    'message'   => 'Sukses Menambah Data',
                                    'simpan'    => 'Sukses'
                                ], 200);  
                            }
                            $this->response([
                                    'status'    => 104,
                                    'datah'     => $this->model_jurnalp->getdatah($idh),
                                    'datad'     => $this->model_jurnalp->getdatad($idh),
                                    'message'   => 'Gagal Menambah Data',
                                    'simpan'    => 'Gagal'
                                ], 200);   
                        }
                        $this->response([
                                'status'    => 104,
                                'datah'     => [],
                                'datad'     => [],
                                'message'   => 'Gagal Menambah Data, Nobukti Sudah Ada',
                                'simpan'    => 'Gagal'
                            ], 200); 

                    }
                    $this->response([
                                'status'    => 104,
                                'datah'     => [],
                                'datad'     => [],
                                'message'   => 'Gagal Menambah Data, Data detail tidak boleh kosong...',
                                'simpan'    => 'Gagal'
                            ], 200); 
                }
                $this->response([
                    'status'  => 101,
                    'message' => 'Gagal Menambah data, Hak Akses Dibatasi',
                    'simpan'  => 'Gagal'
                ], 200);
            }
            elseif ($jenis == 'editdata' && $idh > 0) 
            {
                // update data
                if($edit == 1)
                {  
                    $g  = $this->mylib->cekeditunik($idh, 'nobukti', 'jurnal_h', $nobukti, $branch);
                    if($g == 0)
                    {
                        // update header
                        $data  = array(
                                        'nobukti'   => $nobukti,
                                        'tgl'       => $tgl,
                                        'et'        => date("Y-m-d H:i:s"), 
                                        'periode'   => $periode, 
                                        'dperiode'  => $dperiod,
                                        'jenis'     => $jenisp,
                                        'tabel'     => 'PENYESUAIAN',
                                        'userid'    => $user,
                                        'cabang'    => $branch
                                    );   
                        $res = $this->mylib->simpanupdate('jurnal_h', $data, array('id' => $idh, 'cabang' => $branch));  
                        // detail
                        if(!empty($idakun) || $idakun != 0)
                        {
                            // cek pos 
                            $pos        = $this->model_perkiraan->getakunpos(0, $branch);
                            $dpos       = $this->mylib->searchdata($pos, 'id', $idakun, '==');
                            if(empty($dpos))
                            { 
                                $this->response(['status'    => 104,
                                                 'message'   => 'Data Pos Perkiraan tidak ditemukan'],200);
                            }
                            // simpan detail 
                            if($idd < 1)
                            { 
                                $datad = array(
                                        'idh'        => $idh, 
                                        'norek'      => $idakun,
                                        'keterangan' => $desk2,
                                        'deskripsi'  => 'PENYESUAIAN',
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit
                                    );  
                                $resd = $this->mylib->simpaninsert('jurnal_d',$datad);  
                                $total= $this->model_jurnalp->updtotal($idh); 
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_jurnalp->getdatah($idh), 
                                    'datad'     => $this->model_jurnalp->getdatad($idh), 
                                    'message'   => 'Sukses Mengupdate Data',
                                    'simpan'    => 'Sukses'
                                ], 200);    
                            }  
                            else
                            {
                                $datad = array(
                                        'idh'        => $idh, 
                                        'norek'      => $idakun,
                                        'keterangan' => $desk2,
                                        'deskripsi'  => 'PENYESUAIAN',
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit 
                                    );  
                                $resd = $this->mylib->simpanupdate('jurnal_d',$datad, array('idh' => $idh, 'id' => $idd));  
                                $total= $this->model_jurnalp->updtotal($idh);
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_jurnalp->getdatah($idh), 
                                    'datad'     => $this->model_jurnalp->getdatad($idh), 
                                    'message'   => 'Sukses Mengupdate Data',
                                    'simpan'    => 'Sukses'
                                ], 200);    
                            } 
                        }   
                        $this->response([
                                        'status'   => 102,
                                        'datah'     => $this->model_jurnalp->getdatah($idh), 
                                        'datad'     => $this->model_jurnalp->getdatad($idh), 
                                        'message'  => 'Sukses Mengupdate Data',
                                        'simpan'   => 'Sukses'
                                        ], 200);  
                    }
                    $this->response([
                                'status'    => 104,
                                'datah'     => [],
                                'datad'     => [],
                                'message'   => 'Gagal Mengupdate Data, Nomor Duplikat',
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

    public function hapusjurnalp_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $branch = $this->post('branch');  
        $id     = $this->post('idjurnal');  

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) || empty($id) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $hapus  = 0;
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'hapus, lihatall', $user); 
            if(!empty($cek))
            {
                $hapus  = $cek['hapus']; 
                $lihata = $cek['lihatall'];
            }

            if($hapus == 1)
            { 
                if($lihata == 0)
                {
                    // cek.....
                    $cd = $this->mylib->cektabelusercabang('jurnal_h', 'id', $id, $branch, $user); 
                    if($cd > 0)
                    {
                        $res = $this->mylib->simpanupdate('jurnal_h', 
                                                    array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                                    array('id'    => $id, 'cabang' => $branch, 'userid' => $user));  
                        $res = $this->mylib->simpanupdate('jurnal_d', 
                                                    array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                                    array('idh'   => $id));  
                        $this->response([
                            'status'    => 102,
                            'message'   => "Sukses Menghapus data"  
                        ], 200);   
                    } 
                    $this->response([
                        'status'    => 101,
                        'message'   => "Gagal Menghapus data, Hak Akses Dibatasi"  
                    ], 200);     
                } 
                $res = $this->mylib->simpanupdate('jurnal_h', 
                                            array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                            array('id' => $id, 'cabang' => $branch));    
                $res = $this->mylib->simpanupdate('jurnal_d', 
                                            array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                            array('idh'   => $id));  
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

    public function hapusdatad_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $branch = $this->post('branch');  
        $idd    = $this->post('idjurnalpd');  
        $idh    = $this->post('idjurnalph');  

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) 
        || empty($idh) || empty($idd))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $hapus  = 0;
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'hapus, lihatall', $user); 
            if(!empty($cek))
            {
                $hapus  = $cek['hapus']; 
                $lihata = $cek['lihatall'];
            }

            if($hapus == 1)
            { 
                if($lihata == 0)
                {
                    // cek.....
                    $cd = $this->mylib->cektabeldusercabang('jurnal_d', 'idh', $idh, 'id', $idd, '', ''); 
                    if($cd > 0)
                    { 
                        $res = $this->mylib->simpanupdate('jurnal_d', 
                                                    array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                                    array('idh'   => $idh, 'id'   => $idd)); 
                        $this->model_jurnalp->updtotal($idh);
                        $this->response([
                            'status'    => 102,
                            'message'   => "Sukses Menghapus data",
                            'datah'     => $this->model_jurnalp->getdatah($idh),
                            'datad'     => $this->model_jurnalp->getdatad($idh)
                        ], 200);   
                    } 
                    $this->response([
                        'status'    => 101,
                        'message'   => "Gagal Menghapus data, Hak Akses Dibatasi"  
                    ], 200);     
                }   
                $res = $this->mylib->simpanupdate('jurnal_d', 
                                            array('hapus' => 1, 'dt' => date("Y-m-d H:i:s")),
                                            array('idh'   => $idh, 'id'   => $idd));   
                $this->model_jurnalp->updtotal($idh);
                $this->response([
                    'status'    => 102,
                    'message'   => "Sukses Menghapus data",
                    'datah'     => $this->model_jurnalp->getdatah($idh),
                    'datad'     => $this->model_jurnalp->getdatad($idh)  
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
    
    public function getnewcode_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $branch = $this->post('branch');  
        $search = $this->post('search'); 

        if($this->mylib->validateDate($search) !== true) {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Format Tanggal Tidak Sesuai, Y-m-d"
            ], 200);
        } 

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) || empty($search))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }
        $search = date("Ymd", strtotime($search)); 
       
        $data   = [];   
        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'tambah', $user); 
            if(!empty($cek))
            {
                $tambah  = $cek['tambah']; 
            }

            if($tambah == 1)
            {

                $this->response([
                    'status'    => 102,
                    'data'      => $this->mylib->nomorbaru($search, 'jurnal_h', 'nobukti', $branch)['NOMOR'] 
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

    public function request_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $branch = $this->post('branch');  
        $idkask = $this->post('idkask'); 

        if(empty($user) || empty($token) || empty($idlog) || empty($branch) || empty($idkask))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
         
        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'tambah', $user); 
            if(!empty($cek))
            {
                $tambah  = $cek['tambah']; 
            }

            if($tambah == 1)
            {
                $token = md5('KAS_KELUAR'.(new datetime())->format('YmdHisu')); 
                $this->mylib->simpaninsert('cetakfaktur', 
                                     array('idh'   => $idkask, 
                                           'it'    => date("Y-m-d H:i:s"), 
                                           'exp'   => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"). ' + 1 days')),
                                           'token' => $token,
                                           'jenis' => 'KAS KELUAR'
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
