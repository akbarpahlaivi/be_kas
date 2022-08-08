<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Hitungulang extends RestController
{
    protected $menu= "Hitung Ulang Saldo";

    function __construct()
    {
        parent::__construct();
        $this->load->library('apilib');
        $this->load->model('model_api');
        // beli
        $this->load->model('model_permintaan');
        $this->load->model('model_pesananb');
        $this->load->model('model_penerimaan');
        $this->load->model('model_pesananb');
        $this->load->model('model_beli');
        $this->load->model('model_rbeli');
        // jual
        $this->load->model('model_penawaran');  
        $this->load->model('model_pesananj');
        $this->load->model('model_pengiriman');
        $this->load->model('model_jual');
        $this->load->model('model_rjual'); 
        // stok
        $this->load->model('model_persediaan');
        $this->load->model('model_stokop'); 
        $this->load->model('model_mutasi'); 
        $this->load->model('model_pemakaian'); 
        // hupi
        $this->load->model('model_hutang');
        $this->load->model('model_bayarh');
        $this->load->model('model_piutang');
        $this->load->model('model_bayarp');
        // keuangan
        $this->load->model('model_kask');
        $this->load->model('model_kasm');
        $this->load->model('model_jurnalp');
        $this->load->model('p_saldo');   
        $this->load->model('jurnal');         
        date_default_timezone_set('Asia/Jakarta');
    } 

    public function simpandata_post()
    {
        $idlog      = $this->post('idlog');  
        $user       = $this->post('user');
        $token      = $this->post('token');  
        $branch     = $this->post('branch');   
        $cabang     = $this->post('cabang');  

        $prbeli     = $this->post('prbeli');
        $psbeli     = $this->post('psbeli');
        $pnbeli     = $this->post('pnbeli');
        $beli       = $this->post('beli');
        $rbeli      = $this->post('rbeli');
        $prjual     = $this->post('prjual');
        $psjual     = $this->post('psjual');
        $pnjual     = $this->post('pnjual');
        $jual       = $this->post('jual');
        $rjual      = $this->post('rjual');
        $stok       = $this->post('stok');
        $mutasi     = $this->post('mutasi');
        $pemakaian  = $this->post('pemakaian');
        $hutang     = $this->post('hutang');
        $bayarh     = $this->post('bayarh');
        $piutang    = $this->post('piutang');
        $bayarp     = $this->post('bayarp');
        $kas        = $this->post('kas');
        $jurnal     = $this->post('jurnal');
        $saldo      = $this->post('saldo');
        $tutupbk    = $this->post('tutupbk');

        $tanggal    = date("Y-m-d",strtotime($this->post('tanggal')));  
        $oldperiode = $this->mylib->get_oldperiode($tanggal); 
        $newperiode = $this->mylib->get_newperiode($tanggal);
        $periode    = date("Ym",strtotime($tanggal));  
        $newtanggal = $newperiode . "01";
        $newtanggal = date("Y-m-d", strtotime($newtanggal)); 

        if(empty($user) || empty($token) || empty($idlog) || empty($cabang) || empty($branch) || empty($tanggal) )
        {
            $this->response([
                'status'  => 103,
                'message' => "Invalid Request"
            ], 200);
        }

        $datahitung = array(
                        'userid'        => $user,
                        'branch'        => $branch,
                        'cabang'        => $cabang,
                        'periode'       => $periode,
                        'newperiode'    => $newperiode,
                        'oldperiode'    => $oldperiode,
                        'tanggal'       => $tanggal,
                        'newtanggal'    => $newtanggal);  

        $ck     = $this->mylib->cektoken($user, $token, $idlog, $branch); 
        if ($ck == 101) 
        {
            // cek hak akses
            $cek    = $this->mylib->cekhakakses($this->menu, 'Setting', 'lihatall', $user); 
            if(!empty($cek))
            {
                $lihatall  = $cek['lihatall']; 
            }
            if($lihatall == 1)
            {
                if($prbeli == 'YES' || $prbeli == 'Yes')
                {
                    $this->permintaanbeli($datahitung);
                }
                if($psbeli == 'YES' || $psbeli == 'Yes')
                {
                    $this->pesananb($datahitung);
                }
                if($pnbeli == 'YES' || $pnbeli == 'Yes')
                {
                    $this->penerimaan($datahitung);
                }
                if($beli == 'YES' || $beli == 'Yes')
                {
                    $this->beli($datahitung);
                }
                if($rbeli == 'YES' || $rbeli == 'Yes')
                {
                    $this->rbeli($datahitung);
                }
                if($prjual == 'YES' || $prjual == 'Yes')
                {
                    $this->penawaran($datahitung);
                }
                if($psjual == 'YES' || $psjual == 'Yes')
                {
                    $this->pesananj($datahitung);
                }
                if($pnjual == 'YES' || $pnjual == 'Yes')
                {
                    $this->pengiriman($datahitung);
                }
                if($jual == 'YES' || $jual == 'Yes')
                {
                    $this->jual($datahitung);
                }
                if($rjual == 'YES' || $rjual == 'Yes')
                {
                    $this->rjual($datahitung);
                }
                if($stok == 'YES' || $stok == 'Yes')
                {
                    $this->stokopname($datahitung);
                }
                if($mutasi == 'YES' || $mutasi == 'Yes')
                {
                    $this->mutasi($datahitung);
                }
                if($pemakaian == 'YES' || $pemakaian == 'Yes')
                {
                    $this->pemakaian($datahitung);
                }
                if($hutang == 'YES' || $hutang == 'Yes')
                {
                    $this->hutang($datahitung);
                }
                if($bayarh == 'YES' || $bayarh == 'Yes')
                {
                    $this->bayarh($datahitung);
                }
                if($piutang == 'YES' || $piutang == 'Yes')
                {
                    $this->piutang($datahitung);
                }
                if($bayarp == 'YES' || $bayarp == 'Yes')
                {
                    $this->bayarp($datahitung);
                }
                if($kas == 'YES' || $kas == 'Yes')
                {
                    $this->kas($datahitung);
                }
                if($jurnal == 'YES' || $jurnal == 'Yes')
                {
                    $this->jurnal($datahitung);
                }
                if($saldo == 'YES' || $saldo == 'Yes')
                {
                    $this->saldo($datahitung);
                }
                if($tutupbk == 'YES' || $tutupbk == 'Yes')
                {
                    $this->tutupbk($datahitung);
                }  
                $this->response([
                            'status'    => 102,
                            'data'      => $datahitung,
                            'message'   => 'Sukses Menghitung Ulang' 
                        ], 200); 
            }
            else
            {
                $this->response([
                            'status'    => 104,
                            'data'      => [],
                            'message'   => 'Gagal Menghitung Ulang, Hak akses Dibatasi' 
                        ], 200);  
            }  
        }  
        $this->response([
                'status'  => 100,
                'data'    => [],
                'message' => 'User Tidak Ditemukan' 
            ], 200);
    }

    
    protected function permintaanbeli($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'permintaan_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_permintaan->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function pesananb($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'pesananb_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_pesananb->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function penerimaan($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'penerimaan_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_penerimaan->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function beli($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'beli_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_beli->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function rbeli($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'rbeli_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_rbeli->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function penawaran($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'penawaran_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_penawaran->updtotal($value['id']); 
        }
        return $datahitung;
    }

    protected function pesananj($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'pesananj_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_pesananj->updtotal($value['id']);

        }
        return $datahitung;
    }

    protected function pengiriman($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'pengiriman_h');
        foreach ($data as $key => $value) 
        { 
            $this->model_pengiriman->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function jual($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'jual_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_jual->updtotal($value['id']); 
        }
        return $datahitung;
    }

    protected function rjual($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'rjual_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_rjual->updtotal($value['id']);
            
        }
        return $datahitung;
    }

    protected function stokopname($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'stokopname_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_stokop->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function mutasi($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'mutasigudang_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_mutasi->updtotal($value['id']); 
        }
        return $datahitung;
    }

    protected function pemakaian($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'pemakaian_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_pemakaian->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function hutang($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang']; 
        $hutang  = $this->mylib->hitunghutang($periode, $cabang); 
        return $datahitung;
    }

    protected function bayarh($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'bayarh_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_bayarh->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function piutang($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang']; 
        $piutang = $this->mylib->hitungpiutang($periode, $cabang); 
        return $datahitung;
    }

    protected function bayarp($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'bayarp_h');
        foreach ($data as $key => $value) 
        {  
            $this->model_bayarp->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function kas($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        // masuk
        $datam    = $this->mylib->gettabel($periode, $cabang, 'trankasm_h');
        foreach ($datam as $key => $value) 
        {   
            $this->model_kasm->updtotal($value['id']); 
            $this->jurnal->jurnalkas($idh, 'KAS MASUK');              
        }
        // keluar
        $datak    = $this->mylib->gettabel($periode, $cabang, 'trankask_h');
        foreach ($datak as $key => $value) 
        {  
            $this->model_kask->updtotal($value['id']);
            $this->jurnal->jurnalkas($value['id'], 'KAS KELUAR'); 
        }
        return $datahitung;
    }

    protected function jurnal($datahitung)
    { 
        $periode = $datahitung['periode'];
        $cabang  = $datahitung['cabang'];
        $data    = $this->mylib->gettabel($periode, $cabang, 'jurnal_h');
        foreach ($data as $key => $value) 
        {   
            $this->model_jurnalp->updtotal($value['id']);
        }
        return $datahitung;
    }

    protected function saldo($datahitung)
    {  
        $this->mylib->hitungsaldo($datahitung); 
        return $datahitung;
    }    

    protected function tutupbk($datahitung)
    {  
        $this->mylib->newsaldostok($datahitung);         
        $this->bayarh($datahitung);
        $this->mylib->newhutang($datahitung); 
        $this->bayarp($datahitung);
        $this->mylib->newpiutang($datahitung); 
        $this->mylib->newsaldo($datahitung);     
        return $datahitung;
    }
    
    
    
    
    
    
    
    
    
    
     

    
}
