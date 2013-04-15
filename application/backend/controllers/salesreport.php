<?php

/**
* 
*/
class Salesreport extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		$this->load->view('sales_report');
	}	

	public function export()
	{
		$rep = new Reporting();
		$rep->export_sales_report("Sales Report", $this->input->post('last_query'));
		$this->index();
	}
	
	public function export_sku()
	{
		$rep = new Reporting();
		$rep->export_sales_report_sku("Sales Report", $this->input->post('last_query'));
		$this->index();
	}
	
	public function search()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		if ($this->input->post('groupType') == 'byDay') {
			
			if ($this->input->post('productType') == 'all') {
			
			$expiry_date = strtotime ( '1 day' , strtotime ( $expiry_date ) ) ;
			$expiry_date = date ( 'Y-m-d' , $expiry_date );
				
				$this->db->select(
					"DISTINCT DATE_FORMAT(order_date, '%Y-%m-%d') as 'order_date'"
				, FALSE);
				
				// $this->db->select(
				// 	"order_date, order_no, amount," .
				// 	" DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
				// 	" DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
				// 	" DATE_FORMAT(o.order_date, '%T') as 'otime'," .
				// 	"(select sum(qty) from order_list as ol where ol.order_id = o.id group by order_id) as `orders`"
				// , FALSE);
				
				$this->db->from("`order` as o");
				$this->db->order_by('order_date', 'asc');

				if ($release_date != "" && $expiry_date != "") {
					$this->db->where('o.order_date >=', $release_date);
				}

				if ($expiry_date != "") {
					$this->db->where('o.order_date <=', $expiry_date);
				}
				
				$this->db->where('o.is_delete = 0');
				
				$this->db->where('order_state != 5 AND order_state != 6');
								
				$sales_report = $this->db->get()->result();

				// echo $this->db->last_query();

				if (count($sales_report) >= 1) {
					$this->last_query = $this->db->last_query();
					$this->sales_report = $sales_report;
					$this->load->view("sales_report_result");
				} else {
					$this->update_message = "There is no record based on your search";
					$this->index();
				}

			} else if ($this->input->post('productType') == 'sku') {
				
				$this->db->select(
					"(SELECT sku FROM product as p WHERE p.id = ol.pid) as 'sku'," .
					"(SELECT item_number FROM product as p WHERE p.id = ol.pid) as 'item_number'," .
					"(SELECT name FROM product as p WHERE p.id = ol.pid) as 'product'," .
					"o.order_date, ol.qty, ol.price, (ol.qty * ol.price) as 'sales', ol.price as 'unit_price'," .
					" DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
					" DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
					" DATE_FORMAT(o.order_date, '%T') as 'otime'," .
                            "o.order_no as 'invoice_number'"
				, FALSE);
				$this->db->from('`order` as o');
				$this->db->join('order_list as ol', 'o.id = ol.order_id', 'left');
				// $this->db->group_by('sku');
				
				if ($release_date != "" && $expiry_date != "") {
					$this->db->where('o.order_date >=', $release_date);
				}

				if ($expiry_date != "") {
					$this->db->where('o.order_date <=', $expiry_date);
				}
				
				$this->db->where('o.is_delete = 0');
				
				$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6 AND ol.item_type = \'product\'');
				
				$this->db->order_by('sku', 'asc');
				
				$sales_report = $this->db->get()->result();

				if (count($sales_report) >= 1) {
					$this->last_query = $this->db->last_query();
					$this->sales_report = $sales_report;
					$this->load->view("sales_report_sku_result");
				} else {
					$this->update_message = "There is no record based on your search";
					$this->index();
				}
				
			} else if ($this->input->post('productType') == 'category') {
				
			}
		
		} else if ($this->input->post('groupType') == 'byWeek') {
			
			echo $release_date.br(1);
			echo $expiry_date.br(1);
			
			// echo strtotime($release_data, 'W');
			
			// echo date('W', strtotime($release_date)).br(1);
			// echo date('W', strtotime($expiry_date)) .br(1);
			
			// $today = getdate(strtotime($expiry_date));
			// print_r($today);
			
			
			
			
			// 
			// $ts = strtotime($release_date);
			// $year = data('o', $ts);
			// $week = data('W', $ts);
			// for ($i=1; $i <= 7; $i++) { 
			// 	$ts = strtotime($year, 'W'.$week.$i);
			// 	print date("m/d/Y l", $ts) . "\n";
			// }
			
			// // set current date
			// $date = '04/30/2009';
			// // parse about any English textual datetime description into a Unix timestamp
			// $ts = strtotime($date);
			// // calculate the number of days since Monday
			// $dow = date('w', $ts);
			// $offset = $dow - 1;
			// if ($offset < 0) $offset = 6;
			// // calculate timestamp for the Monday
			// $ts = $ts - $offset*86400;
			// // loop from Monday till Sunday
			// for ($i=0; $i<7; $i++, $ts+=86400){
			//     print date("m/d/Y l", $ts) . br(1);
			// }
			
			// // set current date
			// $date = '04/30/2009';
			// // parse about any English textual datetime description into a Unix timestamp
			// $ts = strtotime($date);
			// // find the year (ISO-8601 year number) and the current week
			// $year = date('o', $ts);
			// $week = date('W', $ts);
			// // print week for the current date
			// for($i=1; $i<=7; $i++) {
			//     // timestamp from ISO week date format
			//     $ts = strtotime($year.'W'.$week.$i);
			//     print date("m/d/Y l", $ts) . br(1);
			// }
			
			// getWeekRange($release_date, $expiry_date);
			// echo "$release_date $expiry_date";
			// 
			// getMonthRange($start, $end);
			// echo "$start $end";
			
			
			
			
		} else if ($this->input->post('groupType') == 'byMonth') {
			
		} else if ($this->input->post('groupType') == 'byYear') {
			$this->db->select(
				"order_date, order_no, amount," .
				"(select sum(qty) from order_list as ol where ol.order_id = o.id group by order_id) as `orders`"
			);
			$this->db->from("order as o");
			$this->db->order_by('order_date', 'asc');
			
			$this->db->where('o.is_delete = 0');
			
			if ($release_date != "" && $expiry_date != "") {
				// $this->db->where('o.order_date >=', $release_date);
				$this->db->where("DATE_FORMAT('o.order_date', '%Y') >=", substr($release_date, 0, 4));
			}

			if ($expiry_date != "") {
				// $this->db->where('o.order_date <=', $expiry_date);
				$this->db->where("DATE_FORMAT('o.order_date', '%Y') <=", substr($expiry_date, 0, 4));
			}
			
		}
						

	}

	function getWeekRange(&$start_date, &$end_date, $offset=0) {
	        $start_date = '';
	        $end_date = '';   
	        $week = date('W');
	        $week = $week - $offset;
	        $date = date('Y-m-d');

	        $i = 0;
	        while(date('W', strtotime("-$i day")) >= $week) {                       
	            $start_date = date('Y-m-d', strtotime("-$i day"));
	            $i++;       
	        }   

	        list($yr, $mo, $da) = explode('-', $start_date);   
	        $end_date = date('Y-m-d', mktime(0, 0, 0, $mo, $da + 6, $yr));
	}

	    function getMonthRange(&$start_date, &$end_date, $offset=0) {
	        $start_date = '';
	        $end_date = '';   
	        $date = date('Y-m-d');

	        list($yr, $mo, $da) = explode('-', $date);
	        $start_date = date('Y-m-d', mktime(0, 0, 0, $mo - $offset, 1, $yr));

	        $i = 2;

	        list($yr, $mo, $da) = explode('-', $start_date);

	        while(date('d', mktime(0, 0, 0, $mo, $i, $yr)) > 1) {
	            $end_date = date('Y-m-d', mktime(0, 0, 0, $mo, $i, $yr));
	            $i++;
	        }
	}
	
}