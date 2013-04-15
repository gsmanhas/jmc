<?php

/**
* 
*/
class Product_sorting extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		if ($_POST) {
			if ($_POST['action'] == "order" && $_POST['id'] != "") {	//	Save Order.
				$this->save_order();
			} else if ($_POST['id'] != "" && $_POST['sorting'] = "sorting") {
				$this->sortingTable();
				return;
			}
		}
		
		$result = $this->db->query("SELECT * FROM product_catalogs WHERE is_delete = 0 ORDER BY ordering asc");
		$this->catalogs = $result->result();
		
		$this->load->view('product_sorting');
	}
	
	public function sortingTable()
	{
		$result = $this->db->query(
			"SELECT pc.id, p.id as 'pid', p.name, p.small_image, pc.sorting, pc.cid, pc.show_it " .
			"FROM product as p LEFT JOIN product_rel_catalog as pc " .
			"ON p.id = pc.pid " .
			"WHERE pc.cid = ? ORDER BY pc.sorting asc", $this->input->post('id')
		);
		
		$this->catalogs = $this->db->query(
			"SELECT name FROM product_catalogs WHERE id = ? AND is_delete = 0 ORDER BY ordering asc", $this->input->post('id')
		)->result();
		
		$this->products = $result->result();
		$this->load->view('product_sorting_table');
	}
	
	public function save_order()
	{
		$numrows = 0;
		if (count($_POST['lists']) != count($_POST['order'])) {
			$this->message = "Oops ... Please try again.";
		} else {
			$lists  = $_POST['lists'];
			$orders = $_POST['order'];
			$i = 0;
			foreach ($lists as $list) {
								
				$SHOW_OR_HIDE = (isset($_POST["hide_or_show_".$list])) ? $_POST["hide_or_show_".$list] : 0;
				
				// echo $SHOW_OR_HIDE.br(1);
				
				$Ordering = array(
					'sorting' => $orders[$i],
					'show_it' => $SHOW_OR_HIDE,
					'updated_at' => unix_to_human(time(), TRUE, 'us')
				);
				
				$this->db->where('id', $list);
				$this->db->update('product_rel_catalog', $Ordering);
				
				$numrows += $this->db->affected_rows();
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}
	}
	
}