<?php
class Cart_shipping_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	function getStates($cid){
		
		$query = $this->db->query("SELECT t.id, s.state,
                                         t.state_id,
                                         t.tax_code, t.tax_rate
                                    FROM tax_codes as t, state as s
                                    where is_delete = 0 && s.id = t.state_id && s.country_id = ?
                                    ORDER BY `sharthand` ASC", $cid);
		return $query->result();
		
	}
	
	public function getShippingMethod()
	{
		$query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 order by `price` asc");
		return $query->result();
	}
	
	public function OnlineShippingMethod()
	{
		$query = $this->db->query("SELECT `id`, `name` FROM shipping_method where is_delete = 0 AND id = 99");
		return $query->row();
	}
	
	public function DestinationState($opt = 0)
	{
		if ($opt == -1) {
			$this->session->set_userdata('DestinationState', array());
		} else {
			$Query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $opt);
			$DestinationState = $Query->result_array();
			if (count($DestinationState) >= 1) {
				$this->session->set_userdata('DestinationState', $DestinationState);
			} else {
				$this->session->set_userdata('DestinationState', $DestinationState);
			}
		}
	}
		
}



?>
