<?php

ob_start();

header('refresh:10;url=/index.php?erp=71&typ=2');

require_once '../vendor/autoload.php';
include('../qrcode/libs/phpqrcode/qrlib.php');

//include( '../includes/db_config.php' );

//include( 'mpdf.php' );

include '../classes/NumbersToWords.php';

include '../includes/db_config.php';

include '../classes/class_estimate_commornon.php';

include '../classes/class_company.php';

$obj_nu = new NumbersToWords();

$obj_com = new Company('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

$obj_so = new Estimate_commornon('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

$data_id = $_REQUEST['soid'];

if (isset($data_id) && ($data_id != '')) {

	$redColor = [255, 0, 0];

	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

	$obj_so->setpk_sale_order($data_id);

	$getSQId = $obj_so->getEstimateNonCommById($data_id);

	$datas = mysqli_fetch_array($getSQId);

	//$obj_com->setCusId( 1 );

	$company = $obj_so->EditCustomer();

	$companyData = mysqli_fetch_array($company);

	/*$company = $obj_so->EditProduct();

    $companyData = mysqli_fetch_array( $company );

    $company = $obj_so->Editcate();

    $companyData = mysqli_fetch_array( $company );
    */

	$getSQAddress = $obj_so->getEstimateNonCommAddressById($data_id);

	$sqaddress = mysqli_fetch_array($getSQAddress);

	$unitArr = array();

	$scproduct = array();

	$gradeArr = array();

	$getSCProducts = $obj_so->getEstimateNonCommProductByPROId($data_id);

	while ($datass = mysqli_fetch_array($getSCProducts)) {

		$unitArr[] = $datass['uom'];

		$obj_so->setpk_sale_order($data_id);

		$getSCProduct = $obj_so->getEstimateNonCommByIdForPDF($datass['PK_ESP_ID']);

		$product_id = $datass['product_id'];

		while ($data = mysqli_fetch_array($getSCProduct)) {

			//$data[ 'type_tables' ], $data[ 'fk_item_id' ]

			/* $itemdata = $obj_so->getAllItemDataprint( $data[ 'table_pk_id' ], $data[ 'type_tables' ], $data[ 'fk_item_id' ] );

            $itemdataval = mysqli_fetch_array( $itemdata );

            $data[ 'itemname' ] = $itemdataval[ 'name' ];
            */

			//array_push( $a, 'blue', 'yellow' );

			//    var_dump( $itemdataval );

			$scproduct[] = $data;
		}
	}
}

$com_name = $companyData['com_name'];

$address_street = $companyData['com_address'];

$address_city = $companyData['com_city'];

$address_state = $companyData['com_state'];

$com_phone = $companyData['com_phone'];

$address_fax = $companyData['com_fax'];

$com_email_id = $companyData['com_email'];

$com_website = $companyData['com_website'];

//$citycus = $obj_so->getCityName( $address_city );

//$statecus = $obj_so->getStateName( $address_state );

//$cityName = mysqli_fetch_array( $city );

//$stateName = mysqli_fetch_array( $state );

$getCustomer = $obj_so->getCustomer($datas['customer_id']);

$getCustomerdata = mysqli_fetch_array($getCustomer);

//var_dump( $getCustomerdata );

//exit;

$sq_no = $datas['sono'];

$cus_name = $getCustomerdata['cus_name'];

$cus_code = $getCustomerdata['cus_code'];

$cus_address = ($getCustomerdata['cus_address']) ? $getCustomerdata['cus_address'] : '';

$cus_address_2 = ($getCustomerdata['cus_address_2']) ? ',' . $getCustomerdata['cus_address_2'] : '';

$cus_address_3 = ($getCustomerdata['cus_address_3']) ? ',' . $getCustomerdata['cus_address_3'] : '';

$cus_email = $getCustomerdata['cus_email'];

$cus_mob_no = $getCustomerdata['cus_mob_no'];

$cus_gst_no = $getCustomerdata['cus_gst_no'];

$cus_city = $getCustomerdata['cus_city'];

$cus_state = $getCustomerdata['cus_state'];

$cus_fax = $getCustomerdata['cus_fax'];

$citycus = $obj_so->getCityName($cus_city);

$statecus = $obj_so->getStateName($cus_state);

$cityName = mysqli_fetch_array($citycus);

$stateName = mysqli_fetch_array($statecus);

$g_no = $datas['reference_no'];

$sq_no = $datas['sono'];

$date = date('d-M-Y', strtotime($datas['sale_date']));
$createddate = date('d-M-Y h:i:s a', strtotime($datas['created_on']));

/*$datesss = new DateTime($datas[ 'created_on' ], new DateTimeZone('Asia/Kolkata'));
echo $datesss->format('Y-m-d H:i:s a') . "\n";
echo $createddate . "\n";
exit;*/

$shipmentfrom = $datas['shipment_from'];

$shipmentto = $datas['shipment_to'];

$sq_total_discount = number_format($datas['gst_total'], 2);

$sq_total = number_format($datas['item_total'], 2);

$sq_grand_total = number_format($datas['grand_total'], 2);

$inword = $obj_nu->convertCurrencyToWords(sprintf('%01.2f', $datas['grand_total']));

//var_dump( $scproduct );

//exit;


$item = "author";
if (isset($datas['sono'])) {

	//data to be stored in qr
	$PK_ES_ID = $data_id;

	$sono = $datas['sono'];
	$ordertype = '2';
	$item =  $sono . '#' . $PK_ES_ID . '#' . $ordertype;
	//file path
	$file = "../temp/" . $item . ".png";

	//other parameters
	$ecc = 'H';
	$pixel_size = 20;
	$frame_size = 5;

	// Generates QR Code and Save as PNG
	QRcode::png($item, $file, $ecc, $pixel_size, $frame_size);


	// Displaying the stored QR code if you want

	if (!isset($item)) {
		$item = "author";
	}
}

$terms_description = "

<br>

1. Goods under this  are supplied on your Account and Risk.<br>

2. The amount of this Sales Quote should be paid on the due date else interest @18% per annum and applicable GST will be charged from the date of sales quote till the actual payment.<br>

3. All payments should be made by RTGS/NEFT/A/c. payee cheque/Draft/irrevocable letter of credit drawn in favour of  AV Digital Press .<br>

4. All claims for Goods lost or damaged in transit or discrepancies of any kind (including shortages in weight and non-delivery) must be made upon the carrier or insurance Company as applicable.<br>

5. All disputes under this will be settled by Competent Court within the jurisdication of Madurai.<br>

";

//$mpdf = new mPDF( '', '', 0, '', 10, 10, 70, 15, 10, 10, 'A4-L' );

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



			<th style='border:0;vertical-align: baseline;width: 30%;font-size:15px;float:right'><small>Page No: 1 of {nbpg}</small><br><img src='../temp/" . @$item . ".png' style='width:100px; height:100px;'></th>
			<th style='border:0;vertical-align: baseline;width: 40%;font-size:18px;text-align:center'>	<h4> Order</h4>  </th>
			<th style='border:0;vertical-align: baseline;width: 30%;font-size:15px;float:left'><small>" . $createddate . "</small></th>
	
		</tr>
			

	

		<tr>

		<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>

		<th colspan='' style='border:0;padding:0;padding-bottom:0;width: 60%'></th>

		<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>



		</tr>



			</thead>

		</table>";

/*$html .= "<table style='border:0;marign-top:80px;'>

<tr >



<td style='border:0;margin-top:15px;padding-bottom:0;text-align:center;'><img src='data:image/png;base64," . base64_encode( $generator->getBarcode( $sq_no, $generator::TYPE_CODE_128 ) ) . "' style='margin:20px;height: 60px;    width: 304px;'>

</td>    </tr>



</table>";

*/
/*
$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;border:0;margin-bottom:5px'>

				<tbody>

				

				<tr><td  colspan='3' style='border-top:0;border-bottom:0;border-left:0;border-right:0;line-height:0;padding-top:2px;padding-left:6px;text-align:center'>

	



	

	

				</td>

	

	

			</tr>

			<tr style='padding-top:5px;padding-bottom:5px'>



			<td style='border:0;padding-top:5px;padding-bottom:0px;font-size:30px;text-align:center'></td>

			<td style='border:0;padding-top:5px;padding-bottom:0px;font-size:18px;text-align:center'><h4> Order</h4></td>

			<td style='border:0;padding-top:5px;padding-bottom:0px;'></td>





			</tr>

</tbody>	</table>";*/

$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:solid;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;margin-bottom:5px'>

				<tbody>





				<tr>

				<td   style='font-size:17px;width:60%;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;border-width:1px;border-style:solid;border-color:#000;'>

							<table style='border-width:0px;border-style:solid;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;'>



							<tr><td  style='border:0;padding:0;padding-bottom:2px;font-size:17px'><p style='float:left'>Date:</p><p style='text-transform:uppercase;'></td><td style='border:0;padding:0;padding-bottom:2px;font-size:17px'>" . $date . "</p></td>



							</tr>

							<tr >

							<td  style='border:0;padding:0;padding-bottom:2px;font-size:17px'><p>Order:</p></td><td style='border:0;padding:0;padding-bottom:2px;font-size:17px'><p style='text-transform:uppercase;'><b>" . $sq_no . "</b></p></td>

							</tr>

							<tr >

							<td  style='border:0;padding:0;padding-bottom:2px;font-size:17px'><p>Customer Code:</p></td><td style='border:0;padding:0;padding-bottom:2px;font-size:17px'><p style='text-transform:uppercase;'>" . $cus_code . "</p></td>

							</tr>

							</table>

						</td>

						<td   style='font-size:17px;width:60%;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;border-width:1px;border-style:solid;border-color:#000;'><p>Customer Details : <b>" . $cus_name . "</b></p>

						<table style='border-width:0px;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;'>

						<tr>

							<td colspan='2' style='border-left:0;border-right:0;border:0;padding:0;padding-bottom:3px;font-size:15px'></td><td style='border:0;padding:0;padding-bottom:3px;font-size:17px'></td>

							</tr>

							<tr>

						

							<td colspan='2' style='border:0;padding:0;padding-bottom:3px;font-size:15px'><p></p></td><td style='border:0;padding:0;padding-bottom:3px;font-size:15px'><p>Mode / Franchise: <b>" . $datas['cat_name'] . "</b></p></td>

							</tr>

					





						</table>

					</td>







				</tbody>

			</table>

			";

$html .= "<table style='border-width:1px;border-color:#000;margin-bottom:0;marign-top:80px' class='items'>";

$html .= "	<thead>

				<tr class='headdetails' style='height:100px'><th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>ItemType</b></th><th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Item</b></th><th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Orientation</b></th><th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>Price Type</b></th><th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Quantity</b></th>

					</tr>

					</thead>";

$itemsTotal = 0;

for (
	$i = 0;
	$i < count($scproduct);
	$i++
) {

	$j = $i + 1;

	$type = '';

	$sop_CGST_amount = ($scproduct[$i]['sop_CGST_percentage'] != 0) ? Floatval($scproduct[$i]['final_total']) * (Floatval($scproduct[$i]['sop_CGST_percentage']) / 100) : '';

	$sop_SGST_amount = ($scproduct[$i]['sop_SGST_percentage'] != 0) ? Floatval($scproduct[$i]['final_total']) * (Floatval($scproduct[$i]['sop_SGST_percentage']) / 100) : '';

	$sop_IGST_amount = ($scproduct[$i]['sop_IGST_percentage'] != 0) ? Floatval($scproduct[$i]['final_total']) * (Floatval($scproduct[$i]['sop_IGST_percentage']) / 100) : '';

	if ($scproduct[$i]['types'] == 1) {

		$typesname = 'Commercial';
	} else {

		$typesname = 'Non Commercial';
	}

	$pricetypes = '';

	if ($scproduct[$i]['itemtype'] == 4 || $scproduct[$i]['itemtype'] == 5) {
		if ($scproduct[$i]['price_type'] == 1) {

			$pricetypes = ' 4 Color ';
		} else if ($scproduct[$i]['price_type'] == 2) {

			$pricetypes = '7 Color ';
		}
	}

	$orientation = '';

	if ($scproduct[$i]['orientation'] == 1) {

		$orientation = 'Length';
	} else if ($scproduct[$i]['orientation'] == 2) {

		$orientation = 'Breadth';
	}

	/*$html .= ''ce ( <img src = '../assets/images/RS.png' style = 'width: 13px;height: 8px;'  /> )</b></th>

    <th style = ''><b>Items     <thead>

    <tr>

    <th style = 'padding-top:4px;padding-bottom:4px;'><b>S.No</b></th>

    <th style = 'padding-top:4px;padding-bottom:4px;'><b>Product Name / Model No</b></th>

    <th style = ''><b>Inner Sheet</b></th>

    <th style = ''><b>Special Effects</b></th>

    <th style = ''><b> Size</b></th>

    <th style = ''><b>Quantity</b></th>

    <th style = ''><b>PriAmount ( <img src = '../assets/images/RS.png' style = 'width: 13px;height: 8px;'  /> )</b></th>

    </tr>

    </thead>*/

	$html .= '<tbody>';

	if ($scproduct[$i]['fk_items_id'] > 0) {

		$html .= "	<tr>

					

							<td   style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-transform:uppercase'>" . $scproduct[$i]['types_name'] . " </td>

							<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-transform:uppercase;'> " . htmlspecialchars_decode($scproduct[$i]['itemnames']) . "</td>

							<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-transform:uppercase;'> " . $orientation . "</td>

							<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-transform:uppercase;'> " . $pricetypes . "</td>



							<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-transform:uppercase'> " . $scproduct[$i]['qty'] . "</td>

						

							</tr>

							";
	}

	if ($scproduct[$i]['final_total'] == '' || $scproduct[$i]['final_total'] == null) {

		$scproduct[$i]['final_total'] = 0;
	}

	$itemsTotal += $scproduct[$i]['final_total'];

	if ($scproduct[$i]['itemtype'] == 4) {

		$sheetTotal += $scproduct[$i]['qty'];
	}
}

if (empty($datas['gst_total'])) {

	$datas['gst_total'] = 0;
}

$gstPlusTotal = $datas['item_total'] + $datas['gst_total'];

$inword = $obj_nu->convertCurrencyToWords(sprintf('%01.2f', $datas['grand_total']));

$html .= "</tbody>

				</table>";

$remarksSection = "<table style='display: table;'>

<tr><td colspan='5' style='border-right:0 !important;border-bottom:0 !important;padding-top:2px;padding-bottom:4px;font-size:12px;float:left'>Remarks: <b>" . $datas['remark'] . "</b></td>            <td style='border-left:0 !important;border-right:0 !important;border-bottom:0 !important;padding-top:2px;padding-bottom:4px;font-size:12px' align='right'><span style='text-align:right'>" . $advanceSection . "<b>Sheets:</b> " . $sheetTotal . "</span></td>
</tr>	  
<tr  style='padding-bottom:30px;padding:10px;width:100%'>

<td  style='border:0;padding:10px;font-size:12px;font-weight:800;'><p >Booking:</p><b>" . $datas['createdby'] . "</b>   </td>
 <td style='border:0;padding:10px;font-size:12px;font-weight:800;'><p >Designing:</p>             </td>
<td  style='border:0;padding:10px;font-size:12px;;font-weight:800;'><p>Cropping:</p>   </td>
 <td  style='border:0;padding:10px;font-size:12px;font-weight:800;'><p >Printing:</p>  </td>
 <td style='border:0;padding:10px;font-size:12px;font-weight:800;'><p>Making :</p> </td>
<td  style='border:0;padding:10px;font-size:12px;font-weight:800;'><p >Finishing :</p> </td>
</tr> </table> </body></html>";

$html .= $remarksSection;

//var_dump( $html );

//exit;

ob_clean();

//$filename = 'Sales Order';

//$mpdf = new \Mpdf\MpdfmPDF( 'utf-8', 'A5-L' );

$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle('AV Digital Press ' . $filename);

/*

$mpdf->SetTitle( 'AV Digital Press '.$filename );

$mpdf->WriteHTML( $html );

$mpdf->SetDisplayMode( 'fullpage' );
*/

//    $mpdf->Output( 'AV_Sales_Order.pdf', 'I' );

$mpdf->WriteHTML($html);

$mpdf->Output($cus_name . '.pdf', 'I');

mysqli_close($GLOBALS['___mysqli_ston']);
