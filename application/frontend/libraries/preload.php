<?php


class Preload {
	
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function abc(){
		return 'Ya Allah Khair';
	}

	

}