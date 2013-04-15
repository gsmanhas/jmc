<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

	jQuery(document).ready(function(){
		
		jQuery("#selSalesYearAndMonth").change(function(){
			jQuery("#frmMain").submit();
		});
		
		jQuery("#selDayofOrder").change(function(){
			jQuery("#frmMain").submit();
		});
		
	});

   google.load("visualization", "1", {packages:["corechart"]});
   google.setOnLoadCallback(drawChart);
   function drawChart() {
     var data = new google.visualization.DataTable();
     data.addColumn('string', 'Year');
     data.addColumn('number', 'Sales');
     // data.addColumn('number', 'Expenses');
     
		
		<?php
		
		// echo $this->input->post('selSalesYearAndMonth');
		$_MONTH = "";
		if (($this->input->post('selSalesYearAndMonth'))) {
			$_MONTH = $this->input->post('selSalesYearAndMonth')."-01";
		} else {
			$_MONTH = "2011-".date("m")."-01";
		}

		
		$Order = $Query = $this->db->query(
					'SELECT DISTINCT STR_TO_DATE(order_date, "%Y-%m-%d") as `order_date`' .
					' FROM `order` ' .
					' WHERE order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND LAST_DAY(?)' .
					' AND order_state != 3 AND order_state != 5 AND order_state != 6' .
					' ORDER BY order_date ASC'
				, array($_MONTH, $_MONTH))->result();
		
		// echo $this->db->last_query();
		
		$i = 0;
		$j = 0;
		if (count($Order) >= 1) {
		?>
		
		data.addRows(<?php echo count($Order); ?>);
		<?php
		foreach ($Order as $item) {
			
			// echo $item->order_date.br(1);
		
		?>
		data.setValue(<?php echo $i ?>, <?php echo $j ?>, '<?php echo substr($item->order_date, 5, 10) ?>');
		<?php
			
			$Query = $this->db->query("SELECT SUM(amount) as 'sum' FROM `order` WHERE STR_TO_DATE(order_date, '%Y-%m-%d') in (?) AND is_delete = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6", $item->order_date);
			$To_Day = $Query->result();
			if (count($To_Day) >= 1) {
				// echo $item->order_date.br(1);
				// echo number_format($To_Day[0]->sum, 2).br(1);
		?>
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo round((float)$To_Day[0]->sum, 2) ?>);
		<?php
			}
			
			$i++;
			$j = 0;
		}
		}
		?>
		

     var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
     chart.draw(data, {colors: ['#c5def4', '#ff0000'], width: '100%', height: 240, title: '',
                       hAxis: {title: '<?php echo $_MONTH ?>', titleTextStyle: {color: '#666'}}
                      });
   }


	<?php
		
		$Products = $this->db->query(
			"SELECT (SELECT `name` FROM product WHERE id = pid) as 'name', sum(qty) as 'total' " .
			"FROM `order_list` as `ol` LEFT JOIN `order` as `o` ON o.id = ol.order_id " .
			"WHERE o.order_state != 3 AND o.order_state != 5 AND o.order_state != 6 " .
			"GROUP BY pid " .
			"ORDER BY total DESC limit 0, 10"
		)->result();	
		// echo $this->db->last_query();
		
		
	?>


   google.setOnLoadCallback(drawChart3);
   function drawChart3() {
     var data = new google.visualization.DataTable();
     data.addColumn('string', 'Year');
     data.addColumn('number', 'Total of Orders');
	 data.addColumn('number', 'Credit Card');
	 data.addColumn('number', 'Paypal');
	 data.addColumn('number', 'Ordered');
	 data.addColumn('number', 'Shipped');
	 data.addColumn('number', 'Cancelled');
     // data.addColumn('number', 'Expenses');


		<?php
		
		$_MONTH2 = "";
		if (($this->input->post('selDayofOrder'))) {
			$_MONTH2 = $this->input->post('selDayofOrder')."-01";
		} else {
			$_MONTH2 = "2011-".date("m")."-01";
		}
		
		$Order = $Query = $this->db->query(
					'SELECT DISTINCT STR_TO_DATE(order_date, "%Y-%m-%d") as `order_date`' .
					' FROM `order` ' .
					' WHERE order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND LAST_DAY(?)' .
					' AND order_state != 3 AND order_state != 5 AND order_state != 6' .
					' ORDER BY order_date ASC'
				, array($_MONTH2, $_MONTH2))->result();
		$i = 0;
		$j = 0;
		?>

		data.addRows(<?php echo count($Order); ?>);
		<?php
		foreach ($Order as $item) {

			// echo $item->order_date.br(1);

		?>
		data.setValue(<?php echo $i ?>, <?php echo $j ?>, '<?php echo substr($item->order_date, 5, 10) ?>');
		<?php

			$Query = $this->db->query(
				"SELECT count(id) as 'sum', (SELECT count(id) FROM `order` as o WHERE o.payment_method = 1 AND is_delete = 0 AND STR_TO_DATE(order_date, '%Y-%m-%d') in (?)) as 'authorize'" .
				", (SELECT count(id) FROM `order` as o2 WHERE o2.payment_method = 2 AND is_delete = 0 AND STR_TO_DATE(order_date, '%Y-%m-%d') in (?)) as 'paypal' " .
				", (SELECT count(id) FROM `order` as o3 WHERE o3.order_state = 2 AND is_delete = 0 AND STR_TO_DATE(order_date, '%Y-%m-%d') in (?)) as 'ordered' " .
				", (SELECT count(id) FROM `order` as o3 WHERE o3.order_state = 4 AND is_delete = 0 AND STR_TO_DATE(order_date, '%Y-%m-%d') in (?)) as 'shipping' " .
				", (SELECT count(id) FROM `order` as o3 WHERE o3.order_state = 3 AND is_delete = 0 AND STR_TO_DATE(order_date, '%Y-%m-%d') in (?)) as 'cancel' " .
				" FROM `order` WHERE STR_TO_DATE(order_date, '%Y-%m-%d') in (?) AND is_delete = 0", 
				array($item->order_date, $item->order_date, $item->order_date, $item->order_date, $item->order_date, $item->order_date));
			
			// echo $this->db->last_query();
			
			$To_Day = $Query->result();
			if (count($To_Day) >= 1) {
				// echo $item->order_date.br(1);
				// echo number_format($To_Day[0]->sum, 2).br(1);
		?>
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo round((float)$To_Day[0]->sum, 2) ?>);
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $To_Day[0]->authorize ?>);
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $To_Day[0]->paypal ?>);
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $To_Day[0]->ordered ?>);
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $To_Day[0]->shipping ?>);
		data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $To_Day[0]->cancel ?>);
		<?php
			}

			$i++;
			$j = 0;
		}

		?>


     var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
     chart.draw(data, {colors: ['#888888', '#c5def4', '#7691a7', '#e54964', '#f5b1ad', '#ddd'], width: '100%', height: 240, title: '',
                       hAxis: {title: '<?php echo $_MONTH2 ?>', titleTextStyle: {color: '#666'}}
                      });
   }


	<?php

		$Products = $this->db->query(
			"SELECT (SELECT `name` FROM product WHERE id = pid) as 'name', sum(qty) as 'total' " .
			"FROM `order_list` as `ol` LEFT JOIN `order` as `o` ON o.id = ol.order_id " .
			"WHERE o.order_state != 3 AND o.order_state != 5 AND o.order_state != 6 " .
			"GROUP BY pid " .
			"ORDER BY total DESC limit 0, 10"
		)->result();	



	?>

	google.setOnLoadCallback(drawChart2);
	      function drawChart2() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Task');
	        data.addColumn('number', 'Hours per Day');
	        data.addRows(<?php echo count($Products) ?>);
	<?php
		$i = 0;
		$j = 0;
		foreach ($Products as $product) {
	?>
	
	data.setValue(<?php echo $i ?>, <?php echo $j ?>, '<?php echo $product->name ?>');
	data.setValue(<?php echo $i ?>, <?php echo ++$j ?>, <?php echo $product->total ?>);
		
	<?php
			$j = 0;
			$i++;
		}
	?>
	        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	        chart.draw(data, {is3D : false, colors: ["#e54b2e", "#f5883f", "#ffcf5e", "#9ea63f", "#30547f", "#e54964", "#ffee65", "#bccc5f", "#81b27a", "#607f7b"]
				, width: '100%', height: 400, title: '', pieSliceTextStyle : {color : "#333"}});
	      }
	

 </script>

</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Dashboard</b></h1>
					<div class="other">
						<div class="float-left">Welcome to your Dashboard! The Dashboard is the first page you see after logging in. It consists of shortcuts to the often used modules.</div>
						<div class="button float-right">

						</div>
						<div class="clearfix"></div>
					</div>
				</div>
								
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>Administration Options</h1>
					<div class="other">
						<ul id="dashboard-buttons">
							<li>
								<a class="Book_phones tooltip" href="<?php echo base_url() ?>admin.php/mediakit">Media Center</a>
							</li>
							<li>
								<a class="Books tooltip" href="<?php echo base_url() ?>admin.php/members">Customers</a>
							</li>
							<li>
								<a class="Books tooltip" href="<?php echo base_url() ?>admin.php/products">Products</a>
							</li>
							<li>
								<a class="Books tooltip" href="<?php echo base_url() ?>admin.php/webpage">Create a Web Page</a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<form action="/admin.php/home" method="post" accept-charset="utf-8" id="frmMain" name="frmMain">
				
				<div class="dashboard_options">
					<span>Select a month </span>
					<select name="selSalesYearAndMonth" id="selSalesYearAndMonth" size="1">
						<option value="0">Please select</option>
						<?php
							$IS_SELECTED = "";
							$query = $this->db->query(
								"SELECT DISTINCT " .
								"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 1, 4) as `year`, " .
								"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 6, 2) as `month` " .
								"FROM `order` " .
								"WHERE order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0;")->result();
							foreach ($query as $item) {
								if ($this->input->post('selSalesYearAndMonth') == ($item->year . '-' . $item->month)) {
									$IS_SELECTED = "selected='selected'";
								} else {
									$IS_SELECTED = "";
								}
								
								if ($item->year == "0000" || $item->month == "00") {
								} else {
									printf("<option value=\"%s\" %s>%s</option>", $item->year . '-' . $item->month, $IS_SELECTED, $item->year . '-' . $item->month);	
								}
							}
						?>
					</select>
				</div>
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>Sales Chart</h1>
					<div class="other">
						<div id="chart_div"></div>
						<div class="clearfix"></div>
					</div>
				</div>	
				
				<div class="dashboard_options">
					<span>Select a month </span>
					<select name="selDayofOrder" id="selDayofOrder" size="1">
						<option value="0">Please select</option>
						<?php
							$IS_SELECTED = "";
							$query = $this->db->query(
								"SELECT DISTINCT " .
								"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 1, 4) as `year`, " .
								"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 6, 2) as `month` " .
								"FROM `order` " .
								"WHERE order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0;")->result();
							foreach ($query as $item) {
								
								if ($this->input->post('selDayofOrder') == ($item->year . '-' . $item->month)) {
									$IS_SELECTED = "selected='selected'";
								} else {
									$IS_SELECTED = "";
								}
								
								if ($item->year == "0000" || $item->month == "00") {
								} else {
									printf("<option value=\"%s\" %s>%s</option>", $item->year . '-' . $item->month, $IS_SELECTED, $item->year . '-' . $item->month);
								}
							}
						?>
					</select>
				</div>
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>Days of Order</h1>
					<div class="other">
						<div id="chart_div3"></div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>TOP 10 Sales product chart</h1>
					<div class="other">
						<div id="chart_div2"></div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				</form>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php
		$this->load->view('base/footer');
		?>		
	</div>
	
</body>
</html>