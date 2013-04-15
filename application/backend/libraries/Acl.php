<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Timur Khamrakulov
 * Date: 05.04.12
 * Time: 14:00
 */
class Acl
{
    private $db;
    private $CI;
    private $resource;
    private $user;

    public function checkAccess($resouce, $user) {
        $this->CI = get_instance();
        $this->db = $this->CI->db;
        $this->resource = $resouce;
        $this->user = $user;


        $sql = "SELECT *
                    FROM acl, acl_resource
                    WHERE acl.manager_id = {$this->user}
                        AND acl.resource_id = acl_resource.id
                        AND acl_resource.resource_name = '{$this->resource}'";

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
}
