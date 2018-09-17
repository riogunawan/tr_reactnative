<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_home extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function add ($data) {
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function edit ($id, $data) {
		$this->db
			->where("id_admin", $id)
			->update("admin", $data);
		return $this->db->affected_rows();
	}

	public function delete ($id) {
		return $this->db
			->where("id_admin", $id)
			->delete("admin");
	}

	public function cekid ($id) {
		$this->db->select('
			a.*,
		');
		$this->db->where("a.id_admin", $id);
		return $this->db->get('admin a');
	}

	function check_username($username, $id) {
		$this->db->select('username');
		$this->db->from('admin');
		$this->db->where(array("username" => $username));
		if ($id > 0) {
			$this->db->where("id_admin !=", $id);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function getWilayah ($wilayah, $parent = 61) {
		switch ($wilayah) {
			case 'provinsi':
				$this->db->from("wilayah_provinsi");
			break;

			case 'kabupaten':
				$this->db->from("wilayah_kabupaten");
				if ($parent > 0) {
					$this->db->where("provinsi_id", $parent);
				}
			break;

			default:
				show_404();
			break;
		}

		$sql = $this->db->get();

		$arr = array();
		foreach ($sql->result() as $data) {
			$arr += array(
				$data->id => $data->name
			);
		}

		return $arr;
	}

}

/* End of file M_home.php */
/* Location: ./application/modules/home/models/M_home.php */