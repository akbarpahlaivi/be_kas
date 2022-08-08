<?php
  defined('BASEPATH') or exit('No direct script access allowed');

/****
CLASS Bank BCA
 ***/

class Bca
{
  var $userid = "";
  var $password = "";
  var $rekening = 1;

  var $login_url = "https://ibank.klikbca.com";
  var $login_process_url = "https://ibank.klikbca.com/authentication.do";
  var $login_success = "authentication.do?value(actions)=welcome";
  var $menu_url = "https://ibank.klikbca.com/nav_bar_indo/menu_bar.htm";
  var $info_rekening = "https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm";
  var $mutasi_form_url = "https://ibank.klikbca.com/accountstmt.do?value( actions )=acct_stmt";
  var $mutasi_url = "https://ibank.klikbca.com/accountstmt.do?value(actions)=acctstmtview";
  var $logout_url = "https://ibank.klikbca.com/authentication.do?value(actions)=logout";

  var $cookie = "cookiejar";
  var $ch;
  var $dom;

  function __construct()
  {
    $this->dom = new DOMDocument();
  }

  function openCurl()
  {
    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt(
      $this->ch,
      CURLOPT_USERAGENT,
      "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"
    );
    curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie);
    curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
  }

  function closeCurl()
  {
    curl_close($this->ch);
  }

  function browse($url, $post = false, $follow = false, $reffer = false)
  {
    $this->openCurl();
    curl_setopt($this->ch, CURLOPT_URL, $url);

    if ($post) {
      curl_setopt($this->ch, CURLOPT_POST, 1);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    }

    if ($follow) {
      curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
    }

    if ($reffer) {
      curl_setopt($this->ch, CURLOPT_REFERER, $reffer);
    }

    $result = array("data" => curl_exec($this->ch), "info" => curl_getinfo($this->ch));
    $result['headers'] = substr($result['data'], 0, $result['info']['header_size']);

    //echo $result['data'];
    $this->closeCurl();
    return $result;
  }

  function login()
  {

    $this->browse($this->login_url);
    $params = 'value%28actions%29=login&value%28user_id%29=' . $this->userid . '&value%28user_ip%29=ip.server&value%28pswd%29=' . $this->password . '&value%28Submit%29=LOGIN';

    $result = $this->browse($this->login_process_url, $params, false, $this->login_url);
    return $isLogin = strpos($result['data'], $this->login_success);
  }

  function mutasi($dari, $sampai, $rekening = false)
  {
    if (!$rekening) {
      $rekening = $this->rekening;
    }

    $page = $this->browse($this->menu_url, false, false, $this->login_process_url);
    $page = $this->browse($this->info_rekening, false, false, $this->login_process_url);
    $page = $this->browse($this->mutasi_form_url, false, false, $this->info_rekening);

    $params = array();
    $t1 = explode('-', $sampai);
    $t0 = explode('-', $dari);

    $params[] = 'value%28startDt%29=' . $t0[2];
    $params[] = 'value%28startMt%29=' . intval($t0[1]);
    $params[] = 'value%28startYr%29=' . $t0[0];
    $params[] = 'value%28endDt%29=' . $t1[2];
    $params[] = 'value%28endMt%29=' . intval($t1[1]);
    $params[] = 'value%28endYr%29=' . $t1[0];
    $params[] = 'value%28D1%29=0';
    $params[] = 'value%28r1%29=1';
    $params[] = 'value%28fDt%29=';
    $params[] = 'value%28tDt%29=';
    $params[] = 'value%28submit1%29=Lihat+Mutasi+Rekening';
    $params = implode('&', $params);

    $page = $this->browse($this->mutasi_url, $params, false, $this->info_rekening);

    $res = preg_match('/<table border="1" width="100%" cellpadding="0" cellspacing="0" bordercolor="#ffffff">(.*?)<\/table>/s', $page['data'], $matches);
    if (!$res) {
      return false;
    }

    preg_match_all('/<tr>(.*?)<\/tr>/si', $matches[0], $trs);

    return $this->parseMutasi($trs[0]);
  }

  function parseMutasi($data)
  {
    $rows = array();
    $i = 1;

    foreach ($data as $tr) {
      if ($i > 1) {
        $str = preg_replace("/\n+/", "", $tr);
        $str = preg_replace("/<br>/", " ", $str);
        $str = preg_replace("/\s+/", " ", $str);
        $str = preg_replace("/,/", "", $str);

        preg_match_all('/<font face="verdana" size="1" color="#0000bb">(.*?)<\/font>/si', $str, $td);

        $row['tanggal'] = trim($td[1][0]);
        $row['keterangan'] = trim($td[1][1]);
        // $row['cabang'] = trim($td[1][2]);
        $row['jumlah'] = trim($td[1][3]);
        $row['jenis'] = trim($td[1][4]);
        $row['saldo'] = trim($td[1][5]);

        $rows[] = $row;
      }
      $i++;
    }

    return $rows;
  }

  function logout()
  {
    $this->browse($this->logout_url);
  }
}
