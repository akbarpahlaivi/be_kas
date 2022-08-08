<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jurnal extends CI_Model
{

    public function jurnalkas($id, $vjenis)
    {
        if($vjenis == 'KAS KELUAR')
        {
            $this->db->select("d.*, h.nobukti, h.kodebank, h.deskripsi, h.tgl, h.periode, h.dperiode, h.jenis");
            $this->db->select("h.userid");
            $this->db->select("b.pos, p.posisidk");
            $this->db->from("trankask_d as d");
            $this->db->join("trankask_h as h", "d.idh = h.id", "Left");
            $this->db->join("bank as b", "h.kodebank = b.id", "Left");
            $this->db->join("perkiraan as p", "p.id = d.norek", "Left");
            $this->db->where("d.idh", $id);
            $this->db->where("d.hapus", 0); 
        }
        else
        {
            $this->db->select("d.*, h.nobukti, h.kodebank, h.deskripsi, h.tgl, h.periode, h.dperiode, h.jenis");
            $this->db->select("h.userid");
            $this->db->select("b.pos, p.posisidk");
            $this->db->from("trankasm_d as d");
            $this->db->join("trankasm_h as h", "d.idh = h.id", "Left");
            $this->db->join("bank as b", "h.kodebank = b.id", "Left");
            $this->db->join("perkiraan as p", "p.id = d.norek", "Left");
            $this->db->where("d.idh", $id);
            $this->db->where("d.hapus", 0);   
        }

        $a = $this->db->get()->result_array();
        if(!empty($a))
        {
            foreach ($a as $key => $value) 
            {
                $nobukti    = $value['nobukti'];
                $userid     = $value['userid'];
                $kodebank   = $value['kodebank'];
                $deskripsi  = $value['deskripsi'];
                $tgl        = $value['tgl'];
                $periode    = $value['periode'];
                $dperiode   = date("Ymd", strtotime($tgl));
                $pos        = $value['pos'];
                $tabelid    = $value['id'];
                $idh        = $id;
                $norek      = $value['norek'];
                $debet      = $value['debet'];
                $kredit     = $value['kredit'];
                $keterangan = $value['keterangan'];
                $posisidk   = $value['posisidk'];
                $vnobukti   = 'K_'.$nobukti;
                $rekkas     = "KAS REK KAS ". $vjenis;
                $rekbank    = "KAS REK BANK ". $vjenis;
                // cek jurnal_h 
                $jurnalh    = $this->cekjurnalh($idh, $vjenis); 
                if(!empty($jurnalh))
                {
                    $vidjrn = $jurnalh['id'];
                    $upd    = $this->simpanupdate('jurnal_h', 
                                            array('nobukti'       => $vnobukti,
                                                  'tgl'           => $tgl,
                                                  'userid'        => $userid,
                                                  'periode'       => $periode,
                                                  'dperiode'      => $dperiode), 
                                            array('idsumber'      => $idh,
                                                  'tabel'         => $vjenis)); 
                }
                else
                {
                    $ins    = $this->simpaninsert('jurnal_h', 
                                            array('nobukti'       => $vnobukti,
                                                  'tgl'           => $tgl,
                                                  'it'            => date("Y-m-d H:i:s"),
                                                  'userid'        => $userid,
                                                  'periode'       => $periode,
                                                  'dperiode'      => $dperiode,
                                                  'idsumber'      => $id,
                                                  'tabel'         => $vjenis)); 
                    $vidjrn = $this->db->insert_id();  
                }
                $jurnaldkas = $this->cekjurnald($vidjrn, $tabelid, $rekkas);
                if(!empty($jurnaldkas))
                {
                    if($posisidk=='DEBET')
                    {
                        $datajurnaldkas  = array('norek'       => $norek,
                                                 'debet'       => $debet,
                                                 'kredit'      => $kredit,
                                                 'keterangan'  => $keterangan  
                                                );  
                        $datajurnaldbank = array('norek'       => $pos,
                                                 'debet'       => $kredit,
                                                 'kredit'      => $debet,
                                                 'keterangan'  => $keterangan 
                                                ); 
                    }
                    else
                    {
                        $datajurnaldkas  = array('norek'       => $norek,
                                                 'debet'       => $debet,
                                                 'kredit'      => $kredit,
                                                 'keterangan'  => $keterangan  
                                                ); 
                        
                        $datajurnaldbank = array('norek'       => $pos,
                                                 'debet'       => $kredit,
                                                 'kredit'      => $debet,
                                                 'keterangan'  => $keterangan  
                                                );  
                    }
                    $updkas = $this->simpanupdate('jurnal_d', $datajurnaldkas, 
                                            array('deskripsi'   => $rekkas,
                                                  'tabelid'     => $tabelid));
                    $updbnk = $this->simpanupdate('jurnal_d', $datajurnaldbank, 
                                            array('deskripsi'   => $rekbank,
                                                  'tabelid'     => $tabelid));
                }
                else
                {
                    if($posisidk=='DEBET')
                    {
                        $datajurnaldkas  = array('norek'       => $norek,
                                                 'debet'       => $debet,
                                                 'kredit'      => $kredit,
                                                 'keterangan'  => $keterangan,
                                                 'deskripsi'   => $rekkas,
                                                 'tabelid'     => $tabelid,
                                                 'idh'         => $vidjrn
                                                );  
                        $datajurnaldbank = array('norek'       => $pos,
                                                 'debet'       => $kredit,
                                                 'kredit'      => $debet,
                                                 'keterangan'  => $keterangan,
                                                 'deskripsi'   => $rekbank,
                                                 'tabelid'     => $tabelid,
                                                 'idh'         => $vidjrn 
                                                ); 
                    }
                    else
                    {
                        $datajurnaldkas  = array('norek'       => $norek,
                                                 'debet'       => $debet,
                                                 'kredit'      => $kredit,
                                                 'keterangan'  => $keterangan,
                                                 'deskripsi'   => $rekkas,
                                                 'tabelid'     => $tabelid,
                                                 'idh'         => $vidjrn
                                                ); 
                        
                        $datajurnaldbank = array('norek'       => $pos,
                                                 'debet'       => $kredit,
                                                 'kredit'      => $debet,
                                                 'keterangan'  => $keterangan,
                                                 'deskripsi'   => $rekbank,
                                                 'tabelid'     => $tabelid,
                                                 'idh'         => $vidjrn 
                                                );  
                    }
                    $inskas = $this->simpaninsert('jurnal_d', $datajurnaldkas);
                    $insbnk = $this->simpaninsert('jurnal_d', $datajurnaldbank);
                }        
            }
            $saldo          = $this->saldojurnalh($vidjrn);
            $totaldebet     = $saldo['debet'];
            $totalkredit    = $saldo['kredit'];
            $updtotal       = $this->simpanupdate('jurnal_h', 
                                            array('totaldebet'  => $totaldebet, 
                                                  'totalkredit' => $totalkredit), 
                                            array('id'          => $vidjrn,
                                                  'tabel'       => $vjenis));
            if($vjenis == 'KAS KELUAR')
            {
                $vtabel = 'trankask_d'; 
            }
            else
            {
                $vtabel = 'trankasm_d';
            }
            $deltabel     = $this->hapusdtljurnald($idh, $vtabel, $rekkas, $vidjrn);
            $deltabel     = $this->hapusdtljurnald($idh, $vtabel, $rekbank, $vidjrn);
        }        
    }

    public function hapusdtljurnald($idh, $tabel, $deskripsi, $idhjurnal)
    {
        $data = $this->db->query("
                delete from jurnal_d where deskripsi='$deskripsi' and idh=$idhjurnal 
                and tabelid not in (select id from $tabel where idh=$idh)");
        return $data;
    }

    public function cekjurnalh($tabelid, $tabel)
    { 
        $this->db->select("*");
        $this->db->from("jurnal_h"); 
        $this->db->where("idsumber", $tabelid);
        $this->db->where("tabel", $tabel);
        $this->db->where("hapus", 0);
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function cekjurnald($idh, $tabelid, $tabel)
    { 
        $this->db->select("*");
        $this->db->from("jurnal_d"); 
        $this->db->where("idh", $idh);
        $this->db->where("tabelid", $tabelid);
        $this->db->where("deskripsi", $tabel);
        $this->db->where("hapus", 0);
        $g = $this->db->get()->result_array();
        return $g;
    }

    public function saldojurnalh($id)
    { 
        $this->db->select("COALESCE(SUM(debet),0) as debet, COALESCE(SUM(kredit),0) AS kredit");
        $this->db->from("jurnal_d"); 
        $this->db->where("idh", $id); 
        $this->db->where("hapus", 0);
        $g = $this->db->get()->row_array();
        return $g;
    } 

    public function posperkiraan($cabang)
    {
        $this->db->select("*");
        $this->db->from("pos");  
        $this->db->where("cabang", $cabang);   
        return $this->db->get()->row_array(); 
    }

    public function cekdatajurnal($cabang)
    {
        $this->db->select("*");
        $this->db->from("jurnal_h");  
        $this->db->where("cabang", $cabang);  
        $g = $this->db->get()->result_array();
        return $g; 
    }

    public function jenisjurnalsaldo($periode, $cabang)
    { 
        $jenis  = $this->jenisjurnal($periode, $cabang); 
        $ju     = [];
        foreach ($jenis as $key => $value)
        {
            $jenis = $value['jenis'];
            if($jenis=='MEMORIAL')
            {
                $jenis = 'MEMORIAL';
            }
            elseif ($jenis=='PENYESUAIAN') 
            {
                $jenis = 'PENYESUAIAN';
            }
            else
            {
                $jenis = 'BIASA';
            } 
            
            $ju[] = [ 
                              'nobukti'         => $value['nobukti'],
                              'norek'           => $value['norek'],
                              'nama'            => $value['nama'],
                              'debet'           => $value['debet'],
                              'kredit'          => $value['kredit'],
                              'posisidk'        => $value['posisidk'],
                              'posisineraca'    => $value['posisinr'],
                              'jenis'           => $jenis,
                              'periode'         => $periode 
                            ];  
        }

        
        $result = array();
        foreach ($ju as $element) 
        {
            $result[$element['norek']][] = $element;
        } 
        $group = $result;


        $result_key = array_keys($result);
        $norek = [];
        foreach ($result_key as $key => $value) {
            $norek [] = ['norek'    => $value];            
        }
        // array map = olah isi array 
        $result = array_map(function ($no_rekening) {
            // Ambil debet 
            $all_debet = array_map(function ($detail) {
                $total_debet = $detail['debet'];
                return $total_debet;
            }, $no_rekening);
            // Ambil kredit
            $all_kredit = array_map(function ($detail) {
                $total_debet = $detail['kredit'];
                return $total_debet;
            }, $no_rekening);

            // array reduce akumulasi 
            // Jumlahkan semua debet
            $all_debet = array_reduce($all_debet, function ($total, $item) {
                $total += $item;
                return $total;
            });
            // Jumlahkan semua kredit
            $all_kredit = array_reduce($all_kredit, function ($total, $item) {
                $total += $item;
                return $total;
            });

            return [
                'debet'  => $all_debet,
                'kredit' => $all_kredit
            ];
        } ,$result); 

        $output = array(
            'jenisjurnal' => $ju,
            'group'       => $group,
            'norek'       => $norek,
            'total'       => $result
        );   
        return $output; 
    }

    public function jenisjurnal($periode, $cabang)
    {
        $this->db->select("d.norek, d.debet, d.kredit");
        $this->db->select("h.nobukti, h.periode, h.jenis ");   
        $this->db->select("p.nama, p.posisidk, p.posisinr");   
        $this->db->from("jurnal_d as d");    
        $this->db->join("jurnal_h as h", "h.id = d.idh", "Left");    
        $this->db->join("perkiraan as p", "p.id = d.norek", "Left");    
        $this->db->where("h.periode", $periode);    
        $this->db->where("h.cabang", $cabang);     
        $this->db->where("d.hapus", 0);
        $g = $this->db->get()->result_array();
        return $g;  
    }

    public function getsaldorl2($periode, $cabang)
    {
        $data = $this->db->query("SELECT COALESCE(SUM(debet),0) AS debet, COALESCE(SUM(kredit),0) AS kredit, norek, posisinr FROM jurnal_d d LEFT JOIN jurnal_h h 
            ON d.idh=h.id
            left join perkiraan p
            on d.norek=p.nomor
            WHERE periode = '$periode' AND posisinr='R/L' and h.cabang = $cabang and d.hapus = 0
            GROUP BY norek");
        return $data->result_array();
    }

    public function simpanupdate($tabel, $data, $kunci)
    {
        $res = $this->db->update($tabel, $data, $kunci);
        return $res;
    }

    public function simpaninsert($tabel, $data)
    {
        $res = $this->db->insert($tabel, $data);
        return $res;
    } 

    public function deletedata($tabel, $kunci){
        $res = $this->db->delete($tabel,$kunci);
        return $res; 
    }

   
    /*    
    public function cektabelusercabang($tabel, $field, $value, $branch, $user)
    {
        $g = 0;  
        $this->db->select('id'); 
        $this->db->from($tabel); 
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($field, $value);     
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function cekusercabang($nama, $pass, $branch)
    { 
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("nama", $nama);
        $this->db->where("password", $pass);
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0);
        $g = $this->db->get()->row_array();
        return $g;
    }

    public function getidtabelusercabang($tabel, $field, $value, $branch, $user)
    {
        $g = 0;  
        $this->db->select('*'); 
        $this->db->from($tabel); 
        $this->db->where("cabang", $branch);
        $this->db->where("hapus", 0); 
        if(!empty($user)) 
        {
            $this->db->where("userid", $user);                   
        }
        $this->db->where($field, $value);                  
        $this->db->limit(1);                  
        $g = $this->db->get()->row_array();
        return $g;
    }
    */
     
    public function clean($string)
    {
        $clean = '';
        if (!empty($string)) {
            $clean = str_replace('removed', '', $this->security->sanitize_filename($string));
        }
        return $clean;
    }

    public function clean2($string)
    {
        $clean = '';
        if (!empty($string)) {
            $string = str_replace('<script>', '', $string);
            $string = str_replace('</script>', '', $string);
            $string = str_replace('<style>', '', $string);
            $string = str_replace('</style>', '', $string);
            $clean  = $string;
        }
        return $clean;
    }

    public function secclean($post)
    {
        $secclean   = trim($this->input->post($post, TRUE), " ");
        $secclean   = str_replace('removed', '', $this->security->sanitize_filename($secclean));
        return $secclean;
    }

    public function secclean2($post)
    {
        $secclean = trim($this->input->post($post, TRUE), " ");
        $secclean = str_replace('<script>', '', $secclean);
        $secclean = str_replace('</script>', '', $secclean);
        $secclean = str_replace('<style>', '', $secclean);
        $secclean = str_replace('</style>', '', $secclean);
        return $secclean;
    }    

    public function caridata($dpost, $text)
    {
        $x = 0;
        $data = stripos($text, $dpost);
        if (strlen($data) > 0) {
            return $x = 1;
        }
    }
  
    public function get_oldperiode($periode)
    {
        $thn        = date("Y",strtotime($periode));  
        $bln        = date("m",strtotime($periode));
        $bln        = ((int)$bln);
        $oldperiode = null;
        if($bln<=10){
            if($bln==1){
                $bln        = 12; 
                $thn        = ((int)$thn)-1;
                $oldperiode = $thn. $bln;  
            }else{
                $bln        = $bln-1; 
                $oldperiode = $thn .'0'. $bln;      
            }
        }else{
            $bln        = $bln-1; 
            $oldperiode = $thn . $bln;    
        }
        return $oldperiode;
    }

    public function get_newperiode($periode)
    { 
        $thn        = date("Y",strtotime($periode));  
        $bln        = date("m",strtotime($periode));
        $bln        = ((int)$bln);
        $newperiode = null;
        if($bln<10){  
            $bln        = $bln+1; 
            $newperiode = $thn . '0'. $bln;       
        }else{
            if($bln==12){
                $bln        = 1; 
                $thn        = ((int)$thn)+1;
                $newperiode = $thn . '0'. $bln;  
            }else{
                $bln        = $bln+1; 
                $newperiode = $thn . $bln;   
            } 
        }
        return $newperiode;
    }

    function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }  



}
