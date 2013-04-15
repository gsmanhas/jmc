<?php
class Zipcode_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

	function zipcde_wise_tax($ship_zipcode) 
		{
			
			$is_show_taxrate = 'no';
			$Dest = $this->session->userdata('DestinationState');
			if (11 == $Dest[0]['id']) {
				
				$this->db->select('*');
				$this->db->from('zipcodes');
				$this->db->where('zipcodes', $ship_zipcode);
				$this->db->where('status', 'Y');
				$zipcodes = $this->db->get()->row();
				
				if($zipcodes){ 
					$is_show_taxrate = $zipcodes->taxrate;
				}
				
			}
			
			return $is_show_taxrate;
        
    }
	

		
}
?>
