<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Cara penggunaan
	==============================
	pada file controler, load dulu library
	$this->load->library('indo_date');
	$tanggal_indo = $this->indo_date->tgl_indo($tgl_yang_akan_dirubah);

 */

class Indo_date {
	function Indo_date(){
		$this->CI =& get_instance();
	}

	function tgl_indo($tgl){
		if ($tgl == "0000-00-00 00:00:00" OR $tgl == "0000-00-00") {
			return "-";
		}
		else {
			$tanggal = substr($tgl,8,2);
			$bulan = $this->getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;
		}
	}

	function getBulan($bln){
		switch ($bln){
			case 1:
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	}

	function getHari ($date) {
		$day = date("D", strtotime($date));
		$dayList = array(
			"Sun" => "Minggu",
			"Mon" => "Senin",
			"Tue" => "Selasa",
			"Wed" => "Rabu",
			"Thu" => "Kamis",
			"Fri" => "Jumat",
			"Sat" => "Sabtu"
		);
		return $dayList[$day];
	}

}


?>
