<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek_cuti
{
	protected $ci;
	public $tanggal_mulai;
	public $tanggal_selesai;

	public function __construct()
	{
        $this->ci =& get_instance();
       	$this->ci->load->database();
	}

	public function cek($NIP, $tanggal_mulai, $id_jenis)
	{
		if ($id_jenis == 1) {
			$cek = $this->cek_tahunan($NIP, $tanggal_mulai, $id_jenis);
		}
		else {
			$cek = array('stat'=>false, 'msg'=>$id_jenis.' belum diatur');
		}
		return $cek;
	}
	public function cek_tahunan($NIP, $tanggal_mulai)
	{
		$tahun = date('Y', strtotime($tanggal_mulai));
		$last_cuti = $this->last_cuti($NIP, 1);

		if ($tahun == $last_cuti) {
			$return = array('stat' => false, 'msg' => 'Sudah pernah mengambil cuti pada tahun bersamaan');
		}
		else {
			// hitung akumulasi cuti sebelumnya
			$xxx = $tahun-$last_cuti;
			$xxx = ($xxx > 3) ? 3 : $xxx ;
			$hak_cuti = $this->jumlah_hari_hak_cuti($xxx);
			$cuti_bersama = 0;
			$cuti_bersama_msg = "";
			for ($i=0; $i < $xxx; $i++) { 
				$count_cuti_bersama = $this->cuti_bersama(($tahun-$i));
				$cuti_bersama += $count_cuti_bersama;
				$cuti_bersama_msg .= "SKB ".($tahun-$i)." ".$count_cuti_bersama." Hari <br>";
			}
			$hak_cuti_tahunan = $hak_cuti - $cuti_bersama;
			$msg = 'Hak Cuti Tahunan Pegawai Bersangkutan Adalah '.$hak_cuti_tahunan.' Hari';
			$msg .= '<br>';
			$msg .= $cuti_bersama_msg;
			$return = array('stat' => false, 'msg' => $msg);
		}
		return $return;


	}
	public function cuti_bersama($tahun)
	{
		$query = $this->ci->db->where('YEAR(libur_tanggal)', $tahun);
		$query = $this->ci->db->where('libur_status', 'cuti_bersama');
		$query = $this->ci->db->get('libur');
		if ($query->num_rows()>0) {
			$count = $query->num_rows();	
		}
		else {
			$count = 0;
		}
		return $count;
	}
	public function last_cuti($NIP, $id_jenis)
	{
		$query = $this->ci->db->select('YEAR(tanggal_mulai) AS tahun');
		$query = $this->ci->db->where('NIP', $NIP);
		$query = $this->ci->db->where('id_jenis', $id_jenis);
		$query = $this->ci->db->order_by('tanggal_mulai', 'desc');
		$query = $this->ci->db->get('cuti', 1);
		if ($query->num_rows()>0) {
			$row = $query->row();
			return $row->tahun;
		}
		else {
			return 0;
		}
	}
	public function jumlah_hari_hak_cuti($jumlah_tahun)
	{
		$jumlah_hari = array(1=>12, 2=>18, 3=>24);
		return $jumlah_hari[$jumlah_tahun];
	}

}

/* End of file Cek_cuti.php */
/* Location: ./application/libraries/Cek_cuti.php */
