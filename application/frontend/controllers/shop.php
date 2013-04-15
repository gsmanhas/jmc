<?php
/**
* 
*/
class Shop extends MY_Controller
{
	
	function __construct()
	{	
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function index()
	{
					
		if (strtolower($this->uri->segment(1, 0)) == "shop") {
			
			// if (strtolower($this->uri->segment(3, 0) == "sort-by")) {
			// 	if (strtolower($this->uri->segment(4, 0) == "price")) {
			// 		$sortBy = "price";
			// 	}
			// 	if (strtolower($this->uri->segment(4, 0) == "name")) {
			// 		$sortBy = "name";
			// 	}
			// }
						
			if ($this->uri->segment(3, 0) != "0" && ($this->uri->segment(3, 0) != "sort-by")) {	
				
				$query = $this->db->query(
					'SELECT p.* ' .
					' FROM product as p WHERE is_delete = 0 AND url = \'' . $this->uri->segment(3, 0) . '\''
				);
				
				$this->product = $query->result();
				
				
				if (count($this->product) >= 1) {
					
					$query = $this->db->query(
						'SELECT ps.id, ps.image, ps.title, ps.description' .
					    ' FROM ' .
					    'product_rel_symbolkey as prs ' .
					    'JOIN ' .
					    'product_symbolkey as ps ' .
					    'ON prs.sid = ps.id ' .
					    'WHERE prs.pid = ' . $this->product[0]->id .
					    ' ORDER by ps.title asc');
					
					$this->symbolkeys = $query->result();
					
					$query = $this->db->query(
						'SELECT ps.id, ps.image, ps.title, ps.description' .
					    ' FROM ' .
					    'product_rel_ingredients as prs ' .
					    'JOIN ' .
					    'product_ingredients as ps ' .
					    'ON prs.ing_id = ps.id ' .
					    'WHERE prs.pid = ' . $this->product[0]->id .
					    ' ORDER by ps.title asc');
					
					$this->ingredients = $query->result();
					
					$query = $this->db->query("SELECT with_id FROM works_well_with WHERE pid = " . $this->product[0]->id);
					$this->Works_Well_With = $query->result();
					
					$CatalogInfo = $this->db->query('SELECT * from product_catalogs WHERE url = \'' . $this->uri->segment(2, 0) . '\'');
					$this->Catalog = $CatalogInfo->result();
					
					$this->load->view('product_details');
					
				} else {
					redirect('page-not-found', 'refresh');
				}
				
			} else if ($this->uri->segment(2, 0) != "0") {
				
				//	Product List
				
				$CatalogInfo = $this->db->query('SELECT * from product_catalogs WHERE url = \'' . $this->uri->segment(2, 0) . '\'');
								
				$this->Catalog = $CatalogInfo->result();
				
				//	先檔掉亂輸入的內容, 直接跳到 /shop
				if (count($this->Catalog) <= 0) {
					redirect('/shop');
				}
								
				// $result = $this->db->query(
				// 	'SELECT p.* ' .
				// 	'FROM product as p WHERE id in(' .
				// 	'SELECT prc.pid as \'pid\' FROM product_catalogs as pc ' .
				// 	'join product_rel_catalog prc ' .
				// 	'on prc.cid = pc.id ' .
				// 	'where pc.url = \'' . $this->uri->segment(2, 0) .  '\'' .
				// 	') and p.publish = 1 and p.is_delete = 0 ORDER by name asc'
				// );
				
				$SORT_BY = 'sorting';	
				$DESC_OR_ASC = "ASC";			
				if (isset($_POST['sort_by'])) {
					$SORT_BY = $_POST['sort_by'];
					if ($SORT_BY == "rating") {
						$DESC_OR_ASC = "DESC";
					}
				}

				$result = $this->db->query(
					'SELECT p.* , ' .
					'(SELECT sorting FROM product_rel_catalog as s WHERE s.pid = p.id AND cid = ' . $this->Catalog[0]->id . ') as \'sorting\',' .
					// 'IFNULL((SELECT rate FROM product_review WHERE pid = p.id), 0) as \'rating\' ' .
					'IFNULL((SELECT ROUND(SUM(rate), 2) FROM product_review WHERE pid = p.id AND is_delete = 0 AND publish = 1), 0) as \'rating\'' .
					'FROM product as p WHERE id in(' .
					'SELECT prc.pid as \'pid\' FROM product_catalogs as pc ' .
					'join product_rel_catalog prc ' .
					'on prc.cid = pc.id ' .
					'where prc.show_it = 1 and pc.url = \'' . $this->uri->segment(2, 0) .  '\'' .
					') and p.publish = 1 and p.is_delete = 0 ORDER BY `'. $SORT_BY .'` ' . $DESC_OR_ASC
				);


				// echo $this->db->last_query();
				
				$this->products = $result->result();
                

                if($this->Catalog[0]->type == 1 || $this->Catalog[0]->type == 0) {
                    $this->load->view('product_list');
                } elseif($this->Catalog[0]->type == 2) {
                    $this->load->view('product_list_advanced');
                }


				
			} else {
				//	若只有指到 SHOP 就會自動跳到產品分類中的第一個 URL
				$CatalogInfo = $this->db->query("SELECT * FROM product_catalogs where ordering >= 1 order by ordering asc");
				$this->Catalog = $CatalogInfo->result();
				if (count($this->Catalog) >= 1) {
					redirect('shop/' . $this->Catalog[0]->url, 'refresh');
				} else {
					redirect('page-not-found', 'refresh');
				}
			}
			
		} else {
			// redirect('page-not-found', 'refresh');
		}
	}
	
	public function _remap()
	{
		$this->index();
	}
}

