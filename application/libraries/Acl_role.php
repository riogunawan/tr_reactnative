<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_role
{
	protected $CI;
	protected $permissions;

	public function __construct()
	{
        $this->CI =& get_instance();
       	$this->permissions = array();
	}

	public function getRolePerms($role_id) {
		$role = new Acl_role();
        $query=$this->CI->db->query("SELECT t2.perm_desc FROM acl_role_perm as t1
                JOIN acl_permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.role_id='".$role_id."'");
        foreach ($query->result_array() as $row)
		{
			$role->permission[$row['perm_desc']]=true;
		}

		return $role;
    }

	public function hasPerm($permission) {
		return isset($this->permission[$permission]);
		/*if(isset($this->permission[$permission])){
			return true;
		}else{
			return false;
		}*/
    }
}