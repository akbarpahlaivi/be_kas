<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
 

class Printpreviewfaktur extends CI_Controller { 
   

  function __construct(){
    parent::__construct();  
    $this->load->model('mylib');  
    $this->load->model('model_kask');  
    $this->load->model('model_kasm');  
    $this->load->library('pdf_mc_table'); 
    $this->load->library('pdf');
    date_default_timezone_set('Asia/Jakarta');   
  } 
  

  public function cetak($token = NULL)
  {  

      if(!empty($token))
      {
          $request  = $this->mylib->cekrequest($token);      
          if(!empty($request))
          { 
              $this->jenisfaktur($request['jenis'], $request['idh']);   
          }
          $this->jenisfaktur('default', 0);  
      }
      else
      {
          $this->jenisfaktur('default', 0);  
      }  
  }

  public function jenisfaktur($jenis, $vid)
  { 
      if($jenis == 'KAS KELUAR')
      {
          $this->kaskeluar($vid);
      }
      elseif ($jenis == 'RECEIPT KAS KELUAR') 
      {
        $this->receiptkaskeluar($vid);  
      }
      elseif ($jenis == 'KAS MASUK') 
      {
          $this->kasmasuk($vid);
      } 
      elseif ($jenis == 'RECEIPT KAS MASUK') 
      {
        $this->receiptkasmasuk($vid); 
      }
  }

  private function kaskeluar($vid)
  {
      $data = $this->model_kask->datakask($vid);
      $ofc  = end($data);
      $bts  = 8;
      $lmt  = 0;
      $jdta = count($data);

      if ($jdta >= 8)
      {
          $hasil = $jdta / $bts; //div             
          $sisa  = $jdta % $bts; //mod    
          if($sisa > 0)
          {
            $hasil = ((int)$hasil + 1); 
          }
          $pdf   = new FPDF('L','mm','A5'); 
          $pdf->SetMargins(7, 7, 7);
                  
          for($i =1; $i <= $hasil ; $i++ )
          {
            $pdf->AddPage();        
            //header
            $pdf->SetFont('Arial','',11);        
            $pdf->Cell(195,4.5,$ofc['cabang'],0,1,'L');
            $pdf->Cell(195,4.5,$ofc['alamat'],0,1,'L');
            $pdf->Cell(195,4.5,$ofc['kota'],0,1,'L');  
            $pdf->Cell(195,5,"",0,1,'C');   

            $pdf->SetLineWidth(0.3);
            $pdf->Line(7,31,202,31); 
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(195,6,'BUKTI KAS KELUAR',0,1,'C');
            
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(35,5,'Nobukti',0,0); 
            $pdf->Cell(5,5,':',0,0); 
            $pdf->Cell(155,5,$ofc['nobukti'],0,1,'L');   
     
            $pdf->Cell(35,5,'Tanggal',0,0); 
            $pdf->Cell(5,5,':',0,0);  
            $pdf->Cell(155,5,date("d-m-Y", strtotime($ofc['tgl'])),0,1,'L');    

            $pdf->Cell(35,5,'Nama Akun',0,0); 
            $pdf->Cell(5,5,':',0,0); 
            $pdf->Cell(155,5,$ofc['kodebank'] ." - ". $ofc['namabank']  ,0,1,'L');    
     
            $pdf->Cell(195,7,"",0,1,'L'); 
            $pdf->SetLineWidth(0.3);
            $pdf->Line(7,53,202,53);   
            $pdf->Cell(25,5,"No Akun",0,0,'C'); 
            $pdf->Cell(65,5,"Nama Akun",0,0,'C');
            $pdf->Cell(75,5,"Keterangan",0,0,'C');
            $pdf->Cell(25,5,"Total",0,1,'R');                    
            $pdf->SetLineWidth(0.3);
            $pdf->Line(7,58,202,58); 
            // looping isi   
            $datad   = array_slice($data, $lmt, $bts);
            $idata   = count($datad);  
            if($idata < 8){ 
              foreach ($datad as $d ) 
              {
                $norek      = $d['nomor'];
                $nama       = $d['namaperkiraan'];
                $keterangan = $d['keterangan'];
                $debet      = number_format($d['debet'],2,',','.'); 

                $pdf->Cell(25,5,$norek,0,0,'L');   
                $pdf->Cell(65,5,$nama,0,0,'L');
                $pdf->Cell(75,5,$keterangan,0,0,'L');
                $pdf->Cell(25,5,$debet,0,1,'R'); 
              }

              $x       = (8 - (int)$idata);
              for($ib =1; $ib <= $x ; $ib++ ){
                  $pdf->Cell(195,5,"",0,1,'C');
              } 
            }
            else
            {
              foreach ($datad as $d ) 
              {
                $norek      = $d['nomor'];
                $nama       = $d['namaperkiraan'];
                $keterangan = $d['keterangan'];
                $debet      = number_format($d['debet'],2,',','.'); 

                $pdf->Cell(25,5,$norek,0,0,'L');   
                $pdf->Cell(65,5,$nama,0,0,'L');
                $pdf->Cell(75,5,$keterangan,0,0,'L');
                $pdf->Cell(25,5,$debet,0,1,'R');   
              }  
            }           
            // footer
            $pdf->SetFont('Arial','',10);    
            $pdf->SetLineWidth(0.3);
            $pdf->Line(7,98,202,98);          
            $pdf->Cell(165,5,"Total",0,0,'L'); 
            $pdf->Cell(25,5,number_format($ofc['totaldebet'],2,',','.'),0,1,'R');   
           
            $pdf->Cell(195,3,"",0,1,'C');
            $pdf->Cell(70,5,"Hormat Kami",0,0,'C');
            $pdf->Cell(55,5,"",0,0,'C');
            $pdf->Cell(70,5,"Yang Menerima",0,1,'C');     
            $pdf->Ln(10);
            $pdf->Cell(70,0,$ofc['username'],0,1,'C'); 
            $pdf->Cell(70,5,"(................................)",0,0,'C');
            $pdf->Cell(55,5,"",0,0,'C');
            $pdf->Cell(70,5,"(................................)",0,1,'C'); 

            $lmt          = ((int)$lmt + 8); 
          }
      }
      else
      {
          $pdf            = new FPDF('L','mm','A5'); 
          $pdf->SetMargins(7, 7, 7);
          $pdf->AddPage();        
          //header
          $pdf->SetFont('Arial','',11);        
          $pdf->Cell(195,4.5,$ofc['cabang'],0,1,'L');
          $pdf->Cell(195,4.5,$ofc['alamat'],0,1,'L');
          $pdf->Cell(195,4.5,$ofc['kota'],0,1,'L');  
          $pdf->Cell(195,5,"",0,1,'C');       

          $pdf->SetLineWidth(0.3);
          $pdf->Line(7,31,202,31); 
          $pdf->SetFont('Arial','B',11);                    
          $pdf->Cell(195,6,'BUKTI KAS KELUAR',0,1,'C');
          $pdf->SetFont('Arial','',9);
          $pdf->Cell(35,5,'Nobukti',0,0); 
          $pdf->Cell(5,5,':',0,0); 
          $pdf->Cell(155,5,$ofc['nobukti'],0,1,'L');   

          $pdf->Cell(35,5,'Tanggal',0,0); 
          $pdf->Cell(5,5,':',0,0); 
          $pdf->Cell(155,5,date("d-m-Y", strtotime($ofc['tgl'])),0,1,'L');    

          $pdf->Cell(35,5,'Nama Akun',0,0); 
          $pdf->Cell(5,5,':',0,0); 
          $pdf->Cell(155,5,$ofc['kodebank'] ." - ". $ofc['namabank']  ,0,1,'L');    

          $pdf->Cell(195,7,"",0,1,'L'); 
          $pdf->SetLineWidth(0.3);
          $pdf->Line(7,53,202,53);   
          $pdf->Cell(25,5,"No Akun",0,0,'C'); 
          $pdf->Cell(65,5,"Nama Akun",0,0,'C');
          $pdf->Cell(75,5,"Keterangan",0,0,'C');
          $pdf->Cell(25,5,"Total",0,1,'R'); 
          $pdf->SetLineWidth(0.3);
          $pdf->Line(7,58,202,58); 
          // looping isi  
          $datad   = array_slice($data, $lmt, $bts);
          $idata   = count($datad);  

          if($idata < 8)
          { 
            foreach ($datad as $d ) 
            {
              $norek      = $d['nomor'];
              $nama       = $d['namaperkiraan'];
              $keterangan = $d['keterangan'];
              $debet      = number_format($d['debet'],2,',','.'); 

              $pdf->Cell(25,5,$norek,0,0,'L');   
              $pdf->Cell(65,5,$nama,0,0,'L');
              $pdf->Cell(75,5,$keterangan,0,0,'L');
              $pdf->Cell(25,5,$debet,0,1,'R'); 
            }
            $x       = (8- (int)$idata);
            for($ib =1; $ib <= $x ; $ib++ ){
                $pdf->Cell(195,5,"",0,1,'C');                            
            } 
          }
          else
          {

            foreach ($datad as $d ) 
            {
              $norek      = $d['nomor'];
              $nama       = $d['namaperkiraan'];
              $keterangan = $d['keterangan'];
              $debet      = number_format($d['debet'],2,',','.'); 

              $pdf->Cell(25,5,$norek,0,0,'L');   
              $pdf->Cell(65,5,$nama,0,0,'L');
              $pdf->Cell(75,5,$keterangan,0,0,'L');
              $pdf->Cell(25,5,$debet,0,1,'R');   
            }  
          }
          // footer
          $pdf->SetFont('Arial','',10);    
          $pdf->SetLineWidth(0.3);
          $pdf->Line(7,98,202,98);          
          $pdf->Cell(165,5,"Total",0,0,'L'); 
          $pdf->Cell(25,5,number_format($ofc['totaldebet'],2,',','.'),0,1,'R');   
         
          $pdf->Cell(195,3,"",0,1,'C');
          $pdf->Cell(70,5,"Hormat Kami",0,0,'C');
          $pdf->Cell(55,5,"",0,0,'C');
          $pdf->Cell(70,5,"Yang Menerima",0,1,'C');     
          $pdf->Ln(10);
          $pdf->Cell(70,0,$ofc['username'],0,1,'C'); 
          $pdf->Cell(70,5,"(................................)",0,0,'C');
          $pdf->Cell(55,5,"",0,0,'C');
          $pdf->Cell(70,5,"(................................)",0,1,'C'); 
      }
      $pdf->Output();
  }

  private function receiptkaskeluar($vid)
  {
     
      $nobukti      = "";
      $tgl          = "";
      $bank         = "";
      $total        = 0;
      $it           = date("Y-m-d H:i:s");
      $sett         = $this->mylib->getsetting(); 
      $merchantname = $sett['nama']; 
      $merchantaddr = $sett['alamat']; 
      $merchanttelp = $sett['telp']; 

      $detail       = $this->model_kask->datakask($vid); 
      $header       = $this->model_kask->getdatah($vid); 
      if(!empty($header))
      {
          $nobukti= $header['nobukti'];
          $tgl    = $header['tgl'];
          $bank   = $header['namabank'];
          $total  = $header['totaldebet']; 
          $it     = $header['it']; 
      }
      $data   = [
                  'namatoko' => $merchantname,   
                  'alamat'   => $merchantaddr,
                  'telp'     => $merchanttelp,
                  'nobukti'  => $nobukti,
                  'tgl'      => $tgl,
                  'it'       => $it,
                  'namabank' => $bank,  
                  'total'    => number_format($total,2,",","."),  
                  'detail'   => $detail 
              ];  
      $this->load->view('nota/kask', $data);     
  }

  private function kasmasuk($vid)
  {
    $data = $this->model_kasm->datakasm($vid);
    $ofc  = end($data);
    $bts  = 8;
    $lmt  = 0;
    $jdta = count($data);

    if ($jdta >= 8)
    {
      $hasil = $jdta / $bts; //div             
      $sisa  = $jdta % $bts; //mod    
      if($sisa > 0)
      {
        $hasil = ((int)$hasil + 1); 
      }
      $pdf   = new FPDF('L','mm','A5'); 
      $pdf->SetMargins(7, 7, 7);
                
      for($i =1; $i <= $hasil ; $i++ )
      {
        $pdf->AddPage();        
        //header
        $pdf->SetFont('Arial','',11);        
        $pdf->Cell(195,4.5,$ofc['cabang'],0,1,'L');
        $pdf->Cell(195,4.5,$ofc['alamat'],0,1,'L');
        $pdf->Cell(195,4.5,$ofc['kota'],0,1,'L');  
        $pdf->Cell(195,5,"",0,1,'C');   

        $pdf->SetLineWidth(0.3);
        $pdf->Line(7,31,202,31); 
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,'BUKTI KAS MASUK',0,1,'C');
        
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(35,5,'Nobukti',0,0); 
        $pdf->Cell(5,5,':',0,0); 
        $pdf->Cell(155,5,$ofc['nobukti'],0,1,'L');   
 
        $pdf->Cell(35,5,'Tanggal',0,0); 
        $pdf->Cell(5,5,':',0,0);  
        $pdf->Cell(155,5,date("d-m-Y", strtotime($ofc['tgl'])),0,1,'L');    

        $pdf->Cell(35,5,'Nama Akun',0,0); 
        $pdf->Cell(5,5,':',0,0); 
        $pdf->Cell(155,5,$ofc['kodebank'] ." - ". $ofc['namabank']  ,0,1,'L');    
 
        $pdf->Cell(195,7,"",0,1,'L'); 
        $pdf->SetLineWidth(0.3);
        $pdf->Line(7,53,202,53);   
        $pdf->Cell(25,5,"No Akun",0,0,'C'); 
        $pdf->Cell(65,5,"Nama Akun",0,0,'C');
        $pdf->Cell(75,5,"Keterangan",0,0,'C');
        $pdf->Cell(25,5,"Total",0,1,'R');                    
        $pdf->SetLineWidth(0.3);
        $pdf->Line(7,58,202,58); 
        // looping isi   
        $datad   = array_slice($data, $lmt, $bts);
        $idata   = count($datad);  
        if($idata < 8){ 
          foreach ($datad as $d ) 
          {
            $norek      = $d['nomor'];
            $nama       = $d['namaperkiraan'];
            $keterangan = $d['keterangan'];
            $kredit      = number_format($d['kredit'],2,',','.'); 

            $pdf->Cell(25,5,$norek,0,0,'L');   
            $pdf->Cell(65,5,$nama,0,0,'L');
            $pdf->Cell(75,5,$keterangan,0,0,'L');
            $pdf->Cell(25,5,$kredit,0,1,'R'); 
          }

          $x       = (8 - (int)$idata);
          for($ib =1; $ib <= $x ; $ib++ ){
              $pdf->Cell(195,5,"",0,1,'C');
          } 
        }
        else
        {
          foreach ($datad as $d ) 
          {
            $norek      = $d['nomor'];
            $nama       = $d['namaperkiraan'];
            $keterangan = $d['keterangan'];
            $kredit      = number_format($d['kredit'],2,',','.'); 

            $pdf->Cell(25,5,$norek,0,0,'L');   
            $pdf->Cell(65,5,$nama,0,0,'L');
            $pdf->Cell(75,5,$keterangan,0,0,'L');
            $pdf->Cell(25,5,$kredit,0,1,'R');   
          }  
        }           
        // footer
        $pdf->SetFont('Arial','',10);    
        $pdf->SetLineWidth(0.3);
        $pdf->Line(7,98,202,98);          
        $pdf->Cell(165,5,"Total",0,0,'L'); 
        $pdf->Cell(25,5,number_format($ofc['totalkredit'],2,',','.'),0,1,'R');   
       
        $pdf->Cell(195,3,"",0,1,'C');
        $pdf->Cell(70,5,"Hormat Kami",0,0,'C');
        $pdf->Cell(55,5,"",0,0,'C');
        $pdf->Cell(70,5,"Yang Menerima",0,1,'C');     
        $pdf->Ln(10);
        $pdf->Cell(70,0,$ofc['username'],0,1,'C'); 
        $pdf->Cell(70,5,"(................................)",0,0,'C');
        $pdf->Cell(55,5,"",0,0,'C');
        $pdf->Cell(70,5,"(................................)",0,1,'C'); 

        $lmt          = ((int)$lmt + 8); 
      }
    }
    else
    {
      $pdf            = new FPDF('L','mm','A5'); 
      $pdf->SetMargins(7, 7, 7);
      $pdf->AddPage();        
      //header
      $pdf->SetFont('Arial','',11);        
      $pdf->Cell(195,4.5,$ofc['cabang'],0,1,'L');
      $pdf->Cell(195,4.5,$ofc['alamat'],0,1,'L');
      $pdf->Cell(195,4.5,$ofc['kota'],0,1,'L');  
      $pdf->Cell(195,5,"",0,1,'C');       

      $pdf->SetLineWidth(0.3);
      $pdf->Line(7,31,202,31); 
      $pdf->SetFont('Arial','B',11);                    
      $pdf->Cell(195,6,'BUKTI KAS MASUK',0,1,'C');
      $pdf->SetFont('Arial','',9);
      $pdf->Cell(35,5,'Nobukti',0,0); 
      $pdf->Cell(5,5,':',0,0); 
      $pdf->Cell(155,5,$ofc['nobukti'],0,1,'L');   

      $pdf->Cell(35,5,'Tanggal',0,0); 
      $pdf->Cell(5,5,':',0,0); 
      $pdf->Cell(155,5,date("d-m-Y", strtotime($ofc['tgl'])),0,1,'L');    

      $pdf->Cell(35,5,'Nama Akun',0,0); 
      $pdf->Cell(5,5,':',0,0); 
      $pdf->Cell(155,5,$ofc['kodebank'] ." - ". $ofc['namabank']  ,0,1,'L');    

      $pdf->Cell(195,7,"",0,1,'L'); 
      $pdf->SetLineWidth(0.3);
      $pdf->Line(7,53,202,53);   
      $pdf->Cell(25,5,"No Akun",0,0,'C'); 
      $pdf->Cell(65,5,"Nama Akun",0,0,'C');
      $pdf->Cell(75,5,"Keterangan",0,0,'C');
      $pdf->Cell(25,5,"Total",0,1,'R'); 
      $pdf->SetLineWidth(0.3);
      $pdf->Line(7,58,202,58); 
      // looping isi  
      $datad   = array_slice($data, $lmt, $bts);
      $idata   = count($datad);  

      if($idata < 8)
      { 
        foreach ($datad as $d ) 
        {
          $norek      = $d['nomor'];
          $nama       = $d['namaperkiraan'];
          $keterangan = $d['keterangan'];
          $kredit     = number_format($d['kredit'],2,',','.'); 

          $pdf->Cell(25,5,$norek,0,0,'L');   
          $pdf->Cell(65,5,$nama,0,0,'L');
          $pdf->Cell(75,5,$keterangan,0,0,'L');
          $pdf->Cell(25,5,$kredit,0,1,'R'); 
        }
        $x       = (8- (int)$idata);
        for($ib =1; $ib <= $x ; $ib++ ){
            $pdf->Cell(195,5,"",0,1,'C');                            
        } 
      }
      else
      {

        foreach ($datad as $d ) 
        {
          $norek      = $d['nomor'];
          $nama       = $d['namaperkiraan'];
          $keterangan = $d['keterangan'];
          $kredit     = number_format($d['kredit'],2,',','.'); 

          $pdf->Cell(25,5,$norek,0,0,'L');   
          $pdf->Cell(65,5,$nama,0,0,'L');
          $pdf->Cell(75,5,$keterangan,0,0,'L');
          $pdf->Cell(25,5,$kredit,0,1,'R');   
        }  
      }
      // footer
      $pdf->SetFont('Arial','',10);    
      $pdf->SetLineWidth(0.3);
      $pdf->Line(7,98,202,98);          
      $pdf->Cell(165,5,"Total",0,0,'L'); 
      $pdf->Cell(25,5,number_format($ofc['totalkredit'],2,',','.'),0,1,'R');   
     
      $pdf->Cell(195,3,"",0,1,'C');
      $pdf->Cell(70,5,"Hormat Kami",0,0,'C');
      $pdf->Cell(55,5,"",0,0,'C');
      $pdf->Cell(70,5,"Yang Menerima",0,1,'C');     
      $pdf->Ln(10);
      $pdf->Cell(70,0,$ofc['username'],0,1,'C'); 
      $pdf->Cell(70,5,"(................................)",0,0,'C');
      $pdf->Cell(55,5,"",0,0,'C');
      $pdf->Cell(70,5,"(................................)",0,1,'C'); 
    }
    $pdf->Output();
  }

  private function receiptkasmasuk($vid)
  {
     
      $nobukti      = "";
      $tgl          = "";
      $bank         = "";
      $total        = 0;
      $it           = date("Y-m-d H:i:s");
      $sett         = $this->mylib->getsetting(); 
      $merchantname = $sett['nama']; 
      $merchantaddr = $sett['alamat']; 
      $merchanttelp = $sett['telp']; 

      $detail       = $this->model_kasm->datakasm($vid); 
      $header       = $this->model_kasm->getdatah($vid); 
      if(!empty($header))
      {
          $nobukti= $header['nobukti'];
          $tgl    = $header['tgl'];
          $bank   = $header['namabank'];
          $total  = $header['totalkredit']; 
          $it     = $header['it']; 
      }
      $data   = [
                  'namatoko' => $merchantname,   
                  'alamat'   => $merchantaddr,
                  'telp'     => $merchanttelp,
                  'nobukti'  => $nobukti,
                  'tgl'      => $tgl,
                  'it'       => $it,
                  'namabank' => $bank,  
                  'total'    => number_format($total,2,",","."),  
                  'detail'   => $detail 
              ];  
      $this->load->view('nota/kasm', $data);     
  }

  private function cetakdefault()
  {
      $pdf       = new PDF_MC_Table('L','mm','A5');
      $pdf->SetMargins(15, 15, 15);
      // membuat halaman baru
      $pdf->AddPage();        
      //kop
      $pdf->SetFont('Arial','',12);
      $pdf->Ln(40);
      $pdf->Cell(180,5,"Support By :",0,1,'C'); 
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(180,10,"AKBAR",0,1,'C'); 
      $pdf->Output();
  }


  private function array_orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
  }
 

}
