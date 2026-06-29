<?php
ob_start();
require_once '../vendor/autoload.php';

//include("../includes/db_config.php");
//include("mpdf.php");
include "../classes/NumbersToWords.php";

include "../includes/db_config.php";

include "../classes/class_sale_order.php";
include "../classes/class_company.php";

$obj_nu = new NumbersToWords();
$obj_com = new Company('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
//$obj_so = new Sale_order('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

$obj_so = new Sale_order('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

//$data_id = $_POST['soid'];


if (isset($_POST) && !empty($_POST)) {



    //$obj_so->setpk_sale_order($data_id);
    $getSQId = $obj_so->getSalesOrderByIdStatus();
   // $datas = mysqli_fetch_array($getSQId);
   


}
/*
$getCustomer = $obj_so->getCustomer($datas['customer_id']);
$getCustomerdata = mysqli_fetch_array($getCustomer);

$sq_no = $datas['sono'];
$date = date('d-M-Y', strtotime($datas['created_on']));

$sq_no = $datas['sono'];

$date = date('d-M-Y', strtotime($datas['sale_date']));

*/
//var_dump( $scproduct);
//exit;
$terms_description = "
<br>
1. Goods under this  are supplied on your Account and Risk.<br>
2. The amount of this Sales Quote should be paid on the due date else interest @18% per annum and applicable GST will be charged from the date of sales quote till the actual payment.<br>
3. All payments should be made by RTGS/NEFT/A/c. payee cheque/Draft/irrevocable letter of credit drawn in favour of  AV Digital Press .<br>
4. All claims for Goods lost or damaged in transit or discrepancies of any kind (including shortages in weight and non-delivery) must be made upon the carrier or insurance Company as applicable.<br>
5. All disputes under this will be settled by Competent Court within the jurisdication of Madurai.<br>
";

//$mpdf=new mPDF('','', 0, '', 10, 10, 70, 15, 10, 10, 'A4-L');

$html = "<html><head>
	<style>
	.clearfix:after {
	content: '';
	display: table;
	clear: both;
	}

	a {
	color: #5D6975;
	text-decoration: underline;
	}

	body {
	color: #001028;
	background: #FFFFFF;
	font-family: Arial, sans-serif;
	font-size: 12px;
	font-family: Arial;
	text-transform: uppercase;
	}

	table {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0;
	border:1px solid #000000!important;
	
	}


	table th,
	table td {
	text-align: center;
	}

	table th {
		color: #000;
		border:1px solid #000000!important;
		white-space: nowrap;
		font-weight: normal;
		text-align: left;
		padding-left: 6px;
		padding-right: 6px;
	}


	table td {
	padding-left: 6px;
	text-align: left;
	border:1px solid #000000!important;
	text-transform: uppercase;
	}

	table td table tr td {
	font-size:12px;
	text-transform: uppercase;
	}

	table p{
		margin:5px 0;
	}

	table.items thead tr th{
		font-size:12px;
		text-transform: uppercase;
	}
	table.items tbody tr td{
		font-size:12px;
		padding-bottom:1px;
		text-transform: uppercase;
	}
	th, td {
		border:1px solid #000000!important;
		border-width:0 1px 1px 0 !important;
	}
	tr.headdetails {
		padding: 80px;
	height:80px;
		  background: #ddd;

	  }
	</style>
	</head>
	<body>
    <main>";

$html .= "<table style='border-width:unset;border-style:unset;border-color:unset;margin-bottom:0;border-bottom:0;'>
			<thead>
				<tr>
				
					<th style='border:0;vertical-align: baseline;width: 30%;font-size:30px;float:right'><small>Page No: 1 of {nbpg}</small></th>
				</tr>
				<tr>
				<th style='border:0;width: 30%;padding-top:0;padding-bottom:0;font-size:30px;text-align:center'></th>
				<th style='border:0;width: 50%;padding-top:0;padding-bottom:0;font-size:30px;text-align:center'></th>
				<th style='border:0;width: 20%;padding-top:0;padding-bottom:0;'>
				</th>

				</tr>
		
				<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>
				<th colspan='' style='border:0;padding:0;padding-bottom:0;width: 60%'></th>
				<th colspan='' style='border:0;padding:0;padding-bottom:0;padding-left:0;width: 10%;font-size:30px'><p style='text-transform:uppercase;%;font-size:30px'><p  style='float:left'></th>
				<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>

				</tr>
			

			</thead>
		</table>";


	/*	$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;'>
				<tbody>
				<tr>
				
						<td style='border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;text-align:center'>
						<p><img src='../assets/images/AVogo.png' style='width: 150px;height: auto;'/></p>
								
							
								
									<p style='text-transform:uppercase;'>51/31, 60 Feet road, J.D Nagar, 2nd street,  Tiruppur, Tamil Nadu - 641602</p>
								<p><b>GSTIN/UN:</b> 33EMTPP5008N1ZE</p>
									
							
						</td>


					</tr>

					<tr style='padding-top:10px;padding-bottom:10px'>
					<tr>	<th style='border:0;width: 50%;padding-top:0;padding-bottom:0;font-size:25px;text-align:center'> Order Status </th>
					<tr>
					<td style='border:2px solid #000000;'></td>
					</tr>	</tbody>	</table>";
*/
	$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;border:0;margin-bottom:10px'>
	<tbody>
	<tr >
	<td style='border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;'>
	<p><img src='../assets/images/AVogo.png' style='width: 150px;height: auto;'/></p>


	</td><td style='border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;'></td>
			
			<td  colspan='3' style='border-top:0;border-bottom:0;border-left:0;border-right:0;line-height:0;padding-top:2px;padding-left:6px;'>

	<p style='text-transform:uppercase;'>51/31, 60 Feet road, J.G Nagar, 2nd street, Tiruppur, Tamil Nadu - 641602</p>
	<p><b>GSTIN/UN:</b> 33EMTPP5008N1ZE</p>
	<p><b>Phone:</b> 04212477600</p>


			</td>


		</tr>

		<tr style='padding-top:10px;padding-bottom:10px'>

		<td colspan='2' style='border:0;'></td>
		</tr>	</tbody>	</table>";

$html .= "<table style='border-width:1px;border-color:#000;margin-bottom:0;marign-top:80px' class='items'>";
$html .= "	<thead>
					
					<tr class='headdetails' style='height:100px'><th ><b>Order No</b></th><th ><b>Date</b></th><th ><b>Items</b></th><th ><b> Status</b></th>
					</tr>
					</thead><tbody>";
while($datas = mysqli_fetch_array($getSQId)) {
   
	$osstatus = '';
	/*if($datas['osstatus'] != '')
	{
		$osstatus = $datas['osstatus'];
	}*/



			if($datas['order_status'] == 1){
				$color_class = 'color-designer';
			}
			elseif ($datas['order_status'] == 2){
				$color_class = 'color-printing';
			}
			elseif ($datas['order_status'] == 3){
				$color_class = 'color-lamination';
			}
			elseif ($datas['order_status'] == 4){
				$color_class = 'color-finishing';
			}
			elseif ($datas['order_status'] == 5){
				$color_class = 'color-ready-for-Delivery';
			}
			elseif ($datas['order_status'] == 6){
				$color_class = 'color-complete-delivery';
			}
			elseif ($datas['order_status'] == 7){
				$color_class = 'color-paid';
			}
			elseif ($datas['order_status'] == 8){
				$color_class = 'color-unpaid';
			}
			else {
				$color_class = '';
			}
			if(!empty($datas['osstatus']))
			{
				$osstatus = '<span class="custom_btn_class btn btn-success '.$color_class.'" >'.$record['osstatus'] .'</span>';
			}
			else{
				$osstatus = '<span class="custom_btn_class btn btn-success '.$color_class.'" >Unpaid</span>';
			}
	$date = date('d-M-Y', strtotime($datas['jo_date']));
	$typesnameval  = explode(',',$datas['typesnameval']);
			$itemnameval  = explode(',',$datas['itemnameval']);
			$va  = array();
			$fisrtComm  = 0;
			for($i=0;$i< count($itemnameval); $i++)
			{
				if($datas['types'] == 1)
				{
				
					if($fisrtComm == 0)
					{
						$va[] ='Commercial : '. $itemnameval[$i];

					}
					else{
						$va[] = $itemnameval[$i];

					}
						

				}else{
					$va[] = $typesnameval[$i].' : '.$itemnameval[$i];
				}
				$fisrtComm++;
			}

			
				$valsTypesItem = implode(',',$va);
	
    $html .= "	<tr><td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>".$datas['sono']."</td><td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>".$date."</td><td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>".$valsTypesItem."</td><td  style='padding-right: 10px;padding-top: 2px;padding-bottom:2px;text-align:right'>" . $osstatus. "</td></tr>";
  
}

   

$html .= "</tbody></table>";

//var_dump($html);
//exit;
ob_clean();
//$filename="Sales Order";
//    $mpdf=new mPDF('utf-8','A4-L');
$mpdf = new \Mpdf\Mpdf();
/*
$mpdf->SetTitle('AV Digital Press '.$filename);
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');*/
//    $mpdf->Output('AV_Sales_Order.pdf', 'I');
$mpdf->WriteHTML($html);
//$mpdf->Output();
$mpdf->Output('AV_Report.pdf', 'D');
mysqli_close($GLOBALS["___mysqli_ston"]);
?>