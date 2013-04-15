<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
* 
*/
class MY_Controller extends CI_Controller
{

	//	Dynamic Menu
	public $dynamicMenus = '';
	
	function __construct()
	{
		parent::__construct();
		// if ($this->session->userdata('ip_address') == "0.0.0.0") {
		// 	$this->output->enable_profiler(TRUE);
		// }
		// $this->output->enable_profiler(TRUE);
		$this->_init();
	}
	
	private function _init() {
		
		// //	Query Footer Menu.
		// $result = $this->db->query('SELECT m.id, m.title, m.url ' .
		// 'FROM ' .
		// 'menus as m join dynamic_menus as dm on dm.id = m.dynamic_id ' .
		// 'where dm.title = \'footer\' ' .
		// 'and m.is_delete = 0 and dm.is_delete = 0');
		// $this->footers = $result->result();
		
		//	Query Product Catalogs 
		$result = $this->db->query('SELECT * FROM product_catalogs WHERE is_delete = 0 and publish = 1 and is_delete=0 order by ordering asc');
		$this->product_menus = $result->result();
		
		$this->getMenu();
		
		$this->db->select('*');
		$this->db->from('footer_menus');
		$this->db->where('is_delete', 0);
		$this->db->where('publish', 1);
		$this->db->order_by('ordering', 'asc');
		$this->FooterMenus = $this->db->get()->result();
		
		// $this->Recurrsive(0, 7);
		
	}
	
	public function _remap($method)
	{
				
		//	目前不開放第二層
		if ($this->uri->total_segments() >= 2) {
			show_404('page');
		}
				
		$result = $this->db->query(
			"SELECT * FROM webpages WHERE is_delete = 0 AND publish = 1" .
			" AND page_url = '" . $this->uri->segment(1, 0) . "'" .
			" Order By id asc"
		);
		
		$this->webpage = $result->result();
		
		// echo count($this->webpage);
		
		if (count($this->webpage) >= 1) {
			//	載入 WebPage
			$this->load->view('webpage');
		} else if ($this->uri->segment(1, '/') == '/') {
			$this->index();
		} else if ($this->uri->segment(1) == 'admin') {
			redirect('admin.php/signin', 'refresh');
		} else {
			show_404('page');
		}
	}
	
	// /**
	// * 顯示 Dynamic Menu Tree
	// */
	// public function Recurrsive($n, $dynamic_id = 0)
	// {		
	// 	$result = $this->db->query(
	// 		"SELECT * FROM menus WHERE is_delete = 0 AND parent = " . $n .
	// 		" AND dynamic_id = " . $dynamic_id .
	// 		" Order By id asc"
	// 	);		
	// 	$records = $result->result();
	// 	if (count($records) > 0) {
	// 		if ($this->dynamicMenus == '') {
	// 			// $this->dynamicMenus .= "<ul id=\"nav\" class=\"treeview\">";
	// 			// $this->dynamicMenus .= "<ul>";
	// 			$this->dynamicMenus .= "<li><ul>";
	// 		} else {
	// 			// $this->dynamicMenus .= "<ul>";
	// 			$this->dynamicMenus .= "<li><ul>";
	// 		}
	// 		
	// 		foreach ($records as $record) {
	// 			$this->dynamicMenus .= "<a href='" . base_url() . $record->url . "' >" . $record->title . "</a>";
	// 			$this->Recurrsive($record->id, $dynamic_id);
	// 		}
	// 		$this->dynamicMenus .= "</li>";
	// 
	// 		$this->dynamicMenus .= "</ul>";
	// 	}
	// }
	
	/**
	* 顯示 Dynamic Menu Tree
	*/
	public function Recurrsive($n, $dynamic_id = 0)
	{		
		$result = $this->db->query(
			"SELECT * FROM menus WHERE is_delete = 0 AND parent = 1" . $n .
			" AND dynamic_id = " . $dynamic_id .
			" Order By id asc"
		);		
		$records = $result->result();
		if (count($records) > 0) {
			if ($this->dynamicMenus == '') {
				$this->dynamicMenus .= "<ul id=\"nav\" class=\"treeview\">";
			} else {
				$this->dynamicMenus .= "<ul>";
			}
			
			foreach ($records as $record) {
				$this->dynamicMenus .= "<li><a href='" . base_url() . $record->url . "' >" . $record->title . "</a>";
				$this->Recurrsive($record->id, $dynamic_id);
			}
			$this->dynamicMenus .= "</li>";

			$this->dynamicMenus .= "</ul>";
		}
	}
	
	public function getMenu()
	{
		$this->db->select('*');
		$this->db->from('menus');
		$this->db->where('is_delete', 0);
		$this->db->where('parent', -1);
		$this->db->order_by('ordering', 'asc');
		$this->dynamicMenus = $this->db->get()->result();
		
	}
	
	public function getCategory()
	{
		echo 'Ya Allah Khair'; die;
		
	}
	
}
