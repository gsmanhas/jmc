<?php

/**
* 
*/
class Reviews extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		if ($_POST) {
			
			if ($_POST['action'] == "order" && $_POST['id'] != "") {	//	Save Order.
				$this->_save_order();
			} else if ($_POST['action'] == "publish" && $_POST['id'] != "") {	// Publish or unPublish
				$this->_publish();
			} else if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
				$this->_remove($_POST['id']);
			}
			
		}
		
		// $result = $this->db->query(
		// 	"SELECT title, message, rate, created_at, pid, (SELECT name FROM product WHERE id = pid) as `name`, count(id) as 'records' FROM product_review" .
		// 	" WHERE is_delete = 0 GROUP BY pid order by name asc"
		// );
		
		$result = $this->db->query("SELECT pid, (SELECT `name` FROM product WHERE id = pid) as 'name' FROM product_review GROUP BY pid ORDER BY pid ASC");
		$this->Products = $result->result();
		// echo $this->db->last_query();
		
		$this->load->view('reviews');
	}
	
	public function allpublish()
	{
		$this->db->query("UPDATE  product_review SET publish = 1  WHERE is_delete = 0 ");
		redirect('reviews');
	}
	
	public function _publish()
	{		
		$Publish = array(
			'publish' => $this->input->post('publish_state'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);	
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('product_review', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update product_review set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
					$numrows += $this->db->affected_rows();
				}
								
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE) {
					$this->error_message = "發生了異常的動作, 系統取消了上次的動作";
					$this->db->trans_rollback();
				} else {
					if ($numrows != 0) {
						$this->message =  $numrows . " records deleted";	
					}
				}
			}
		}
	}

	public function search()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		//	SELECT pid, (SELECT name FROM product WHERE id = pid) as `name`, count(id) as 'records'
		
		$this->db->select("*");
		$this->db->from("product_review");
		$this->db->where('is_delete = 0');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('created_at >=', $release_date);
		}

		if ($expiry_date != "") {
			$this->db->where('created_at <=', $expiry_date);
		}
		
		// $this->db->order_by('name', 'asc');
		
		$this->Reviews = $this->db->get();
		// echo $this->db->last_query();
		
		$this->load->view('reviews_result');
		
	}

	public function _save_order()
	{
		$numrows = 0;
		if (count($_POST['lists']) != count($_POST['order'])) {
			$this->message = "Oops 排序資料不同步";
		} else {
			$lists  = $_POST['lists'];
			$orders = $_POST['order'];
			$i = 0;
			foreach ($lists as $list) {
				$Ordering = array('ordering' => $orders[$i]);
				$this->db->where('id', $list);
				$this->db->update('product_review', $Ordering);
				
				$numrows += $this->db->affected_rows();
				// echo "$q";
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}

	}
	
}