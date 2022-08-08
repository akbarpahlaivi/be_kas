<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
 
class Kasm extends RestController
{ 
    protected $menu= "Transaksi Kas Masuk";

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('apilib');
        $this->load->model('model_bank'); 
        $this->load->model('model_pengguna'); 
        $this->load->model('model_api');
        $this->load->model('model_kasm');       
        $this->load->model('model_perkiraan');
        $this->load->model('jurnal'); 
    }

    public function daftarkasmasuk_post()
    {
        $idlog     = $this->post('idlog');  
        $user      = $this->post('user');
        $token     = $this->post('token');   
        $lihat     = 0; 
        $dari      = date("Ymd",strtotime($this->post('dari')));
        $sampai    = date("Ymd",strtotime($this->post('sampai'))); 
         
        // search
        $srnobukti = $this->post('srnobukti'); 
        $srkodebank= $this->post('srkodebank');
        $srnamabank= $this->post('srnamabank');
        $srdesk    = $this->post('srdesk'); 
        
        if(empty($user) || empty($token) || empty($idlog) || empty($dari) || empty($sampai))
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
                $list   = $this->model_kasm->datakasmasuk($srnobukti, $srkodebank, $srnamabank, $srdesk, $dari, $sampai);   
                
                foreach ($list as $key => $value) 
                { 
                    $data[] = [
                                'no'          => $no++,
                                'id'          => $value['id'],
                                'nobukti'     => $value['nobukti'],
                                'tgl'         => $value['tgl'], 
                                'kodebank'    => $value['kode'], 
                                'idbank'      => $value['kodebank'], 
                                'desk1'       => $value['deskripsi'],
                                'namabank'    => $value['namabank'], 
                                'totaldebet'  => $value['totaldebet'],
                                'totalkredit' => $value['totalkredit'],
                                'username'    => $value['username'],
                                'datad'       => $this->model_kasm->getdatad($value['id'])
                            ];
                    $this->model_kasm->updtotal($value['id']); 
                    $this->jurnal->jurnalkas($value['id'], 'KAS MASUK'); 
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
        $idh    = $this->post('idh'); //  new set id = -1
        $idd    = $this->post('idd'); //  new set id = -1
        // header 
        $nobukti= $this->post('nobukti');  
        $tgl    = $this->post('tanggal');            
        $idbank = $this->post('idbank');
        $desk1  = $this->post('desk1');
        // detail
        $idakun = $this->post('idakun');
        $desk2  = $this->post('desk2');
        $kredit = $this->post('kredit');
        $debet  = 0; 
        $lihat  = 0;

        
        if($this->mylib->validateDate($tgl) !== true) {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Format Tanggal Tidak Sesuai, Y-m-d"
            ], 200);
        } 

        if(empty($user) || empty($token) || empty($idlog) 
        || empty($jenis) || empty($idh) || empty($idd) || empty($nobukti)  
        || empty($idbank))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
        

        $tgl    = date("Y-m-d", strtotime($tgl));
        $periode= date("Ym", strtotime($tgl));
        $dperiod= date("Ymd", strtotime($tgl));

        $ck     = $this->mylib->cektoken($user, $token, $idlog); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat']; 
            }  

            // cek bank
            $bank      = $this->model_bank->getbank();
            $dbank     = $this->mylib->searchdata($bank, 'id', $idbank, '==');
            if(empty($dbank))
            {
                $this->response(['status'    => 104,
                                 'message'   => 'Data Bank tidak ditemukan'],200);
            }

            if($jenis == 'tambahdata' && $idh == -1)
            {
                if($lihat == 1)
                {   
                    if(!empty($idakun) || $idakun != 0)
                    {
                        // cek pos 
                        $pos        = $this->model_perkiraan->getakunpos(0);
                        $dpos       = $this->mylib->searchdata($pos, 'id', $idakun, '==');
                        if(empty($dpos))
                        { 
                            $this->response(['status'    => 104,
                                             'message'   => 'Data Pos Perkiraan tidak ditemukan'],200);
                        }
                        $g      = $this->mylib->cekunik('trankasm_h', 'nobukti', $nobukti);
                        if($g == 0)
                        {
                            $data  = array(
                                        'nobukti'   => $nobukti,
                                        'tgl'       => $tgl,
                                        'it'        => date("Y-m-d H:i:s"), 
                                        'periode'   => $periode, 
                                        'dperiode'  => $dperiod,
                                        'deskripsi' => $desk1, 
                                        'kodebank'  => $idbank,
                                        'userid'    => $user
                                    );  
                            $res = $this->mylib->simpaninsert('trankasm_h',$data); 
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
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit,
                                        'userid'     => $user,
                                        'it'         => date("Y-m-d H:i:s")
                                    );  
                                    $this->mylib->simpaninsert('trankasm_d',$datad);  
                                }  
                                $this->model_kasm->updtotal($idh); 
                                $this->jurnal->jurnalkas($idh, 'KAS MASUK'); 
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_kasm->getdatah($idh),
                                    'datad'     => $this->model_kasm->getdatad($idh),  
                                    'message'   => 'Sukses Menambah Data',
                                    'simpan'    => 'Sukses'
                                ], 200);  
                            }
                            $this->response([
                                    'status'    => 104,
                                    'datah'     => $this->model_kasm->getdatah($idh),
                                    'datad'     => $this->model_kasm->getdatad($idh),
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
                if($lihat == 1)
                {  
                    $g  = $this->mylib->cekeditunik($idh, 'nobukti', 'trankasm_h', $nobukti);
                    if($g == 0)
                    {
                        // update header
                        $data  = array(
                                        'nobukti'   => $nobukti,
                                        'tgl'       => $tgl,
                                        'et'        => date("Y-m-d H:i:s"), 
                                        'periode'   => $periode, 
                                        'dperiode'  => $dperiod,
                                        'deskripsi' => $desk1, 
                                        'kodebank'  => $idbank,
                                        'userid'    => $user,
                                    );   
                        $res = $this->mylib->simpanupdate('trankasm_h', $data, array('id' => $idh));  
                        // detail
                        if(!empty($idakun) || $idakun != 0)
                        {
                            // cek pos 
                            $pos        = $this->model_perkiraan->getakunpos(0);
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
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit,
                                        'userid'     => $user,
                                        'it'         => date("Y-m-d H:i:s") 
                                    );  
                                $this->mylib->simpaninsert('trankasm_d',$datad);  
                                $this->model_kasm->updtotal($idh); 
                                $this->jurnal->jurnalkas($idh, 'KAS MASUK'); 
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_kasm->getdatah($idh), 
                                    'datad'     => $this->model_kasm->getdatad($idh), 
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
                                        'debet'      => $debet,                                    
                                        'kredit'     => $kredit,
                                        'userid'     => $user,
                                        'et'         => date("Y-m-d H:i:s") 
                                    );  
                                $this->mylib->simpanupdate('trankasm_d',$datad, array('idh' => $idh, 'id' => $idd));  
                                $this->model_kasm->updtotal($idh); 
                                $this->jurnal->jurnalkas($idh, 'KAS MASUK'); 
                                $this->response([
                                    'status'    => 102,
                                    'datah'     => $this->model_kasm->getdatah($idh), 
                                    'datad'     => $this->model_kasm->getdatad($idh), 
                                    'message'   => 'Sukses Mengupdate Data',
                                    'simpan'    => 'Sukses'
                                ], 200);    
                            } 
                        }
                        $this->model_kasm->updtotal($idh); 
                        $this->jurnal->jurnalkas($idh, 'KAS MASUK');    
                        $this->response([
                                        'status'   => 102,
                                        'datah'     => $this->model_kasm->getdatah($idh), 
                                        'datad'     => $this->model_kasm->getdatad($idh), 
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

    public function hapuskasmasuk_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $id     = $this->post('idkasm');  

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
            // cek hak akses
            $hapus  = 0;
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat'];  
            }

            if($lihat == 1)
            { 
                $this->mylib->deletedata('trankasm_h', array('id' => $id));  
                $this->mylib->deletedata("jurnal_h", array('tabel' => 'KAS MASUK', 'idsumber' => $id));  
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
        $idd    = $this->post('idkasmd');  
        $idh    = $this->post('idkasmh');   

        if(empty($user) || empty($token) || empty($idlog)  
        || empty($idh) || empty($idd))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }
        $lihat  = 0;
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
                $this->mylib->deletedata('trankasm_d', array('id' => $idd));     
                $this->model_kasm->updtotal($idh); 
                $this->jurnal->jurnalkas($idh, 'KAS MASUK'); 
                $this->response([
                    'status'    => 102,
                    'message'   => "Sukses Menghapus data",
                    'datah'     => $this->model_kasm->getdatah($idh),
                    'datad'     => $this->model_kasm->getdatad($idh)
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
        $search = $this->post('search');  

        if($this->mylib->validateDate($search) !== true) {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request, Format Tanggal Tidak Sesuai, Y-m-d"
            ], 200);
        } 

        if(empty($user) || empty($token) || empty($idlog) || empty($search))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }
        $search = date("Ymd", strtotime($search)); 
       
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
                $this->response([
                    'status'    => 102,
                    'data'      => $this->mylib->nomorbaru($search, 'trankasm_h', 'nobukti')['NOMOR'] 
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
        $idkasm = $this->post('idkasm'); 

        if(empty($user) || empty($token) || empty($idlog) || empty($idkasm))
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
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat']; 
            }

            if($lihat == 1)
            {
                $token = (new datetime())->format('YmdHisu'); 
                $this->mylib->simpaninsert('cetakfaktur', 
                                     array('idh'   => $idkasm, 
                                           'it'    => date("Y-m-d H:i:s"), 
                                           'exp'   => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"). ' + 1 days')),
                                           'token' => $token,
                                           'jenis' => 'KAS MASUK'
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

     public function requestmini_post()
    {
        $idlog  = $this->post('idlog');  
        $user   = $this->post('user');
        $token  = $this->post('token');  
        $idkasm = $this->post('idkasm'); 

        if(empty($user) || empty($token) || empty($idlog) || empty($idkasm))
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        } 
         
        $ck = $this->mylib->cektoken($user, $token, $idlog); 
        if($ck == 101) 
        { 
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Transaksi', 'lihat', $user); 
            if(!empty($cek))
            {
                $lihat  = $cek['lihat']; 
            }

            if($lihat == 1)
            {
                $token = NOINV; 
                $this->mylib->simpaninsert('cetakfaktur', 
                                     array('idh'   => $idkasm, 
                                           'it'    => date("Y-m-d H:i:s"), 
                                           'exp'   => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"). ' + 1 days')),
                                           'token' => $token,
                                           'jenis' => 'RECEIPT KAS MASUK'
                                        )); 
                $this->response([
                    'status'    => 102,
                    'data'      => $token
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

     
     
    

    


     


    

     

    

    
}
