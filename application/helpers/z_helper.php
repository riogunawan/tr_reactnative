<?php
function notification_proses($type="success",$title="Berhasil", $msg = "Berhasil", $template="adminLTE"){
	if ($template == "adminLTE") {
		$html = '<div class="alert alert-'.$type.' alert-dismissable">
			<h4><i class="icon fa fa-check"></i> '.$title.'</h4>
			'.$msg.'
		</div>';
	}
	return $html;
}

function quirkNotif ($stat = false, $title, $msg = "") {
	// $bg = ($stat) ? "bg-notif-success" : "bg-notif-danger";
	$bg = ($stat) ? "alert-success" : "alert-danger";
	if ($msg == "") {
		$submsg = "";
	} else {
		$submsg = "<div class='submsg'>$msg</div>";
	}
	
	// <div class='panel-body $bg notif'>
	return "
	<div class='alert $bg fade in' role='alert'>
		<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		<h4 class='alert-heading'>$title !</h4>
		<p>$submsg</p>
	</div>
	";
}

function combobox_dynamic($name,$table,$field,$pk,$selected="",$placeholder="::Pilih Data::",$class="form-control"){
	$ci = get_instance();
	$cmb = "<select name='$name' id='$name' class='$class $name'>";
	$cmb .= "<option value=''>".$placeholder."</option>";
	if (is_object($table)) {
		$data = $table->result();
	}
	else {
		$data = $ci->db->order_by($field, 'asc');
		$data = $ci->db->get($table)->result();
	}
	foreach ($data as $d){
		$cmb .="<option value='".$d->$pk."'";
		$cmb .= $selected==$d->$pk?" selected='selected'":'';
		$cmb .=">".$d->$field."</option>";
	}
	$cmb .="</select>";
	return $cmb;
}

function combobox_category($id_type, $selected="", $name="id_category", $class="form-control"){
	$ci =& get_instance();
	$cmb = "<select name='$name' id='$name' class='$class $name'>";
	$cmb .= "<option value=''>Pilih Kategori</option>";
	$data = $ci->db->where('id_type', $id_type);
	$data = $ci->db->get('category')->result();
	foreach ($data as $d){
		$cmb .="<option value='".$d->id_category."'";
		$cmb .= $selected==$d->id_category?" selected='selected'":'';
		$cmb .=">".$d->category."</option>";
	}
	$cmb .="</select>";
	return $cmb;
}

function konversi_tanggal($waktu="", $format="") {
	//{tanggalIndoTiga tgl=0000-00-00 00:00:00 format="l, d/m/Y H:i:s"}
	if ($waktu != "" || $format != "") {
		if($waktu == "0000-00-00" || !$waktu || $waktu == "0000-00-00 00:00:00") {
			$rep = "";
		} else {
			if(preg_match('/-/', $waktu)) {
				$tahun = substr($waktu,0,4);
				$bulan = substr($waktu,5,2);
				$tanggal = substr($waktu,8,2);
			} else {
				$tahun = substr($waktu,0,4);
				$bulan = substr($waktu,4,2);
				$tanggal = substr($waktu,6,2);
			}
			$jam = substr($waktu,11,2);
			$menit= substr($waktu,14,2);
			$detik = substr($waktu,17,2);
			$hari_en = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
			$hari_id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
			$bulan_en = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$bulan_id = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
			$ret = @date($format, @mktime($jam, $menit, $detik, $bulan, $tanggal, $tahun));
			$replace_hari = str_replace($hari_en, $hari_id, $ret);
			$rep = str_replace($bulan_en, $bulan_id, $replace_hari);
			$rep = nl2br($rep);
		}
		return $rep;
	}
}

function format_duit($xx) {
	if (empty($xx)){
		return $xx;
	}else {
		$x = trim($xx);
		$b = number_format($x, 0, ",", ".");
		return $b;
	}
}

function form_select ($option = array()) {

	$hasil = "";

	if (count($option) > 0) {
		$conf = @$option['config'];
		$list = (empty($option['list'])) ? array() : $option['list'];
		$selected = @$option['selected'];
		$extra = @$option['extra'];
		
		if (
			!empty($list['db']) AND
			$list['db']['id'] AND
			$list['db']['title'] AND
			$list['db']['table']
		) {
			$id = $list['db']['id'];
			$title = $list['db']['title'];
			$table = $list['db']['table'];
			
			$ci =& get_instance();
			
			$sql = $ci->db
				->select("$id id, $title title")
				->get($table);
				
			$list = array("" => "");
			
			foreach ($sql->result() as $data) {
				$list += array(
					$data->id => $data->title
				);
			}
		}

		$hasil = form_dropdown($conf, $list, $selected, $extra);
	}

	return $hasil;
}

function get_loggeduser_role(){
	$ci =& get_instance();

	$logged_level = $ci->session->userdata('level');
	$id_user='0';
	$lvl='0';
	switch ($logged_level) {
		case 'admin':
			$id_user=$ci->session->userdata('id_admin');
			$lvl='1';
		break;

		case 'perusahaan':
			$id_user=$ci->session->userdata('id_pemrakarsa');
			$lvl='3';
		break;

		case 'pemrakarsa':
			$id_user=$ci->session->userdata('id_user');
			$lvl='2';
		break;
		
		default:   
		break;
	}

	$ci->db->where(array("user_id"=>$id_user,"lvl"=>$lvl));
	$dt=$ci->db->get('acl_user_role')->result_array();
	$logged_role=array();
	foreach ($dt as $r) {
	   $logged_role[]=$r['role_id'];
	}

	return $logged_role;
}

function get_accepted_menu($role){
	$ci =& get_instance();
	$ci->load->database();

	$accepted_menu=array();
	if(count($role)>0){
		$wherein=implode(',', $role);
		$result=$ci->db->query("SELECT * FROM acl_menu_role WHERE role_id IN (".$wherein.") ")->result();
		foreach ($result as $row) {
			$accepted_menu[]=$row->menu_id;
		}
	}

	return $accepted_menu;
}

if(!function_exists('display_menu_admin')) {
	function display_menu_admin($parent, $level) {
		$ci =& get_instance();
		$ci->load->database();
		
		$logged_role=get_loggeduser_role();
		$listmenu=get_accepted_menu($logged_role);

		// $ci->load->model('model_menu');
		$result = $ci->db->query("SELECT a.id, a.label,a.icon_color, a.type, a.link,a.icon, Deriv1.Count FROM `menu` a  LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count FROM `menu` GROUP BY parent) Deriv1 ON a.id = Deriv1.parent WHERE a.menu_type_id = 1 AND a.parent=" . $parent." order by `sort` ASC")->result();

		$ret = '';
		if ($result) {
			if (($level > 1) AND ($parent > 0) ) {
				$ret .= '<ul class="children">';
			} else {
				$ret = '';
			}
			foreach ($result as $row) {
				// $perms = 'menu_'.strtolower(str_replace(' ', '_', $row->label));

				$links = explode('/', $row->link);

				// $segments = array_slice($ci->uri->segment_array(), 0, count($links));
				
				// if (implode('/', $segments) == implode('/', $links)) {
				//     $active = 'active';
				// } else {
				//     $active = '';
				// }
				$active = '';
				
				$class = "mn-".strtolower(_ent(str_replace(" ", "", $row->label)));
				
				if ($row->type == 'label') {
					$ret .= '<li class="header">'._ent($row->label).'</li>';
				} else {
					if(in_array($row->id, $listmenu)){
						if ($row->Count > 0) {
							// if ($ci->aauth->is_allowed($perms)) {
								$ret .= '<li class="nav-parent '.$class.' '.$active.'"> 
														<a href="'.site_url($row->link).'">';

								if ($parent) {
									$ret .= '<span>'._ent($row->label).'</span></a>';
								} else {
									$ret .= '<i class="fa '._ent($row->icon).' '._ent($row->icon_color).'"></i> <span>'._ent($row->label).'</span></a>';
								}

								$ret .= display_menu_admin($row->id, $level + 1);
								$ret .= "</li>";
							// }
						} elseif ($row->Count==0) {
						   // if ($ci->aauth->is_allowed($perms)) {
								$ret .= '<li class="'.$class.' '.$active.'"> 
														<a href="'.site_url($row->link).'">';

								if ($parent) {
									$ret .= '<span>'._ent($row->label).'</span></a>';
								} else {
									$ret .= '<i class="fa '._ent($row->icon).' '._ent($row->icon_color).'"></i> <span>'._ent($row->label).'</span></a>';
								}

								$ret .= "</li>";
							// }
						}
					}
				}
			}
			if ($level != 1) {
				$ret .= '</ul>';
			}
		}

		return $ret;
	}
}

if(!function_exists('_ent')) {
	function _ent($string = null) {
		return htmlentities($string);
	}
}

// kirim_email (array(
//     "to" => "agusdiyansyah@gmail.com",
//     "from" => "ADMINISTRATOR",
//     "subject" => "NOTIFIKASI",
//     "msg" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
// ));
function kirim_email ($conf = array()) {
	$withCPanel = false;
	
	if ($withCPanel) {
		# code...
	} else {
		$ci =& get_instance();
		$ci->load->library('email');
		
		$config['charset'] = 'utf-8';
		$config['useragent'] = $_SERVER['HTTP_USER_AGENT']; //bebas sesuai keinginan kamu
		$config['protocol']= "smtp";
		$config['mailtype']= "html";
		$config['smtp_host']= "ssl://smtp.gmail.com";
		$config['smtp_port']= "465";
		$config['smtp_timeout']= "10";
		$config['smtp_user']= "tes1890@gmail.com";//isi dengan email kamu
		$config['smtp_pass']= "diyan8@coba"; // isi dengan password kamu
		$config['crlf']="\r\n"; 
		$config['newline']="\r\n"; 
		$config['wordwrap'] = TRUE;
		
		$ci->email->initialize($config);
		$ci->email->to($conf['to']);
		$ci->email->from($conf['from']);
		$ci->email->subject($conf['subject']);
		$ci->email->message($conf['msg']);
		
		if ($ci->email->send()) {
			return true;
		} else {
			return false;
		}
		
		if ($debug) {
			$ci->email->send();
			return $ci->email->print_debugger();
		}
	}
	
}

function kawasan () {
	$kawasan = array(
		"kawasan_apl" => "Areal Penggunaan Lain",
		"kawasan_hp" => "Kawasan Hutan Produksi",
		"kawasan_l" => "Kawasan Lindung",
		"kawasan_bldkl" => "Berbatasan Langsung Dengan Kawasan Lindung",
		"kawasan_eg" => "Ekosistem Gambut"
	);
	return $kawasan;
}

function combobox_perusahaan ($selected="") {
	$ci =& get_instance();

	$level = $ci->session->userdata('level');
	$ci->db
		->where('isdelete', '0')
		->select('id_pemrakarsa, pemrakarsa_nama');

	if ($level == "pemrakarsa") {
		$id_user = $ci->session->userdata('id_user');
		$ci->db->where('id_user', $id_user);
	}
	
	$q = $ci->db->get('pemrakarsa');
	return combobox_dynamic("id_pemrakarsa",$q,"pemrakarsa_nama","id_pemrakarsa",$selected);
}

function admin_info ($id_admin, $table="admin") {
	$ci =& get_instance();
	if ($table == "admin") {
		$q = $ci->db->where('id_admin', $id_admin);
	}
	else if ($table == "user") {
		$q = $ci->db->where('id_user', $id_admin);
	}
	$q = $ci->db->get($table);
	return $q->row();
}

function combobox_status ($selected="") {
	$ci =& get_instance();

	// $level = $ci->session->userdata('level');
	$q = $ci->db->select('status, status_label');
	
	$q = $ci->db->get('kegiatan_status');
	return combobox_dynamic("status", $q, "status_label" ,"status", $selected, "&nbsp", "form-control s2 status");
}

function kegiatan_log ($id_kegiatan, $aksi) {
	$ci =& get_instance();
	$level = $ci->session->userdata('level');
	$aksi_user_type = $level;
	if ($level == "user" OR $level == "pemrakarsa") {
		$aksi_user_id = $ci->session->userdata('id_user');
	}
	else if ($level == "perusahaan") {
		$aksi_user_id = $ci->session->userdata('id_pemrakarsa');
	}
	else if ($level == "admin") {
		$aksi_user_id = $ci->session->userdata('id_admin');
	}
	$data['id_kegiatan'] = $id_kegiatan;
	$data['aksi'] = $aksi;
	$data['aksi_user_type'] = $aksi_user_type;
	$data['aksi_user_id'] = $aksi_user_id;
	$ci->db->insert('kegiatan_log', $data);
}

function jadwalSidang ($id_kegiatan) {
	$ci =& get_instance();
	$q = $ci->db->where('id_kegiatan', $id_kegiatan);
	$q = $ci->db->get('kegiatan_sidang');
	if ($q->num_rows()>0) {
		$row = $q->row();
		$data['status'] = true;
		$data['id_sidang'] = $row->id_sidang;
		$data['sidang_jadwal'] = $row->sidang_jadwal;
		$data['sidang_tempat'] = $row->sidang_tempat;
		$data['sidang_keterangan'] = $row->sidang_keterangan;
	}
	else {
		$data['status'] = false;
	}
	return json_encode($data);
}

function kegiatanInfo ($id_kegiatan) {
	$ci =& get_instance();
	$ci->load->model('kegiatan/M_kegiatan');
	$query = $ci->M_kegiatan->cekId($id_kegiatan);
	if ($query->num_rows()>0) {
		$row  = $query->row();
		$json['status'] = true;
		$json['message'] = "Data ada";
		$json['data'] = $row;
		
	}
	else {
		$json['status'] = false;
		$json['message'] = "Data Tidak Ada";
		$json['data'] = null;
	}
	return json_encode($json);
}

function status () {
	$ci =& get_instance();
	$sql = $ci->db->get("kegiatan_status");
	$arr = array();
	foreach ($sql->result() as $data) {
		$arr[$data->status] = array(
			"status" => $data->status, 
			"label" => $data->status_label, 
			"keterangan" => $data->status_deskripsi, 
		);
	}
	return $arr;
}

function noHpAdmin()
{
	$ci =& get_instance();
	$handphone = array();
	$q = $ci->db->select('handphone');
	$q = $ci->db->where('handphone !=', '');
	$q = $ci->db->where("level", "super");
	$q = $ci->db->get("admin");
	foreach ($q->result() as $key) {
		array_push($handphone, $key->handphone);
	}
	return $handphone;
}