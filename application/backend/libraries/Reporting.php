<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Reporting
{
	
	private $CI;
	
	function __construct()
	{
		// $this->_enable_profiler(TRUE);
		// parent::__construct();
		$this->CI =& get_instance();
	}
	
	
	public function export_view_orders($setDescription = '', $stmt = '')
	{
        ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription($setDescription)
					->setKeywords("office 2007 openxml php")
					->setCategory("");
					
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Order No");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Customer Name');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Payment Method');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Order Date');
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Total Product Sales');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Discount');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Taxes Collected');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Shipping Charged');
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Amount');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Order Status');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Tracking No');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);

		$Query = $this->CI->db->query($stmt);
		
		$SUM_TOTAL_PRODUCT_SALES = 0;
		$SUM_TAX                 = 0;
		$SUM_CALCULATE_SHIPPING  = 0;
		$SUM_GRAND_TOTAL         = 0;
		$SUM_DISCOUNT	         = 0;
				
		$i = 2;
		foreach ($Query->result() as $item) {


			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->order_no);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->firstname . ' ' . $item->lastname);
			if($item->use_encryption == 'Y'){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->payment_method == 1 ? base64_decode($item->card_type) : 'PayPal');
			}else {
			 $objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->payment_method == 1 ? $item->card_type : 'PayPal');
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , $item->odate . ' ' . $item->oapm . ' ' . $item->otime);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , number_format($item->subtotal, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , number_format($item->discount, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , number_format($item->product_tax, 2));
			
			$calculate_shipping = 0;
			if ($item->promo_free_shipping == 0 AND $item->freeshipping == 0) {
				$calculate_shipping = $item->calculate_shipping;
			} else {
				
				if ($item->shipping_id == 2 AND $item->promo_free_shipping == 0 AND $item->freeshipping == 0) {
					$calculate_shipping = $Order->calculate_shipping;
				} else if ($item->shipping_id == 2) {
					$calculate_shipping = $item->calculate_shipping;
				} else {
					$calculate_shipping = 0;
				}
				
			}
            
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , number_format($calculate_shipping, 2));
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , number_format($item->amount, 2));
			
			// $grand = round($item->subtotal + $item->product_tax + $calculate_shipping, 2) - $item->discount;
			$grand = round($item->amount, 2);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('I' . $i) , $grand);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('J' . $i) , $item->order_state);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('K' . $i) , $item->track_number);
			
			$SUM_TOTAL_PRODUCT_SALES += $item->subtotal;
			$SUM_TAX                 += $item->product_tax;
			$SUM_CALCULATE_SHIPPING  += $calculate_shipping;
			$SUM_DISCOUNT            += $item->discount;
			$SUM_GRAND_TOTAL         = ($SUM_TOTAL_PRODUCT_SALES + $SUM_TAX + $SUM_CALCULATE_SHIPPING) - $SUM_DISCOUNT;
			
			$i++;

            /*$products = $this->CI->db->query("SELECT p.name, p.sku, ol . *
                                                FROM product p, order_list ol
                                               WHERE ol.order_id ={$item->order_id}
                                                AND p.id = ol.pid");


            $boldFont = array(
                'font'=>array(
                    'name'=>'Arial Cyr',
                    'size'=>'10',
                    'bold'=>true
                )
            );

            $center = array(
                'alignment'=>array(
                    'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
                )
            );

            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$i)->applyFromArray($boldFont)
                                                                  ->applyFromArray($center);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , "Product list.");
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$i:E$i");
            $startPosition = $i;
            $i++;
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, "Product Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, 'Product SKU');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, 'Units');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, 'Unit Price');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, 'Total price');

            foreach($products->result() as $product) {
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $product->name);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, (string)$product->sku);
                $objPHPExcel->setActiveSheetIndex(0)->getCell("B$i")->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $product->qty);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, number_format($product->price,2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, number_format($product->price * $product->qty, 2));

            }

            $styleArray = array(
              'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
              )
            );

            $objPHPExcel->getActiveSheet()->getStyle("A$startPosition:E$i")->applyFromArray($styleArray);
            unset($styleArray);

            $i++;*/


		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , "TOTAL");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , $SUM_TOTAL_PRODUCT_SALES);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , $SUM_DISCOUNT);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , $SUM_TAX);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , $SUM_CALCULATE_SHIPPING);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , $SUM_GRAND_TOTAL);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('I' . $i) , "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('J' . $i) , "");

		$title = "Report : View Orders " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download('report_view_orders.zip');
	}
	
	public function export_sales_report($setDescription = '', $stmt = '')
	{
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription($setDescription)
					->setKeywords("office 2007 openxml php")
					->setCategory("");
					
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Date");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Total Product Sales');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Tax Collected');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Shipping Charged');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Grand Total');		
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		
				
		$Query = $this->CI->db->query($stmt);
		
		$sum_orders = 0;
		$Sum_TotalProductSales = 0;
		$Sum_ShippingCharged = 0;
		$Sum_TaxCollected = 0;
		$SumGrandTotal = 0;
		
		$i = 2;
		foreach ($Query->result() as $item) {
			
			// $Query = $this->CI->db->query("SELECT " .
			// "SUM(amount) as 'TotalProductSales', " .
			// "SUM(calculate_shipping) as 'ShippingCharged', " .
			// "SUM(product_tax) as 'TaxCollected'" .
			// "FROM `order` as o " .
			// "WHERE order_date like '" . $item->order_date . "%' " .
			// "AND `o`.`order_state` != 3 AND order_state != 5 AND order_state != 6", FALSE)->result();
			
			$Query = $this->CI->db->query("SELECT " .
			"SUM((SELECT SUM(price * qty) FROM order_list as ol WHERE ol.order_id = o.id)) as 'subtotal'," .
			"SUM(amount) as 'TotalProductSales', " .
			"SUM(calculate_shipping) as 'ShippingCharged', " .
			"SUM(product_tax) as 'TaxCollected'," .
			"SUM((SELECT calculate_shipping FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 1 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'ShippingCharged2'," .
			// "SUM((SELECT calculate_shipping FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'twoDays'" .
			"SUM((SELECT calculate_shipping FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'twoDays'" .
			// "SUM((SELECT calculate_shipping FROM `order` as o1 WHERE o1.id = o.id AND promo_free_shipping = 0 AND freeshipping = 0)) as 'ShippingCharged2'," .
			// "SUM((SELECT calculate_shipping FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2)) as 'twoDays'" .
			", SUM(o.discount) as 'discount' " .
			", SUM(o.amount) as 'amount' " .
			"FROM `order` as o " .
			"WHERE order_date like '" . $item->order_date . "%' " .
			"AND `o`.`order_state` != 3 AND order_state != 5 AND order_state != 6 AND o.is_delete = 0", FALSE)->result();
			
			// echo $this->CI->db->last_query();
			
			$TotalProductSales = 0;
			$ShippingCharged = 0;
			$TaxCollected = 0;
			
			if (count($Query) >= 1) {
				$TotalProductSales = $Query[0]->subtotal;
				$ShippingCharged   = $Query[0]->ShippingCharged2 + $Query[0]->twoDays;
				$TaxCollected      = $Query[0]->TaxCollected;
			}
			
			$Total = round($TotalProductSales + $TaxCollected + $ShippingCharged, 2) - $Query[0]->discount;
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->order_date);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , round($TotalProductSales, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , round($TaxCollected, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , round($ShippingCharged, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , round($Total, 2));
			
			$Sum_TotalProductSales += $TotalProductSales;
			$Sum_ShippingCharged   += $ShippingCharged;
			$Sum_TaxCollected      += $TaxCollected;
			$SumGrandTotal         += ($Total);
			
			$i++;
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , "TOTAL");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , round($Sum_TotalProductSales, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , round($Sum_TaxCollected, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , round($Sum_ShippingCharged, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , round($SumGrandTotal, 2));

		// exit;

		$title = "Report : Sales Report " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download('report_sales_report.zip');	
	}
	
	public function export_sales_report_sku($setDescription = '', $stmt = '')
	{
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription($setDescription)
					->setKeywords("office 2007 openxml php")
					->setCategory("");
					
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "SKU");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', "Item Number");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', "Invoice number");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Product');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Date');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Unit Price');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Units');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Sales');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

		$Query = $this->CI->db->query($stmt);
				
		$i = 2;
		$last_sku     = '';
		$last_product = '';
		$sku_count = 0;
		$sku_sales = 0;
		$sum_sku   = 0;
		$sum_sales = 0;
		foreach ($Query->result() as $item) {
			
			$bol = ($last_sku != $item->sku) ? TRUE : FALSE;
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->sku);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->item_number);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->invoice_number);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , $item->product);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , $item->odate . ' ' . $item->oapm . ' ' . $item->otime);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , number_format($item->unit_price, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , $item->qty);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , number_format($item->sales, 2));
			
			$i++;
			
			$sku_count += $item->qty;
			$sku_sales += $item->sales;
			
			$last_sku     =  $item->sku;
			$last_product =  $item->product;
			$sum_sku      += $item->qty;
			$sum_sales    += $item->sales;
			
		}
		
		
		$title = "Report : Sales Report " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download('report_sales_report.zip');

				
	}
	
	public function export_sales_tax_report($setDescription = '', $stmt = '')
	{
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription($setDescription)
					->setKeywords("office 2007 openxml php")
					->setCategory("");
					
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Order ID");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Date/Time Placed');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Shipped To');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Subtotal');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Tax Collected');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Shipping Charged');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Coupon Discounts');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Total Billed');
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		
		$sum_subtotal        = 0;
		$sum_product_tax     = 0;
		$sum_shipping_price  = 0;
		$sum_discount        = 0;
		$sum_amount          = 0;
		
		$Query = $this->CI->db->query($stmt);
				
		$i = 2;
		foreach ($Query->result() as $item) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , (string)$item->order_no);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->odate . ' ' . $item->oapm . ' ' . $item->otime);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , (string)$item->destination_state);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)number_format($item->subtotal, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , (float)number_format($item->product_tax, 2));
			$shipping_price = 0;
			if ($item->freeshipping == 0 && $item->promo_free_shipping == 0) {
				$shipping_price = $item->shipping_price;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , (float)number_format($shipping_price, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , (float)number_format($item->discount, 2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , (float)number_format($item->amount, 2));
			
			$sum_subtotal       += $item->subtotal;
			$sum_product_tax    += $item->product_tax;
			$sum_shipping_price += $shipping_price;
			$sum_discount       += $item->discount;
			// $sum_amount         += $item->amount;
			$sum_amount			+= ($item->subtotal + $item->product_tax + $shipping_price) - $item->discount;
			
			$i++;
		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , "TOTAL");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)round($sum_subtotal, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , (float)round($sum_product_tax, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , (float)round($sum_shipping_price, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , (float)round($sum_discount, 2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , (float)round($sum_amount, 2));


		$title = "Report : Sales Tax Report " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download('report_sales_tax_report.zip');
	}
	
	public function sales_by_SKU($setDescription = '', $stmt = '')
	{
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription($setDescription)
					->setKeywords("office 2007 openxml php")
					->setCategory("");
					
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "SKU");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Product');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Items Sold');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		
		$Query = $this->CI->db->query($stmt);
				
		$i = 2;
		foreach ($Query->result() as $item) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->sku);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->product);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->qty);
			$i++;
		}

		$title = "Report : Sales by SKU " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download('report_sales_by_sku.zip');
		
	}
	
	public function export2Excel()
	{		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription("")
					->setKeywords("office 2007 openxml php")
					->setCategory("");
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Project");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Task');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Due Date');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Assign To');
		
		// $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()
		// 	->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		// 	->getStartColor()->setRGB('003366');
		// 
		// $objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()
		// 	->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		// 	->getStartColor()->setRGB('003366');	
		// 
		// $objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()
		// 	->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		// 	->getStartColor()->setRGB('003366');		
		// 
		// $objPHPExcel->getActiveSheet()->getStyle('D1')->getFill()
		// 	->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		// 	->getStartColor()->setRGB('003366');		
		
		// $objPHPExcel->getActiveSheet()->setAutoFilter('A1:D'.$DColumn);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);

		$title = "Six Spoke task list " . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");
		
		$CI->zip->read_file("excel/".$title.".xls");
		
		$CI->zip->download('my_backup.zip');
		
		
	}
	

	public function export_monthly_shipment_report($stmt = '')
	{
		$_MONTH = "";
		if (($this->CI->input->post('selmonthly'))) {
			$_MONTH = $this->CI->input->post('selmonthly')."-01";
		} else {
			$_MONTH = date("y")."-".date("m")."-01";
		}
		
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription("Monthly Shipment Report")
					->setKeywords("office 2007 openxml php")
					->setCategory("");
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Date");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Item / SKU');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Quantity');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Total Collected | Discount Amount Received | Unit Price');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
		
		
		$i = 2;		
		$Sales_tax_collected = $this->CI->db->query(
			"SELECT " .
			"SUM(product_tax) as 'price' " .
			"FROM `order` " .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
			"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
			"AND is_delete = 0 " .
			"ORDER BY order_date ASC"
			, array($_MONTH, $_MONTH))->result();
		
		if (count($Sales_tax_collected) >= 1) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $this->CI->input->post('selmonthly'));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , "Sales Tax Collected");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , 1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)number_format($Sales_tax_collected[0]->price, 2));
		}
		$i++;
		
		$Shipping_Charged = $this->CI->db->query(
			"SELECT " .
			"SUM(calculate_shipping) as 'price' " .
			"FROM `order` " .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
			"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
			"AND is_delete = 0 AND freeshipping = 0 " .
			"ORDER BY order_date ASC"
			, array($_MONTH, $_MONTH))->result();
		
		if (count($Shipping_Charged) >= 1) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $this->CI->input->post('selmonthly'));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , "Shipping Charged");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , 1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)round($Shipping_Charged[0]->price, 2));
		}
		$i++;
		
		$Free_Shipping_for_Orders_Over_50 = 0;
		
		$Q1 = $this->CI->db->query(
			"SELECT " .
			"SUM(calculate_shipping) as 'price'" .
			"FROM `order` " .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
			"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
			"AND is_delete = 0 AND freeshipping = 1 " .
			"ORDER BY order_date ASC"
			, array($_MONTH, $_MONTH))->result();
		
		// echo $this->db->last_query();
		
		$Q2 = $this->CI->db->query(
			"SELECT " .
			"SUM(calculate_shipping) as 'price'" .
			"FROM `order` " .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
			"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
			"AND is_delete = 0 AND promo_free_shipping = 1 " .
			"ORDER BY order_date ASC "
			, array($_MONTH, $_MONTH))->result();
		
		// echo $this->db->last_query();
		
		if (count($Q1) >= 1) {
			$Free_Shipping_for_Orders_Over_50 = is_null($Q1[0]->price) ? 0 : $Q1[0]->price;
		}
		
		if (count($Q2) >= 1) {
			$Free_Shipping_for_Orders_Over_50 += (is_null($Q2[0]->price) ? 0 : $Q2[0]->price);
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $this->CI->input->post('selmonthly'));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , "Discount: Free Shipping for Orders Over $50");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , 1);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)round($Free_Shipping_for_Orders_Over_50, 2));
		$i++;
		
		
		$Query = $this->CI->db->query(
			"SELECT discount_id, count(discount_id) as 'count', SUM(discount) as 'discount', (SELECT code FROM discountcode WHERE id = discount_id) as 'name' FROM `order`" .
			"WHERE " .
			"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
			// "AND order_state != 3 AND order_state != 5 AND order_state != 6  " .
			// "AND promo_free_shipping = 0 AND freeshipping = 0 AND is_delete = 0 " .
			// "AND discount_id != 0 " .
			"AND discount_id != 0 " .
			"AND order_state = 4 " .
			"GROUP BY discount_id " .
			"ORDER BY order_date ASC"
		, array($_MONTH."-01", $_MONTH."-01"));
		foreach ($Query->result() as $item) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $this->CI->input->post('selmonthly'));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->name);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->count);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)round($item->discount, 2));
			$i++;
		}
		
		// echo $stmt;
		
		$this->OrderDate = $this->CI->db->query($stmt)->result();
		
		// foreach ($this->OrderDate as $OrderDate) {
			
			$Query = $this->CI->db->query(
				"SELECT pid, sku, pname, price, SUM(qty) as 'qty' FROM monthly_shipment_report " .
				"WHERE order_id IN ( " .
				"SELECT id FROM `order` WHERE order_date Like '".$this->CI->input->post('selmonthly')."%' " .
				"AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 " .
				") GROUP BY pid, price"
			)->result();
												
			foreach ($Query as $item) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $this->CI->input->post('selmonthly'));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->sku);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->qty);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , (float)number_format($item->price, 2));
				$i++;
			}
		// }
		
		
		$title = "Monthly Shipment Report" . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");

		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download($title.".zip");
		
		
	}	


	public function export_monthly_shipping_address_report($stmt = '')
	{
				
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription("Monthly Shipment Report")
					->setKeywords("office 2007 openxml php")
					->setCategory("");
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Order NO");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Order Date');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Tracking No');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'First Name');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Last Name');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'E-Mail');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Phone Number');
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Billing City');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Billing State');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Billing Zip code');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Billing Address');

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Shipping City');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Shipping State');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Shipping Zip code');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Shipping Address');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
		
		$Query = $this->CI->db->query($stmt)->result();
		
		$i = 2;
		foreach ($Query as $item) {
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->order_no);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->order_date);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->track_number);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , $item->firstname);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('E' . $i) , $item->lastname);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('F' . $i) , $item->email);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('G' . $i) , $item->phone_number);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('H' . $i) , $item->bill_city);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('I' . $i) , $item->bill_state);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('J' . $i) , $item->bill_zipcode);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('K' . $i) , $item->bill_address);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('L' . $i) , $item->ship_city);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('M' . $i) , $item->ship_state);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('N' . $i) , $item->ship_zipcode);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('O' . $i) , $item->ship_address);
			$i++;
			
		}
		
		$title = "Monthly Shipping Address Report" . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");

		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download($title.".zip");
		
	}

	public function export_shipments_for_a_given_time_period($stmt = '')
	{

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()
					->setCreator("Six Spoke Media")
					->setLastModifiedBy("SystemCart")
					->setTitle("Office 2007 XLSX Document")
					->setSubject("Office 2007 XLSX Document")
					->setDescription("Monthly Shipment Report")
					->setKeywords("office 2007 openxml php")
					->setCategory("");

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Date");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'UPC');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Quantity');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Unit Price');
		

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		

		$Query = $this->CI->db->query($stmt)->result();

		$i = 2;
		foreach ($Query as $item) {

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('A' . $i) , $item->date);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('B' . $i) , $item->UPC);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('C' . $i) , $item->quantity);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(('D' . $i) , $item->price);
			$i++;

		}

		$title = "Product Shipments Report" . date("Ymd");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save("excel/" . $title . ".xls");

		$this->CI->zip->read_file("excel/".$title.".xls");
		$this->CI->zip->download($title.".zip");

        return true;

	}
	
}




