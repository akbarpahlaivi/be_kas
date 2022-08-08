<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apilib
{

	function GET($url)
	{
		// Ambil data mentahnya (JavaScript Object)
		$data_mentah = $this->callAPI('GET', $url);
		// Format menjadi PHP Array
		$kePHPArray = json_decode($data_mentah, true);
		// Atau format menjadi PHP Object
		$kePHPObject = json_decode($data_mentah);
		return $kePHPArray;
	}

	function POST($url, $data = false)
	{
		// Ambil data mentahnya (JavaScript Object)
		$data_mentah = $this->callAPI('POST', $url, $data);
		// Format menjadi PHP Array
		$kePHPArray = json_decode($data_mentah, true);
		// Atau format menjadi PHP Object
		$kePHPObject = json_decode($data_mentah);
		return $kePHPArray;
	}

	function callAPI($method, $url, $data = false)
	{
		// Inisialisasi cURL
		$curl = curl_init(); 
		// Menentukan bentuk atau tipe request
		switch ($method) {
		   case "POST":
			  curl_setopt($curl, CURLOPT_POST, 1);
			  if ($data)
				 curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			  break;
		   case "PUT":
			  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			  if ($data)
				 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	 
			  break;
		   default:
			  if ($data)
				 $url = sprintf("%s?%s", $url, http_build_query($data));
		}
	 
		// OPTIONS: pilihan konfigurasi
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// IIS
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	 
		// EXECUTE: memulai koneksi atau request ke server
		$result 	= curl_exec($curl); 
		$statusCode = curl_getinfo($curl); 

		// Menutup koneksi
		curl_close($curl); 
		if ($statusCode['http_code'] == 200 || $statusCode['http_code'] == '200' ||
			$statusCode['http_code'] == 201 || $statusCode['http_code'] == '201') {
			return ([
				'data'		 => json_decode($result),
				'statuscode' => $statusCode['http_code']
			]); //$result; 
		} 
		else 
		{
			return ([
				'data'		 => [],
				'statuscode' => $statusCode['http_code']
			]);
		}  
	}

	function callAPI2($method, $url, $data = false)
	{
		// Inisialisasi cURL
		$curl = curl_init();
		// Menentukan bentuk atau tipe request
		switch ($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $this->formatData($data));
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// OPTIONS: pilihan konfigurasi
		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// IIS
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		// EXECUTE: memulai koneksi atau request ke server
		$result = curl_exec($curl);
		$httpcode = curl_getinfo($curl);

		// Menutup koneksi
		curl_close($curl);
		if ($httpcode['http_code'] == 200 || $httpcode['http_code'] == '200' ||
			$httpcode['http_code'] == 201 || $httpcode['http_code'] == '201') {
			return ([
				'data'		 => json_decode($result),
				'statuscode' => $httpcode['http_code']
			]); //$result; 
		} else {
			return ([
				'data'		 => [],
				'statuscode' => $httpcode['http_code']
			]);
		} 

	}

	function formatData($data)
	{
		if ($data && is_array($data)) {

			$temp = '';
			$i = 0;
			foreach ($data as $key => $value) {
				$temp .= $key . "=" . $value;
				if (count($data) - 1 !== $i) {
					$temp .= "&";
				}
				$i++;
			}

			return $temp;
		} else {
			return '';
		}
	}


	function caridata($dpost, $text){ 
        $x = 0;
        $data = stripos($text, $dpost); 
        if(strlen($data) > 0){
            return $x = 1;
        }
    }

    function divpage($total, $perpage){
		$bg 	= intdiv($total, $perpage);
		$ss  	= $total % $perpage;
		if($ss > 0){
			$ss = 1;
		} 
		return $bg + $ss; 
    }
}
