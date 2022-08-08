<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
 

class Printpreviewlapkeu extends CI_Controller { 
   

  function __construct(){
      parent::__construct();  
      $this->load->model('mylib');  
      $this->load->model('lapkeu');   
      $this->load->model('model_perkiraan');  
      $this->load->model('model_jurnalumum');  
      $this->load->model('p_saldo'); 
      $this->load->library('pdf_mc_table'); 
      $this->load->library('pdf');
      date_default_timezone_set('Asia/Jakarta');   
  } 
  

  public function cetak($token = NULL)
  {   
      if(!empty($token))
      {
          $req        = $this->mylib->cekrequestlapkeu($token);   
          if(!empty($req))
          {  

              $this->p_saldo->psaldo($req['periode']); 
              $this->jenisfaktur($req['jenis'], array('periode'  => $req['periode'], 
                                                      'pfilter1' => 'NO',
                                                      'pfilter2' => $req['pfilter2'],
                                                      'pfilter3' => $req['pfilter3'],
                                                      'pfilter4' => $req['pfilter4']
              )); 
          }
          $this->jenisfaktur('default', 0); 
      }
      else
      {
          $this->jenisfaktur('default', 0); 
      } 
  }

  private function jenisfaktur($jenis, $data)
  { 
      if ($jenis == 'Laba Rugi') 
      { 
          $out = $this->laprugilaba($data);
          $this->cetakrugilaba($out, $data); 
      }
      elseif ($jenis == 'Buku Besar') 
      { 
          $out = $this->lapbukubesar($data);
          $this->cetakbukubesar($out, $data); 
      }
      elseif ($jenis == 'Jurnal Umum') 
      { 
          $out = $this->lapjurnalumum($data);
          $this->cetakjurnalumum($out, $data); 
      }   
  } 

  private function astext($desc, $count)
  {
      $text = "";
      // for ($count=0; $count < ; $count++) 
      for ($x = 0; $x <= $count; $x++) 
      { 
        if($x > 1)
        {
          $text = $text."   ";  
        } 
      }
      return $text.$desc;   
  } 

  /* RUGI LABA */
  protected function laprugilaba($filter)
  { 
      $periode        = $filter['periode']; 
      $semua          = $filter['pfilter1']; 
      
      
      $nourut         = 0;
      $vnoheader1     = 0; 
      $vnoheader2     = 0;
      $vnoheader3     = 0;
      $vnoheader4     = 0;
      $posisidk1      = null; 
      $posisidk2      = null;
      $posisidk3      = null;
      $posisidk4      = null;
      $judul          = null;
      $totalsaldo     = 0;
      $saldoakhir     = 0;
      $rugi1          = $this->lapkeu->rugilaba('JUDUL', 1);
      $laprugilaba    = []; 

      foreach ($rugi1 as $key => $value) 
      {
        $nourut         = $nourut + 1000;
        $id1            = $value['id'];
        $vnoheader1     = $value['nomor'];
        $posisidk1      = $value['posisidk']; 
        $judul          = $value['nama'];
        $laprugilaba [] = [
                            'id'            => $value['id'],
                            'judul'         => $judul,
                            'nourut'        => $nourut,
                            'norek'         => $value['nomor'],
                            'nama'          => $value['nama'],
                            'saldoakhir'    => 0,
                            'saldoakhird'   => 0,
                            'saldoakhirk'   => 0,
                            'levelno'       => $value['levelno'],
                            'posisidk1'     => $posisidk1,
                            'posisidk2'     => $posisidk2,
                            'posisidk3'     => $posisidk3,
                            'posisidk4'     => $posisidk4,
                            'keterangan'    => $value['keterangan'],
                            'totalsaldo'    => 0 
                        ];
        $rugi2      = $this->lapkeu->rugilaba('JUDUL', 2);   
        $rugi2      = array_filter($rugi2, function($value) use ($id1){
            return $value['nomorheader']==$id1;
        });

        foreach ($rugi2 as $key => $value) 
        {
          $nourut         = $nourut + 100;
          $id2            = $value['id'];
          $vnoheader2     = $value['nomor'];
          $posisidk2      = $value['posisidk']; 
          $laprugilaba [] = [
                              'id'            => $value['id'],
                              'judul'         => $judul,
                              'nourut'        => $nourut,
                              'norek'         => $value['nomor'],
                              'nama'          => $value['nama'],
                              'saldoakhir'    => 0,
                              'saldoakhird'   => 0,
                              'saldoakhirk'   => 0,
                              'levelno'       => $value['levelno'],
                              'posisidk1'     => $posisidk1,
                              'posisidk2'     => $posisidk2,
                              'posisidk3'     => $posisidk3,
                              'posisidk4'     => $posisidk4,
                              'keterangan'    => $value['keterangan'],
                              'totalsaldo'    => 0 
                          ];
          $rugi3      = $this->lapkeu->rugilaba('JUDUL', 3); 
          $rugi3      = array_filter($rugi3, function($value) use ($id2){
              return $value['nomorheader']==$id2;
          });
            
            foreach ($rugi3 as $key => $value) 
            {
              $nourut         = $nourut + 10;
              $id3            = $value['id'];
              $vnoheader3     = $value['nomor'];
              $posisidk3      = $value['posisidk'];
              $laprugilaba [] = [
                          'id'            => $value['id'],
                          'judul'         => $judul,
                          'nourut'        => $nourut,
                          'norek'         => $value['nomor'],
                          'nama'          => $value['nama'],
                          'saldoakhir'    => 0,
                          'saldoakhird'   => 0,
                          'saldoakhirk'   => 0,
                          'levelno'       => $value['levelno'],
                          'posisidk1'     => $posisidk1,
                          'posisidk2'     => $posisidk2,
                          'posisidk3'     => $posisidk3,
                          'posisidk4'     => $posisidk4,
                          'keterangan'    => $value['keterangan'],
                          'totalsaldo'    => 0 
                      ];

              $rugi4      = $this->lapkeu->rugilaba('DETIL', 4); 
              $rugi4      = array_filter($rugi4, function($value) use ($id3){
                  return $value['nomorheader']==$id3;
              });

              foreach ($rugi4 as $key => $value)
              {
                $nourut         = $nourut + 10;  
                $posisidk4      = $value['posisidk'];
                $norek          = $value['nomor']; 
                $id             = $value['id'];

                // isi
                $saldoakhird    = 0;
                $saldoakhirk    = 0;
                $psaldo         = $this->lapkeu->saldonorek($periode, $id);   
                $saldoakhird    = $psaldo['debet'];
                $saldoakhirk    = $psaldo['kredit'];
                if(empty($saldoakhird)){$saldoakhird = 0;}
                if(empty($saldoakhirk)){$saldoakhirk = 0;}
                
                if($posisidk4=='DEBET')
                {
                  $saldoakhir = - $saldoakhird;
                  if($saldoakhir >= 0)
                  {
                      $totalsaldo = $saldoakhir;
                  }
                  else
                  {
                      $totalsaldo = - $saldoakhir;
                  }
                  $saldoakhirk = null;
                }
                elseif($posisidk4=='KREDIT')
                {
                  $saldoakhir = $saldoakhirk;
                  if($saldoakhir >= 0)
                  {
                      $totalsaldo = $saldoakhir;
                  }
                  else
                  {
                      $totalsaldo = - $saldoakhir;
                  }
                  $saldoakhirk = null;  
                }
                 
                if($totalsaldo <> 0 || $semua=='YES')
                {
                  $laprugilaba [] = [
                              'id'            => $value['id'],
                              'judul'         => $judul,
                              'nourut'        => $nourut,
                              'norek'         => $norek,
                              'nama'          => $value['nama'],
                              'saldoakhir'    => $saldoakhir,
                              'saldoakhird'   => $saldoakhird,
                              'saldoakhirk'   => $saldoakhirk,
                              'levelno'       => $value['levelno'],
                              'posisidk1'     => $posisidk1,
                              'posisidk2'     => $posisidk2,
                              'posisidk3'     => $posisidk3,
                              'posisidk4'     => $posisidk4,
                              'keterangan'    => $value['keterangan'],
                              'totalsaldo'    => $totalsaldo 
                          ];
                } 
                $saldoakhird    = 0;
                $saldoakhirk    = 0;
                $saldoakhir     = 0;
                $totalsaldo     = 0; 
              } 
            } 
        } 
      }    
       
      $pfilter1  = $filter['pfilter1'];  
      $pfilter2  = $filter['pfilter2'];  
      $pfilter3  = $this->filter($filter['pfilter3']);  
      $pfilter4  = $filter['pfilter4'];   

      if(!empty($pfilter2) && !empty($pfilter3) && !empty($pfilter4))
      { 
          $paramakhir     = $pfilter4; 
          $filter         = $pfilter2;     
          switch ($pfilter3) 
          {
              case "==": 
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]==$paramakhir;
                  });  
                  break;
              case "<>":
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<>$paramakhir;
                  });  
                  break;
              case ">":
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>$paramakhir;
                  });  
                  break;
              case "<":
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<$paramakhir;
                  });  
                  break;
              case ">=":
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>=$paramakhir;
                  });  
                  break;
              case "=<":
                  $laprugilaba  = array_filter($laprugilaba, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<=$paramakhir;
                  });  
                  break; 
          }  
      } 
      return $laprugilaba;
  }

  protected function cetakrugilaba($data, $filter)
  {
      $sett    = $this->mylib->getsetting(); 
      $hldg    = $sett['nama']; 
      $hldgaddr= $sett['alamat']; 
   
      $periode  = $filter['periode'];

      $total    = 0; 
      $totald   = 0;
      $totalk   = 0; 

      /* di group kan sesuai dengan judul */
      $result = array();
      foreach ($data as $element) 
      {
          $result[$element['judul']][] = $element;
      }  
      $result_key = array_keys($result);
      $group = [];
      foreach ($result_key as $key => $value) 
      {
          $group [] = ['judul'     => $value];            
      } 

      $pdf       = new FPDF('P','mm','A4'); 
      $pdf->AliasNbPages();
      // membuat halaman baru
      $pdf->AddPage();        
      $pdf->SetFont('Arial','B',11);         
      $pdf->Cell(190,5,$hldg,0,1,'C');      
      $pdf->SetTextColor(255,0,0); 
      $pdf->Cell(190,5, 'LABA RUGI',0,1,'C'); 
      $pdf->SetTextColor(0,0,0);
      $pdf->SetFont('Arial','',10);     
      $pdf->Cell(190,5, 'Periode '. substr($periode, 0,4 ).' - '.substr($periode, 4,6 ),0,1,'C');  
     
      $pdf->SetLineWidth(0.3);
      $pdf->Line(10,28,200,28); 
      $pdf->ln(10);    

      foreach ($group as $key => $g) 
      { 
          $judul      = $g['judul'];
          $totalgroup = 0;
          $subgroup   = 0;
          $pdf->SetFont('Arial','BU',10);  
          $pdf->SetTextColor(0,0,245); 
          $pdf->Cell(20,5, $g['judul'],0,1,'L'); 
          $rugilaba   = array_filter($data, function($value) use($judul){
                      return $value['judul']==$judul;
                  });
          $sorted         = $this->array_orderby($rugilaba, 'nourut', SORT_ASC );  

          foreach ($sorted as $key => $d)
          { 
              $pdf->SetFont('Arial','',9);  
              $pdf->SetTextColor(0,0,0); 
              $posisidk = $d['posisidk1'];
              $level    = $d['levelno'];
              $nama     = $this->astext($d['nama'], $level);

              if($level != 4)
              {
                 $pdf->SetFont('Arial','B',9);  
              }
              

              // $saldo    = $d['totalsaldo'];
              $saldo    = $d['saldoakhir'];
              $pdf->Cell(5,5,'',0,0,'L');  
              $pdf->Cell(82,5, $nama,0,0,'L');    //$d['nama']
              $pdf->Cell(33,5, number_format( $d['saldoakhir'],2,',','.'),0,1,'R');  
              $subgroup   = $subgroup + $d['totalsaldo'];  
              $total      = $total + $d['saldoakhir'];   
          }
          $pdf->SetFont('Arial','BU',10);  
          // $pdf->SetTextColor(0,0,245); 
          $pdf->Cell(30,7,'',0,0,'L');  
          $pdf->Cell(90,7, $judul,0,0,'L');  
          $pdf->Cell(50,7, number_format($subgroup,2,',','.'),0,1,'R');  
      }  
      $pdf->Cell(30,7,'',0,0,'C');  
      $pdf->Cell(90,7, 'LABA / RUGI BERSIH',0,0,'L');    
      $pdf->Cell(50,5, number_format($total,2,',','.'),0,0,'R');         
      $pdf->Output();  
  }

  /* BUKU BESAR */
  protected function lapbukubesar($filter)
  {
      $periode = $filter['periode']; 

      $thn     = substr($periode, 0,4);
      $bln     = substr($periode, 5,6);
      $hr      = '01';
      $awalbln = strtotime($hr.'-'. $bln . '-' .  $thn);
        // $awalbln = date('d-m-Y',$awalbln); 

      $p4      = $this->model_perkiraan->getakunpos(4); 
      $bukubesar = [];
      foreach ($p4 as $key => $value) 
      {
          $id         = $value['id'];
          $norek      = $value['nomor'];
          $nama       = $value['nama'];
          $posisidk   = $value['posisidk']; 
          $saldo      = 0;
          $vsaldo     = $this->lapkeu->saldonorek($periode, $id); 
          $saldo      = $vsaldo['saldoawal'];
          if(empty($saldo))
          {
              $saldo  = 0;
          }
          $debet      = 0;
          $kredit     = 0;
          $keterangan = "SALDO AWAL...";
          $nobukti    = "AWAL";
          $tgl        = date('d-m-Y',$awalbln);

          $bukubesar[] = [
                          'id'            => $id,
                          'norek'         => $norek,
                          'nama'          => $nama,
                          'tgl'           => $tgl,
                          'nobukti'       => $nobukti,
                          'keterangan'    => $keterangan,
                          'debet'         => $debet,
                          'kredit'        => $kredit,
                          'saldo'         => $saldo,
                          'posisidk'      => $posisidk];  
            
          $bukubesartmp = $this->bukubesartmp($periode, $id, $posisidk);
          $sorted       = $this->array_orderby($bukubesartmp, 'tgl', SORT_ASC ); 

          foreach ($sorted as $key => $s) 
          { 
              $tgl        = strtotime($s['tgl']);
              $tgl        = date('d-m-Y', $tgl);
              $nobukti    = $s['nobukti'];
              $keterangan = $s['keterangan'];
              $debet      = $s['debet'];
              $kredit     = $s['kredit'];
              if(empty($debet)){$debet = 0;}
              if(empty($kredit)){$kredit = 0;}
              
              if($kredit > $debet)
              {
                  $kredit = $kredit - $debet;
                  $debet  = 0;
              }
              elseif($kredit < $debet)
              {
                  $debet  = $debet - $kredit;
                  $kredit = 0;
              }
       
              if($posisidk=='DEBET')
              {
                  $saldo = $saldo + $debet - $kredit;
              }
              else
              {
                  $saldo = $saldo - $debet + $kredit;
              } 

              $bukubesar[] = [
                          'id'            => $id,
                          'norek'         => $norek,
                          'nama'          => $nama,
                          'tgl'           => $tgl,
                          'nobukti'       => $nobukti,
                          'keterangan'    => $keterangan,
                          'debet'         => $debet,
                          'kredit'        => $kredit,
                          'saldo'         => $saldo,
                          'posisidk'      => $posisidk]; 
          }  
      }

      $pfilter1  = $filter['pfilter1'];  
      $pfilter2  = $filter['pfilter2'];  
      $pfilter3  = $this->filter($filter['pfilter3']);  
      $pfilter4  = $filter['pfilter4'];   

      if(!empty($pfilter2) && !empty($pfilter3) && !empty($pfilter4))
      { 
          $paramakhir     = $pfilter4; 
          $filter         = $pfilter2;     
          switch ($pfilter3) 
          {
              case "==": 
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]==$paramakhir;
                  });  
                  break;
              case "<>":
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<>$paramakhir;
                  });  
                  break;
              case ">":
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>$paramakhir;
                  });  
                  break;
              case "<":
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<$paramakhir;
                  });  
                  break;
              case ">=":
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>=$paramakhir;
                  });  
                  break;
              case "=<":
                  $bukubesar  = array_filter($bukubesar, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<=$paramakhir;
                  });  
                  break; 
          }  
      }     
      return $bukubesar; 
  }

  protected function bukubesartmp($periode, $norek, $posisidk)
  { 
      $data           = $this->lapkeu->jurnal($periode, $norek);
      $vtmpdebet      = 1000;
      $vtmpkredit     = 2000;
      $bukubesartmp   = [];
      foreach ($data as $key => $value) 
      {
          $tmp        = null;
          $debet      = $value['debet'];
          $kredit     = $value['kredit'];        
          if(empty($kredit)){$kredit = 0;} 
          if(empty($debet)){$debet = 0;} 

          if($kredit > $debet)
          {
              $kredit = $kredit - $debet;
              $debet  = 0;
          }
          elseif ($kredit < $debet) 
          {
              $debet  = $debet - $kredit;
              $kredit = 0; 
          }

          if(($posisidk=='DEBET' && $debet >0) || ($posisidk=='KREDIT' && $kredit > 0))
          {
              $tmp = $vtmpdebet;
              $vtmpdebet = $vtmpdebet + 1;
          }
          else
          {
              $tmp = $vtmpkredit;
              $vtmpdebet = $vtmpkredit + 1;
          } 

          $bukubesartmp [] = ['tgl'        => $value['tgl'],
                              'nobukti'    => $value['nobukti'],
                              'keterangan' => $value['keterangan'],
                              'debet'      => $debet,
                              'kredit'     => $kredit,
                              'tmp'        => $tmp
                              ];
      
      } 
      return $bukubesartmp;
  } 

  protected function cetakbukubesar($data, $filter)
  {
      $sett    = $this->mylib->getsetting(); 
      $hldg    = $sett['nama']; 
      $hldgaddr= $sett['alamat']; 
       
      $periode    = $filter['periode']; 
      $subtotald  = 0;
      $subtotalk  = 0; 

      /* di group kan sesuai dengan norek */
      $result = array();
      foreach ($data as $element) {
          $result[$element['id']][] = $element;
      }  
      $result_key = array_keys($result);
      $group = [];
      foreach ($result_key as $key => $value) {
          $group [] = ['id'     => $value];            
      } 

      $pdf       = new FPDF('L','mm','A4');
      $pdf->AliasNbPages();
      $pdf->AddPage();      
      $pdf->SetFont('Arial','B',12);      
      $pdf->ln(5);  
      $pdf->Cell(280,5,$hldg,0,1,'C');     
      $pdf->SetTextColor(255,0,0);
      $pdf->Cell(280,5, 'BUKU BESAR',0,1,'C'); 
      $pdf->SetTextColor(0,0,0);
      $pdf->Cell(280,5, 'Periode '. substr($periode, 0,4 ).' - '.substr($periode, 4,6 ),0,1,'C');  
      $pdf->ln(10);
     
      $curno = 0;   
      foreach ($group as $key => $g) 
      {   
          $id         = $g['id'];
          $nperkiraan = $this->model_perkiraan->getakun($id); //namaperkiraan($norek);
          $norek      = $nperkiraan['nomor'];
          $nama       = $nperkiraan['nama'];
          $subtotald  = 0;
          $subtotalk  = 0;  
          $pdf->SetFont('Arial','B',9);  
          $pdf->SetTextColor(255,0,0); 
          $pdf->Cell(25,6, $norek,0,0,'L'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(20,6, $nama,0,1,'L'); 
          // GROUP
          $pdf->SetFont('Arial','',9);  
          $pdf->SetTextColor(0,0,245);  
          $pdf->SetFont('Arial','',10);  
          $pdf->Cell(25,6, 'Tanggal','B',0,'C'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(50,6, 'Nobukti','B',0,'C'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(95,6, 'Keterangan','B',0,'C'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(30,6, 'Debet','B',0,'R'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(30,6, 'Kredit','B',0,'R'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(30,6, 'Saldo','B',1,'R'); 
          
          $bukubesar   = array_filter($data, function($value) use($id){
                      return $value['id']==$id;
                  });
          
          foreach ($bukubesar as $key => $d)
          {  

              $pdf->SetTextColor(0,0,0); 
              $pdf->SetFont('Arial','',9);  
              $posisidk = $d['posisidk'];
              $pdf->Cell(25,6, $d['tgl'],0,0,'L'); 
              $pdf->Cell(2,5,'',0, 0,'C'); 
              $pdf->Cell(50,6, $d['nobukti'],0,0,'L'); 
              $pdf->Cell(2,5,'',0, 0,'C'); 
              $pdf->Cell(95,6, $d['keterangan'],0,0,'L'); 
              $pdf->Cell(2,5,'',0, 0,'C'); 
              $pdf->Cell(30,6, number_format($d['debet'],2,',','.'),0,0,'R'); 
              $pdf->Cell(2,5,'',0, 0,'C'); 
              $pdf->Cell(30,6, number_format($d['kredit'],2,',','.'),0,0,'R'); 
              $pdf->Cell(2,5,'',0, 0,'C'); 
              $pdf->Cell(30,6, number_format($d['saldo'],2,',','.'),0,1,'R'); 

              $subtotald   = $subtotald + $d['debet'];  
              $subtotalk   = $subtotalk + $d['kredit'];
          }
          $pdf->SetFont('Arial','B',9);       
          $pdf->SetTextColor(0,0,245);        
          $pdf->Cell(25,7,$norek,0,0,'L'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(147,7,$nama ,0,0,'L'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(30,7,number_format($subtotald,2,',','.'),'B',0,'R'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(30,7,number_format($subtotalk,2,',','.'),'B',1,'R');  
          $pdf->ln(5);     
      }  
      $pdf->Output();    
  }

  /* JURNAL UMUM */
  protected function lapjurnalumum($filter)
  {
      $periode = $filter['periode'];   
       
      $data    = $this->model_jurnalumum->jurnalumum($periode); 
      $lapjurnalumum = [] ;
      foreach ($data as $key => $value) 
      {
        $lapjurnalumum [] = [
                            'tabel'    => $value['tabel'],
                            'nobukti'  => $value['nobukti'],
                            'tgl'      => $value['tgl'],
                            'norek'    => $value['norek'],
                            'nama'     => $value['nama'],
                            'debet'    => $value['debet'],
                            'kredit'   => $value['kredit'] 
                        ]; 
      }

      $pfilter1  = $filter['pfilter1'];  
      $pfilter2  = $filter['pfilter2'];  
      $pfilter3  = $this->filter($filter['pfilter3']);  
      $pfilter4  = $filter['pfilter4'];   

      if(!empty($pfilter2) && !empty($pfilter3) && !empty($pfilter4))
      { 
          $paramakhir     = $pfilter4; 
          $filter         = $pfilter2;     
          switch ($pfilter3)  
          {
              case "==": 
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]==$paramakhir;
                  });  
                  break;
              case "<>":
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<>$paramakhir;
                  });  
                  break;
              case ">":
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>$paramakhir;
                  });  
                  break;
              case "<":
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<$paramakhir;
                  });  
                  break;
              case ">=":
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]>=$paramakhir;
                  });  
                  break;
              case "=<":
                  $lapjurnalumum  = array_filter($lapjurnalumum, function($value) use ($paramakhir, $filter){ 
                  return $value[$filter]<=$paramakhir;
                  });  
                  break; 
          }  
      } 
      return $lapjurnalumum; 
  }

  protected function cetakjurnalumum($data, $filter)
  {
      $sett       = $this->mylib->getsetting();
      $hldg       = $sett['nama']; 
      $hldgaddr   = $sett['alamat']; 

      $periode    = $filter['periode'];

      $subtotald  = 0;
      $subtotalk  = 0;


      $result     = array();
      foreach ($data as $element) 
      {
        $result[$element['nobukti']][] = $element;
      }  
      $result_key = array_keys($result);
      $group      = [];
      foreach ($result_key as $key => $value) 
      {
        $group [] = ['nobukti'     => $value];            
      }    

      $pdf        = new FPDF('P','mm','A4');
      $pdf->AliasNbPages();
      $pdf->AddPage();      
      $pdf->SetFont('Arial','B',11);      
      $pdf->ln(5);  
      $pdf->Cell(182,5,$hldg,0,1,'C');     
      $pdf->SetTextColor(255,0,0);
      $pdf->Cell(182,5, 'JURNAL UMUM',0,1,'C'); 
      $pdf->SetTextColor(0,0,0);
      $pdf->Cell(182,5, 'Periode '. substr($periode, 0,4 ).' - '.substr($periode, 4,6 ),0,1,'C');  
      $pdf->ln(10);

      $group         = $this->array_orderby($group, 'nobukti', SORT_ASC );      
      foreach ($group as $key => $value) 
      {
          $pdf->SetFont('Arial','B',9);     
          $pdf->SetTextColor(255,0,0); 
          $nobukti= $value['nobukti']; 
          $pdf->Cell(25,5,$nobukti,0,1,'L'); 
          $pdf->SetFont('Arial','', 9);           
          $pdf->SetTextColor(0,0,245); 

          $pdf->Cell(20,5,'Tanggal','B',0,'C'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          // $pdf->Cell(25,5,'Jenis','B',0,'C');  
          // $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(110,5,'Nama','B',0,'C'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(25,5,'Debet','B',0,'R'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(25,5,'Kredit','B',1,'R');   

          $nota   = array_filter($data, function($value) use($nobukti){
                  return $value['nobukti']==$nobukti;
                }); 

          $debet  = 0;
          $kredit = 0;

          foreach ($nota as $key => $g) 
          {
              $pdf->SetFont('Arial','', 9); 
              $pdf->SetTextColor(0,0,0); 
              $tgl        = strtotime($g['tgl']);
              $tgl        = date('d-m-Y', $tgl);
              $pdf->Cell(20,6, $tgl,0,0,'L'); 
              $pdf->Cell(2,5,'',0, 0,'C');
              // $pdf->Cell(25,6, $g['tabel'],0,0,'L');
              // $pdf->Cell(2,5,'',0, 0,'C');               
              // $pdf->Cell(15,6, $g['norek'],1,0,'L'); 
              $pdf->Cell(110,6, $g['norek'].' - '.$g['nama'],0,0,'L'); 
              $pdf->Cell(2,5,'',0, 0,'C');
              $pdf->Cell(25,6, number_format($g['debet'],2,',','.'),0,0,'R'); 
              $pdf->Cell(2,5,'',0, 0,'C');
              $pdf->Cell(25,6, number_format($g['kredit'],2,',','.'),0,1,'R');  
              $debet  = $debet + $g['debet'];
              $kredit = $kredit + $g['kredit'];
          }
          $pdf->SetFont('Arial','B', 9); 
          $pdf->SetTextColor(0,0,245);
          $pdf->Cell(132,5, 'Total',0,0,'L'); 
          $pdf->Cell(2,5,'',0, 0,'C');
          $pdf->Cell(25,5,number_format($debet,0,',','.'),'B',0,'R'); 
          $pdf->Cell(2,5,'',0, 0,'C'); 
          $pdf->Cell(25,5,number_format($kredit,0,',','.'),'B',1,'R');   
          $pdf->ln(5); 
      } 
      $pdf->Output();   
  }

  private function filter($kondisi)
  {
    $n = $kondisi;    
    switch ($kondisi) 
    {
      case "Sama Dengan":           
          $n =  "==";          
          break;
      case "Tidak Sama":          
          $n =  "<>";          
          break;
      case "Lebih Dari":          
          $n =  ">";          
          break;
      case "Kurang Dari":          
          $n =  "<";          
          break;
      case "Lebih Dari Sama Dengan":          
          $n =  ">=";          
          break;
      case "Kurang Dari Sama Dengan":
          $n =  "=<";
          break;  
    }   
    return $n;
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
      $pdf->SetFont('Arial','B',25);
      $pdf->Cell(180,10,"DATA NOT FOUND",0,1,'C'); 
      $pdf->Ln(40);
      $pdf->Cell(180,5,"Support By :",0,1,'C'); 
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(180,10,"AKBAR",0,1,'C'); 
      $pdf->Output();
  }


  private function array_orderby() 
  {
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
