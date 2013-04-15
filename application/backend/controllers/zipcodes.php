<?php



/**

* 

*/

class Zipcodes extends CI_Controller

{

	

	function __construct()

	{

		parent::__construct();

	}

	

	public function index()

	{

		

		if ($_POST) {

			if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.

				$this->_remove($_POST['id']);

			} else if ($_POST['action'] == "publish" && $_POST['id'] != "") {	// Publish or unPublish

				$this->_publish($_POST['id']);

			} else if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode

				$this->_update($_POST['id']);

				return;

			} else if ($_POST['action'] == "update_save" && $_POST['id'] != "") {	//	Save Update Data.

				if ($this->_update_save() == false) {

					return;

				}

			}

		}

		$q_rows_zipcodes = $this->db->query("SELECT * FROM zipcodes order by id desc");
		
		$this->load->library('pagination');
		$config['base_url'] = $this->config->item('base_url').'/admin.php/zipcodes/index/';
		$config['total_rows'] = $q_rows_zipcodes->num_rows();
		$config['per_page'] = '250';
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['num_links'] = '10';
		
		$config['cur_tag_open'] = '<span class="emm-page emm-current">';
		$config['cur_tag_close'] = '</span>';

		$config['first_link'] = 'First';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
		
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['uri_segment'] = '3';
		$this->pagination->initialize($config);

		
		$limit = $config['per_page'];
		$offset = 0;
		if($this->uri->segment(3)){
			$offset = $this->uri->segment(3);
		}
		$result = $this->db->query("SELECT * FROM zipcodes order by id desc limit $offset, $limit");
		//$this->db->limit($config['per_page'], $this->uri->segment(3));
		$this->Zipcodes = $result->result();

		$this->load->view('allzipcodes.php');

	}

	
	

	public function addnew()

	{


		$this->load->view('addnew_zipcode');

	}

	

	private function _submit_validate()

	{		

		$this->form_validation->set_rules('is_active', 'Publish', 'required');		
		$this->form_validation->set_rules('zipcode', 'Zipcode', 'required');		
		$this->form_validation->set_rules('tax_rate', 'Tax Rate', 'required');
		return $this->form_validation->run();

	}

	

	public function save()

	{

		if ($this->_submit_validate() === FALSE) {

			$this->addnew();

			return;

		}

		

		$Zipcode = array(
			'zipcodes'      => $this->input->post('zipcode'),
			'taxrate'    => $this->input->post('tax_rate'),
			'status'        => $this->input->post('is_active'),
		);

		

		$this->db->trans_start();

		$this->db->insert('zipcodes', $Zipcode);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Zipcodes->save() 交易錯誤');

		}

		

		redirect('zipcodes/success', 'refresh');	

	}



	public function success()

	{

		$this->message = "Successfully Saved";

		$this->index();

	}



	public function _publish()

	{		

		$Publish = array('status' => $this->input->post('publish_state'));	

		$this->db->where('id', $_POST['id']);

		$this->db->update('zipcodes', $Publish);

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

					$this->db->query('delete from zipcodes where id = ' . $id);	

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

	

	public function _update()

	{

		$this->db->select('*');

		$this->db->from('zipcodes');		

		$this->db->where('id', $this->input->post('id'));

		$this->Zipcode = $this->db->get()->result();
		$this->load->view('zipcode_update');

	}



	public function _update_save()

	{

		if ($this->_submit_validate() === FALSE) {

			$this->_update($_POST['id']);

			return;

		}

		
		$Zipcode = array(
			'zipcodes'      => $this->input->post('zipcode'),
			'taxrate'    => $this->input->post('tax_rate'),
			'status'        => $this->input->post('is_active'),
		);
		

		$this->db->trans_start();

		$this->db->where('id', $this->input->post('id'));

		$this->db->update('zipcodes', $Zipcode);

		$this->db->trans_complete();

		

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();

			log_message('error', 'Zipcodes->save() 交易錯誤');

		}

		

		redirect('zipcodes/success', 'refresh');		

		

	}
	
	

}