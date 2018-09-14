<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_privilegeduser
{
	private $roles;
	protected $CI;

    public function __construct() {
        // parent::__construct();
        $this->CI =& get_instance();
    }

    public function getByUsername($username) {
    	$query=$this->CI->db->query("SELECT id_admin,username FROM admin WHERE username='".$username."' LIMIT 1");
        $result = $query->row_array();

        if (!empty($result)) {
            $privUser = new Acl_privilegeduser();
            $privUser->initRoles($result['id_admin'],1);
            return $privUser;
        } else {
            return false;
        }
    }

    public function getByEmail($email) {
    	$query=$this->CI->db->query("SELECT id_user,email FROM user WHERE email='".$email."' LIMIT 1");
        $result = $query->row_array();

        if (!empty($result)) {
            $privUser = new Acl_privilegeduser();
            $privUser->initRoles($result['id_user'],2);
            return $privUser;
        } else {
            return false;
        }
    }

    protected function initRoles($user_id,$lvl) {
        $this->roles = array();
        $this->CI->load->library('Acl_role');
        $query=$this->CI->db->query("SELECT t1.role_id, t2.role_name FROM acl_user_role as t1
                JOIN acl_roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.user_id ='".$user_id."' AND t1.lvl='".$lvl."' ");
        foreach ($query->result_array() as $row)
		{
			$this->roles[$row["role_name"]] = $this->CI->acl_role->getRolePerms($row["role_id"]);
		}
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }
}