<!DOCTYPE html>
<html>

  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/images/favicon.ico');?>">
  <title>BUKTI KAS MASUK</title> 
  <style> 
    *
    {
      font-family: 'Open Sans', sans-serif;
      color: #5b5b5b;  
    }
    .nota 
    {
      /*max-width: 300px; */
      /*width: 58mm;*/
      width: 88mm;
      height: 100%;
      background-color: #ffffff;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;

    }
    .isi-nota th
    {
      border-top: 1px solid #e4e4e4;
      border-bottom: 1px solid #e4e4e4;
      font-size: 12px; 
      color: #5b5b5b;  
      line-height: 18px; 
      vertical-align: bottom; 
      text-align: center;
    }
    .isi-nota td
    { 
      font-size: 12px; 
      color: #5b5b5b;  
      line-height: 18px; 
      vertical-align: bottom;  
    }
  </style>

  <body> 
    <div class="nota"> 
      <!-- ISI TOKO -->
      <div class="toko">
        <table width="100%" border="0" cellpadding='2' cellspacing="2" align="center" bgcolor="#ffffff" style="padding-top:4px;">
          <tbody>
            <tr>
              <td style="font-size: 12px;  
                line-height: 18px; 
                vertical-align: bottom; 
                text-align: center;">            
                <strong style="font-size:16px;"><?php echo $namatoko;?></strong>
                <br><?php echo $telp;?> 
                <br><?php echo $alamat;?>
              </td>
            </tr>
            <tr>
              <td height="2" colspan="0" style="border-bottom:1px solid #e4e4e4 "></td>
            </tr>
          </tbody>
        </table> 
      </div>

      <!-- ISI HEADER --> 
      <div class="header">
        <table cellpadding="0" cellspacing="0" style="font-size: 12px; 
              color: #5b5b5b;  
              line-height: 18px; 
              vertical-align: bottom; 
              text-align: left;">
          <tbody>
            <tr>
              <td>No. Bukti</td>
              <td>&nbsp; : &nbsp;</td>
              <td><?php echo $nobukti;?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>&nbsp; : &nbsp;</td>
              <td><?php echo $tgl;?></td>
            </tr>
            <tr>
              <td>Bank</td>
              <td>&nbsp; : &nbsp;</td>
             <td><?php echo $namabank;?></td>
            </tr>  
          </tbody>
        </table>   
      </div> 

      <!-- ISI NOTA -->
      <div class="isi-nota">
        <table width="100%" border="0" cellpadding='0' cellspacing="0" align="center" bgcolor="#ffffff" style="padding-top:4px;">
          <tr>
            <th>No Akun</th>
            <th>Nama Akun</th>
            <th>Jumlah</th>
          </tr>
          <tbody>
            <!-- FOREACH --> 
            <?php 
              $no=0;                       
              foreach($detail as $d) {$no++ ;?> 
              <tr>  
                <td><?php echo $d['nomor'];?></td>
                <td style="line-height: 1.2">
                  <?php echo $d['namaperkiraan'];?>
                  <br />
                  <?php echo $d['keterangan'];?>  
                </td>  
                <td style="text-align: right;">
                  <?php echo number_format($d['kredit'],2,",",".") ;?>                    
                </td> 
              </tr>  
            <?php } ?>  
          </tbody> 
        </table>
        <table width="100%" border="0" cellpadding='2' cellspacing="2" align="center" bgcolor="#ffffff" 
        style="padding-top:4px;">
          <tbody>
            <tr>
              <td height="2" colspan="0" style="border-bottom:1px solid #e4e4e4 "></td>
            </tr> 
          </tbody>
        </table> 
      </div>

      <!-- TOTAL NOTA -->
      <div class="total">
        <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px;  
              line-height: 18px; 
              vertical-align: bottom; 
              text-align: left;">
          <tbody>            
            <tr>
              <td style="text-align: left; width:15%;"><strong>Total</strong></td>
              <td style="width:5%;">:</td>
              <td style="text-align: right; width:45%;"><strong><?php echo $total;?></strong></td>
            </tr>
            <!-- <tr>
              <td style="text-align: left; width:15%;"><strong>Disc</strong></td>
              <td style="width:5%;">:</td>
              <td style="text-align: right; width:45%;"><strong>Rp disc</strong></td>
            </tr>
            <tr>
              <td style="text-align: left; width:15%;"><strong>Total</strong></td>
              <td style="width:5%;">:</td>
              <td style="text-align: right; width:45%;"><strong>Rp Total</strong></td>
            </tr>  -->
          </tbody>
        </table>     
      </div> 

      <!-- TUTUP -->
      <div class="tutup">
        <table width="100%" border="0" cellpadding='2' cellspacing="2" align="center" bgcolor="#ffffff" style="padding-top:4px;">
          <tbody>
            <tr>
              <td height="2" colspan="0" style="border-bottom:1px solid #e4e4e4 "></td>
            </tr>
            <tr>
              <td style="font-size: 12px;  
                line-height: 18px; 
                vertical-align: bottom; 
                text-align: center;">            
                <strong style="font-size:14px;">-BUKTI KAS MASUK-</strong>
                <!-- <br>Barang Yang sudah dibeli tidak dapat dikembalikan  -->
                <br> --- <?php echo $it;?> ---
              </td>
            </tr>
          </tbody>
        </table> 
      </div>

    </div>
  </body>
  <script type="text/javascript">
    window.onafterprint = () => window.close();
    window.print();
    window.onfocus = function () { setTimeout(function () { window.close(); }, 500); } 
  </script>
</html>

