<?php

/**
* 
*/
class Homepagebanner extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->db->select('*');
		$this->db->from('home_banners');
		$this->db->order_by('id', 'asc');
		$this->HomeBanner = $this->db->get()->result();
		
		$this->db->select('*');
		$this->db->from('home_seo');
		$this->SEO = $this->db->get()->result();
		
		$this->load->view('homepagebanner');
	}
	
	public function submit()
	{
		$numrows = 0;
		
		$HomeSEO = array(
			'description' => $this->input->post('meta_description'),
			'keywords'    => $this->input->post('meta_keywords')
		);
		$this->db->where('id', 1);
		$this->db->update('home_seo', $HomeSEO);
		$numrows += $this->db->affected_rows();
		
		
		$HomeBanner01 = array(
			'image' => $this->input->post('banner1'),
			'title' => $this->input->post('bg_1_title'),
			'url'   => $this->input->post('banner_01_url'),
			'text'  => $this->input->post('banner1_text'),
			'banner_status'  => $this->input->post('banner_01_status'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 1);
		$this->db->update('home_banners', $HomeBanner01);
		$numrows += $this->db->affected_rows();
		
		$HomeBanner02 = array(
			'image' => $this->input->post('banner2'),
			'title' => $this->input->post('bg_2_title'),
			'url'   => $this->input->post('banner_02_url'),
			'text'  => $this->input->post('banner2_text'),
			'banner_status'  => $this->input->post('banner_02_status'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 2);
		$this->db->update('home_banners', $HomeBanner02);
		$numrows += $this->db->affected_rows();
		
		$HomeBanner03 = array(
			'image' => $this->input->post('banner3'),
			'title' => $this->input->post('bg_3_title'),
			'url'   => $this->input->post('banner_03_url'),
			'text'  => $this->input->post('banner3_text'),
			'banner_status'  => $this->input->post('banner_03_status'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 3);
		$this->db->update('home_banners', $HomeBanner03);
		$numrows += $this->db->affected_rows();
		
		// $HomeBanner04 = array(
		// 	'image' => $this->input->post('banner4'),
		// 	'url'   => $this->input->post('banner_04_url'),
		// 	'text'  => $this->input->post('banner4_text'),
		// 	'updated_at'   => unix_to_human(time(), TRUE, 'us')
		// );
		// $this->db->where('id', 4);
		// $this->db->update('home_banners', $HomeBanner04);
		// $numrows += $this->db->affected_rows();
		// 
		// $HomeBanner05 = array(
		// 	'image' => $this->input->post('banner5'),
		// 	'url'   => $this->input->post('banner_05_url'),
		// 	'text'  => $this->input->post('banner5_text'),
		// 	'updated_at'   => unix_to_human(time(), TRUE, 'us')
		// );
		// $this->db->where('id', 5);
		// $this->db->update('home_banners', $HomeBanner05);
		// $numrows += $this->db->affected_rows();
		
		$HomeBanner06 = array(
			'title' => $this->input->post('banner_1_title'),
			'image' => $this->input->post('side_left'),
			'url'   => $this->input->post('banner_06_url'),
			'text'  => $this->input->post('side_left_text'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 6);
		$this->db->update('home_banners', $HomeBanner06);
		$numrows += $this->db->affected_rows();
		
		$HomeBanner07 = array(
			'title' => $this->input->post('banner_2_title'),
			'image' => $this->input->post('side_middle'),
			'url'   => $this->input->post('banner_07_url'),
			'text'  => $this->input->post('side_middle_text'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 7);
		$this->db->update('home_banners', $HomeBanner07);
		$numrows += $this->db->affected_rows();
		
		$HomeBanner08 = array(
			'title' => $this->input->post('banner_3_title'),
			'image' => $this->input->post('side_right'),
			'url'   => $this->input->post('banner_08_url'),
			'text'  => $this->input->post('side_right_text'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->where('id', 8);
		$this->db->update('home_banners', $HomeBanner08);
		$numrows += $this->db->affected_rows();
		
		$this->message =  $numrows . " records updated";
		
		$this->index();
		
	}
	
}
