<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Store Locator - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<script src="http://maps.google.com/maps?file=api&v=2&key=AIzaSyCy4CzMLrQwO6pUpqfPoqOLd_18E_rV_NQ"
            type="text/javascript"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';

	</script>
	<script src="/js/store_locator.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="" onLoad="load()" onUnload="GUnload()">
	
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities') ?>
	</div>
	
	<div id="main">
		<div id="topnav">
			<?php $this->load->view('base/menu') ?>
		</div>
		<div id="pagetitle"><h1>Store Locator</h1></div>
		
		<div>
			<div style="float:left;margin-right:20px;">
				Zip <input type="text" id="addressInput" value="" class="inputtext" />
			</div>
			<div style="float:left;margin-right:20px;">
			    Radius
				<select id="radiusSelect" class="dropdown">
					<option value="25" selected>25</option>
					<option value="100">100</option>
					<option value="200">200</option>
				</select>
				miles
			</div>
			<input type="button" onClick="searchLocations()" value="Find Stores" class="inputbutton" />
			
			<div style="width:978px;border:1px solid #ccc;margin-top:20px;">
				<table style="margin-bottom:0;"> 
			    <tbody> 
			      <tr id="cm_mapTR">
			        <td>
						<div id="map" style="overflow: hidden; width:634px; height:400px"></div>
					</td>
					
					<td width="346" valign="top">
						<div id="sidebar" style="overflow: auto; height: 386px; font-size: 11px; color: #000">
						</div>
			        </td>
			      </tr> 
			    </tbody>
			  </table>
			</div>
		</div> 
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>