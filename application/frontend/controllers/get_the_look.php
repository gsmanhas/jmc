<?php

/**
* 
*/
class Get_the_look extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function _remap()
	{
		
		switch (strtolower($this->uri->segment(2, 0))) {
			
			case "allbuy" :
				$this->allbuy();
			break;
			case "entry" :
				$this->entry();
			break;
			case "add2wish" :
				$this->add2wish();
			break;
			default :
				$this->index();
			break;
		}
		
	}
	
	public function entry()
	{
		$ndx = strtolower($this->uri->segment(3, 0));
		$query = $this->db->query("SELECT * FROM get_the_look WHERE id = ?", $ndx);
		$this->get_the_look = $query->result();
		
		$Query_on_sale_price = $this->db->query(
			"SELECT price, in_stock, can_pre_order FROM product " .
			"WHERE id in(" .
			"SELECT pid FROM get_the_look_rel_product WHERE look_id = ? ORDER BY pid asc" .
			") AND on_sale = 1", $ndx
		)->result();
		
		$Query_retail_price = $this->db->query(
			"SELECT price, in_stock, can_pre_order FROM product " .
			"WHERE id in(" .
			"SELECT pid FROM get_the_look_rel_product WHERE look_id = ? ORDER BY pid asc" .
			") AND on_sale = 0", $ndx
		)->result();
		
		$on_sale_price = 0;
		foreach ($Query_on_sale_price as $item) {
			if ($item->can_pre_order == 1) {
				$on_sale_price += $item->price;
			} else {
				if ($item->in_stock >= 1) {
					$on_sale_price += $item->price;
				}
			}
		}
				
		$on_retail_price = 0;
		foreach ($Query_retail_price as $item) {
			if ($item->can_pre_order == 1) {
				$on_retail_price += $item->price;
			} else {
				if ($item->in_stock >= 1) {
					$on_retail_price += $item->price;
				}
			}
		}
		
		
		// $total = ($Query_on_sale_price[0]->price + $Query_retail_price[0]->price);
		$this->LOOK_PRICE = number_format(($on_sale_price + $on_retail_price), 2);
		
		$this->load->view('get_the_look_detail');
		
	}
	
	public function index()
	{		
		$query = $this->db->query("SELECT * FROM get_the_look WHERE publish = 1 AND is_delete = 0 ORDER BY ordering asc");
		$this->get_the_look = $query->result();
		
		$this->load->view('get_the_look');
	}
	
	
	public function allbuy()
	{
		$ndx = strtolower($this->uri->segment(3, 0));
		$this->ShoppingCart->get_the_look_all_buy($ndx);
		
		// redirect('viewcart', 'refresh');
		header("Location: https://www.josiemarancosmetics.com/viewcart");
	}
	
	public function add2wish()
	{
		$ndx = strtolower($this->uri->segment(3, 0));
		$this->ShoppingCart->all_add_to_wish($ndx);
		redirect('myaccount/wishlist', 'refresh');
	}
	
}
