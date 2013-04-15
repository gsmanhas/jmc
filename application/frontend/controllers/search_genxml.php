<?php

/**
* 
*/
class Search_genxml extends CI_Controller
{
	
	function __construct()
	{   
		parent::__construct();
	}
	
	public function index()
	{

		// Get parameters from URL
		$center_lat = $this->input->post('lat');
		$center_lng = $this->input->post('lng');
		$radius     = $this->input->post('radius');
		
		//	Test Data.
		//	?lat=37&lng=-122&radius=25
		// $center_lat = 37;
		// $center_lng = -122;
		// $radius     = 25;

		// Start XML file, create parent node
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		$Query = $this->db->query(
			"SELECT address, phone, name, lat, lng, ( 3959 * acos( cos( radians('" . $center_lat . 
			"') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('" . $center_lng . 
			"') ) + sin( radians('" . $center_lat . "') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '" . $radius .
			"' ORDER BY distance LIMIT 0 , 20"
		);

		header("Content-type: text/xml");

		foreach ($Query->result() as $result) {
			$node = $dom->createElement("marker");
		 	$newnode = $parnode->appendChild($node);
		 	$newnode->setAttribute("name",     $result->name);
		 	$newnode->setAttribute("address",  $result->address . "<br>" . $result->phone);
		 	$newnode->setAttribute("lat",      $result->lat);
		 	$newnode->setAttribute("lng",      $result->lng);
		 	$newnode->setAttribute("distance", $result->distance . " miles");
		}

		echo $dom->saveXML();

	
	}
}