<?php


class Inventory {
	
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * 我知道很困惱, 因為它叫是否存在, 請將它當成是否存在於使用者的購物車中
	 */
	public function is_exists($pid = 0)
	{
		if (isset($_SESSION['cart_contents'])) {
			foreach ($_SESSION['cart_contents'] as $key => $value) {
               if (is_array($value)) {
					if ($value['id'] == $pid) {
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * 取得商品在 SESSION 中的數量
	 */
	public function get_current_qty($pid = 0)
	{
		if (isset($_SESSION['cart_contents'])) {
			foreach ($_SESSION['cart_contents'] as $key => $value) {
               if (is_array($value)) {
					if ($value['id'] == $pid) {
						// echo $value['qty'];
						return $value['qty'];
					}
				}
			}
		}
		return 0;
	}

	/**
	 * 判斷是要入庫還是出庫存
	 */
	public function comparison($pid = 0, $qty = 0)
	{
		if ($qty <= 0) {
			return;
		}
		
		$curr_qty = $this->get_current_qty($pid);
		
		// echo $curr_qty.br(1);
		
		if ($curr_qty < $qty) {
			// echo $qty . " - " . $curr_qty . " = " . ($qty - $curr_qty);
			$stock = ($qty - $curr_qty);
			$this->Out_Stock($pid, $stock);
			//	++ (取出 In_Stock)
		}
		
		if ($curr_qty > $qty) {
			// echo $curr_qty . " - " . $qty . " = " . ($curr_qty - $qty);
			$stock = ($curr_qty - $qty);
			$this->In_Stock($pid, $stock);
			//	-- (回寫 In_Stock)
		}
		
	}
	
	/**
	 * 我詞窮了, 這個當作是取出庫存 (不要打我)
	 */
	public function Out_Stock($pid = 0, $qty = 0)
	{
		$query = $this->CI->db->query(
			"UPDATE product SET in_stock = in_stock - ? WHERE id = ?",
			array($qty, $pid)
		);
	}

	/**
	 * 我詞窮了, 這個當作是回寫庫存 (不要打我)
	 */
	public function In_Stock()
	{
		$query = $this->CI->db->query(
			"UPDATE product SET in_stock = in_stock + ? WHERE id = ?",
			array($qty, $pid)
		);	
	}

}