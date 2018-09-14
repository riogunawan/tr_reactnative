<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function data($post, $debug = false)	{
		$order = $post['order'][0];

		$this->db->start_cache();

			$this->db->from("admin a");

			// filter
			if (!empty($post['nama_lengkap'])) {
				$this->db->like('a.nama_lengkap', $post['nama_lengkap'], 'both');
			}
			if (!empty($post['username'])) {
				$this->db->like('a.username', $post['username'], 'both');
			}
			if (!empty($post['id_level'])) {
				$this->db->where('l.id_level', $post['id_level']);
			}
			if (!empty($post['kabupaten'])) {
				$this->db->where('kab.id', $post['kabupaten']);
			}

			$orderColumn = array(
				2 => "a.nama_lengkap",
				3 => "a.username",
				4 => "l.level",
				5 => "kab.name",
			);

			// order
			if ($order['column'] == 0) {
				$this->db->order_by('a.id_admin', 'DESC');
			} else {
				$this->db->order_by($orderColumn[$order['column']], $order['dir']);
			}

			// join
			$this->db->join("admin_level l", "l.id_level = a.id_level", 'left');
			$this->db->join("wilayah_kabupaten kab", "kab.id = a.wilayah_id", 'left');

		$this->db->stop_cache();

		// get num rows
		$this->db->select('a.id_admin');
		$rowCount = $this->db->get()->num_rows();

		// get result
		$this->db->select('
			a.*,
			l.*,
			kab.name kabupaten
		');

		$this->db->limit($post['length'], $post['start']);

		$val = $this->db->get()->result();

		$this->db->flush_cache();

		$output['draw']            = $post['draw'];
		$output['recordsTotal']    = $rowCount;
		$output['recordsFiltered'] = $rowCount;
		$output['data']            = array();

		if ($debug) {
			$output['sql'] = $this->db->last_query();
		}

		$no = 1 + $post['start'];

		$base = base_url();

		foreach ($val as $data) {

			$btnAksi = "";

			$btnAksi .= "
				<li>
					<a href='{$base}admin/form/$data->id_admin' class='btn-edit'>
						<i class='fa fa-edit'></i>&nbsp;
						Edit
					</a>
				</li>
			";

			$btnAksi .= "
				<li>
					<a href='#' class='btn-delete' data-id='$data->id_admin'>
						<i class='fa fa-close'></i>&nbsp;
						Delete
					</a>
				</li>
			";

			$aksi = "
			<div class='btn-group'>
				<button type='button' class='btn btn-warning dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
					<i class='fa fa-gear'></i>
				</button>
				<ul class='dropdown-menu'>
					$btnAksi
				</ul>
			</div>
			";

			$baris = array(
				"no" => $no,
				"aksi" => $aksi,
				"nama_lengkap" => $data->nama_lengkap,
				"username" => $data->username,
				"id_level" => $data->level,
				"kabupaten" => $data->kabupaten,
			);

			array_push($output['data'], $baris);
			$no++;
		}
		return json_encode($output);
	}

	public function add ($data) {
		$this->db->insert('admin', $data);
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

/* End of file M_admin.php */
/* Location: ./application/modules/admin/models/M_admin.php */