<?php
class M_login extends CI_Model {
	private $user;

	public function __construct() {
		$table = $this->config->load("database_table", true);
		$this->user = $table['admin'];
	}

	public function ubahPassword ($email, $new_pass, $exp) {
		return $this->db
			->where("user_email", $email)
			->update($this->user, array(
				"user_pass" => $new_pass,
				"forgot_exp" => $exp,
			));
	}

	public function setIpAddress ($username, $ip) {
		return $this->db
			->where("username", $username)
			->update($this->user, array(
				"ip_address" => $ip,
				"last_login" => date('Y-m-d H:i:s')
			));
	}

	//cek user dan sandi di database
	public function cek($email, $sandi) {
		$query = $this->db->get_where($this->user, array(
			'user_email' => $email,
			'user_pass' => $sandi
		), 1, 0);

		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//untuk mendapatkan data admin yg login
	public function getUserData($username){
		return $this->db
			->select("id_admin, username, pass, nama_lengkap, forgot_exp, avatar, a.*, wilayah_id")
			->where('username', "$username")
			->join('admin_level a', "a.id_level = $this->user.id_level", 'left')
			->where('banned', 0)
			->get($this->user, 1);
	}

	public function cekEmail ($email) {
		return $this->db
			->select("user_id")
			->where("user_email", $email)
			->get($this->user, 1);
	}
}