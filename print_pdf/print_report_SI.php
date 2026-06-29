<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();



// header( "refresh:10;url=/index.php?erp=75" );



require_once '../vendor/autoload.php';



//include("../includes/db_config.php");

//include("mpdf.php");

include "../classes/NumbersToWords.php";



include "../includes/db_config.php";



include "../classes/class_invoice_commornon.php";



include "../classes/class_company.php";



$obj_nu = new NumbersToWords();

$obj_com = new Company('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');





$obj_si = new Invoice_commornon('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');





$data_id = $_REQUEST['soid'];



if (isset($data_id) && ($data_id != '')) {



	$redColor = [255, 0, 0];



	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();



	$obj_si->setpk_id($data_id);

	$getSQId = $obj_si->getSalesInvoiceById($data_id);

	$datas = mysqli_fetch_array($getSQId);



	//$obj_com->setCusId(1);

	$company = $obj_si->EditCustomer();

	$companyData = mysqli_fetch_array($company);



	/*$company = $obj_si->EditProduct();

			 $companyData = mysqli_fetch_array($company);



			 $company = $obj_si->Editcate();

			 $companyData = mysqli_fetch_array($company);*/



	$getSQAddress = $obj_si->getSalesInvoiceAddressById($data_id);

	$sqaddress = mysqli_fetch_array($getSQAddress);



	$unitArr = array();

	$scproduct = array();

	$gradeArr = array();

	$getSCProducts = $obj_si->getSalesInvoiceProductByPROId($data_id);



	while ($datass = mysqli_fetch_array($getSCProducts)) {



		$unitArr[] = $datass['uom'];

		$obj_si->setpk_id($data_id);

		$getSCProduct = $obj_si->getSalesInvoiceProductByIdForPDF($datass['PK_SEP_ID']);

		$product_id = $datass['product_id'];



		while ($data = mysqli_fetch_array($getSCProduct)) {

			//$data['type_tables'],$data['fk_item_id']



			if ($data['type_tables']) {

				$itemdata = $obj_si->getAllItemDataprint($data['table_pk_id'], $data['type_tables'], $data['fk_item_id']);

				$itemdataval = mysqli_fetch_array($itemdata);

				$data['itemname'] = $itemdataval['name'];

			} else {

				$data['itemname'] = 'Commercial';

			}



			//array_push($a,"blue","yellow");



			//    var_dump($itemdataval );

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



//$citycus = $obj_so->getCityName($address_city);

//$statecus = $obj_so->getStateName($address_state);



//$cityName = mysqli_fetch_array($city);

//$stateName = mysqli_fetch_array($state);



$getCustomer = $obj_si->getCustomer($datas['fk_customer_id']);

$getCustomerdata = mysqli_fetch_array($getCustomer);

//var_dump($getCustomerdata);

//exit;

$sq_no = $datas['sono'];
$gst_type = $datas['gst_type'];


$cus_name = $getCustomerdata['cus_name'];

$cus_code = $getCustomerdata['cus_code'];

$cus_address = ($getCustomerdata['cus_address']) ? $getCustomerdata['cus_address'] : '';

$cus_address_2 = ($getCustomerdata['cus_address_2']) ? $getCustomerdata['cus_address_2'] : '';

$cus_address_3 = ($getCustomerdata['cus_address_3']) ? $getCustomerdata['cus_address_3'] : '';

$cus_email = $getCustomerdata['cus_email'];

$cus_mob_no = $getCustomerdata['cus_mob_no'];

$cus_gst_no = $getCustomerdata['cus_gst_no'];

$cus_city = $getCustomerdata['cus_city'];

$cus_state = $getCustomerdata['cus_state'];

$cus_fax = $getCustomerdata['cus_fax'];



$citycus = $obj_si->getCityName($cus_city);

$statecus = $obj_si->getStateName($cus_state);



$cityName = mysqli_fetch_array($citycus);

$stateName = mysqli_fetch_array($statecus);







$g_no = $datas['reference_no'];

$sq_no = $datas['eno'];



$date = date('d-M-Y', strtotime($datas['date']));
$createddate = date('d-M-Y h:i:s a', strtotime($datas['created_on']));



$shipmentfrom = $datas['shipment_from'];

$shipmentto = $datas['shipment_to'];



$sq_total_discount = number_format($datas['gst_total'], 2);

$sq_total = number_format($datas['item_total'], 2);

$sq_grand_total = number_format($datas['grand_total'], 2);

$inword = $obj_nu->convertCurrencyToWords(sprintf("%01.2f", $datas['grand_total']));



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

		// text-transform: uppercase;

	}
	table {
		width: 100%;
		border:1px solid #000000!important;
		margin:0;
	

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
		// text-transform: uppercase;
		vertical-align: top;
		
	}
	table td table tr td {
		font-size:12px;
		// text-transform: uppercase;
	}
	table p{
		margin:5px 0;
	}
	table.items thead tr th{
		font-size:12px;
		// text-transform: uppercase;
	}
	table.items tbody tr td{
		font-size:12px;
		padding-bottom:1px;
		// text-transform: uppercase;
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
		main{
			border: 1px solid #000000 !important;
		}

		.align-left{
		text-align:left;
		}
		.align-right{
		text-align:right;
		}

	 @page {
    sheet-size: A4;
    margin: 20mm;
    /* Additional mPDF specific styles */
    /* Disable header repeat */
    mso-header-space: 0;
  }

  
	</style>
	</head>
	<body>
    <main >";

$html .= "<table style='border-width:unset;border-style:unset;border-color:unset;margin-bottom:0;border-bottom:0;'>
			<thead>
				<tr>
					<th style='border:0;vertical-align: baseline;width: 30%;font-size:15px;'><small>Page No: 1 of {nbpg}</small></th>
					<th style='border:0;vertical-align: baseline;width: 60%;font-size:15px;'></th>
					<th style='border:0;vertical-align: baseline;width: 10%;font-size:15px;'><small>" . $createddate . "</small></th>
				</tr>
				<tr>
					<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>
					<th colspan='' style='border:0;padding:0;padding-bottom:0;width: 60%'></th>
					<th style='border:0;width: 10%;padding-top:0;padding-bottom:0;'></th>
				</tr>
			</thead>
		</table>";

$html .= "<table style='padding:5px;border-top:1px solid #000000; border-bottom:0;border-left:1px solid #000000;border-right:1px solid #000000 ;margin-bottom:0;'>
			<tbody>
				<tr style='padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px'>
					<td  style='width:75%;font-family: serif !important; border:0;padding-top:5px;padding-bottom:0px;color:#1C2D4A;margin:0;padding:0;'>
					<table style='margin-bottom:0;border:0'>
						<tr>
							<td style='border-top:0; border-bottom:0; border-left:0; border-right:0;'>
								<h1> AV DIGITAL PRESS</h1>
								<p>51/31 J.G Nagar, 60 Feet Road 2nd Street, Tiruppur - 641602.<br/>Phone : 04212477600</p>
							</td>
						</tr>
						<tr>
							<td style='border-top:0; border-bottom:0; border-left:0; border-right:0;'>
						</td>
						</tr>
					</table>
					<td   style='float:right;width:25%;border-top:0; border-bottom:0; border-left:0; border-right:0; height 2px;padding-top:10px;line-height: 12px; padding-left:6px;font-size:11px;'>
						<p style='padding-bottom:10px;'><img src='../assets/images/AVogo.png' style='width: 130px; height: auto;padding-bottom:0;'></p><br/>
						
					
					</td>
				</tr>
		
			
			</tbody>
		</table>";

/*$html .= "<table style='border:0;marign-top:80px;'>

<tr >



<td style='border:0;margin-top:15px;padding-bottom:0;text-align:center;'><img src='data:image/png;base64," . base64_encode($generator->getBarcode($sq_no, $generator::TYPE_CODE_128)) . "' style='margin:20px;height: 60px;    width: 304px;'>

</td>    </tr>



</table>";

 */

$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;border:0;'>
				<tbody>
					<tr style='padding-top:5px;padding-bottom:5px'>
						<td colspan='2' style='border:0;'></td>
					</tr>
				</tbody>	
		</table>";

$html .= "<table  style='border-width:1px;border-style:solid;border-color:#000;margin-bottom:0;margin-bottom:0;border-bottom:0;'>
			<tbody><tr>

	<td   style='font-size:14px;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:0;border:0;'>
<p><b>GSTIN/UN :</b> 33EMTPP5008N1ZE</p>

	</td>
	<td colspan='2'  style='font-size:16px;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:0;border:0;'>
<p><b>TAX INVOICE</b></p>

	</td>
	<td   style='text-align:right;align-font-size:11px;padding-top:2px;padding-left:0;border:0;'>
<p><b>ORIGINAL FOR RECIPIENT</b></p>

	</td>

</tr></tbody></table>
				";
$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0;border-style:solid;border-color:#000;margin-bottom:0;margin-bottom:5px'>
			<tbody>
				";

$address_parts = [];

if (!empty($cus_address)) {
	$address_parts[] = $cus_address;
}
if (!empty($cus_address_2)) {
	$address_parts[] = $cus_address_2;
}
if (!empty($cus_address_3)) {
	$address_parts[] = $cus_address_3;
}
if (!empty($stateName['state'])) {
	$state_and_fax = $stateName['state'];
	if (!empty($cus_fax)) {
		$state_and_fax .= " - " . $cus_fax;
	}
	$address_parts[] = $state_and_fax;
}

$full_address = implode(', ', $address_parts);





$html .=       " 

<tr>
<td   style='border-top:0;font-size:13px;width:60%;vertical-align:baseline;padding-top:0;padding-left:0;border-width:1px;border-style:solid;border-color:#000;border-right:0;;'>
					<table style='margin-bottom:0;border:0'>
						<tr>
							<td colspan='3' style='vertical-align:middle;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px;text-align:center'><p style=''><b>Customer Detail</b></p></td>
						</tr>
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p><b>Name</b></p> </td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>" . $cus_name . "</td>
								
								</td>
						</tr>
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p><b>Address</b></p></td><td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>" . $full_address . " </td>
								</td>
						</tr>
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p><b>Mobile</b></p></td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>" . $cus_mob_no . "</td>
								</td>
						</tr>
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p><b>GSTIN</b></p></td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>01BOLCW1203D1MG</td>
								</td>
						</tr>
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p><b>Place of Supply</b></p></td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>Coimbatore</td>
								</td>
						</tr>

						</table>
				</td>
				<td   style='font-size:13px;width:40%;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:0;border-width:1px;border-style:solid;border-left:0;border-right:0;border-color:#000;'>
					<table style='margin-bottom:0;border:0'>
					<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p>Invoice No</p></td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'><b>".$sq_no."</b></td>
								</td>
						</tr>
			
						<tr>
							<td colspan='1' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>
								<p>Invoice Date</p></td>
								<td colspan='2' style='border:0;margin-top:0;border-top:0;border-left:0;border-right:0;padding-left:3px;font-size:11px'>".$date."</td>
								</td>
						</tr>
						
						
						
						
					</table>
				</td>
			</tr>
			
		</tbody>
	</table>";

$html .= "<table style='border-width:1px;border-color:#000;margin-bottom:0;marign-top:80px;	border-collapse: collapse;border-spacing: 0;' class='items'>";

$gstText = '';
$gst_total = 0;
$gstValue =0;
$gstTexttot ="";
$gst_totalval = '';

if ($datas['state'] == 33) {
    $gstText = "CGST ({$datas['gst_percent']}%) <br/> SGST ({$datas['sgst_percent']}%)";
    $gstTexttot = "Add : CGST  <br/> Add : SGST ";
    $gstValue = (intval($datas['gst_percent']) + intval($datas['sgst_percent'])).'%';
	$gst_total = floatVal($datas['gst_total']) + floatVal($datas['sgst_total']);
	$gst_totalval = floatVal($datas['gst_total']).'<br/>' ;
	$gst_totalval .= floatVal($datas['sgst_total']);

} else {
    $gstText = "IGST ({$datas['gst_percent']}%)";
	$gstTexttot ="Add : IGST ";

	$gstValue = (intval($datas['gst_percent'])).'%';
	$gst_total = floatVal($datas['gst_total']);
	$gst_totalval .= floatVal($datas['gst_total']);

}




$gst_typetext = '';

if ($gst_type == 1) {
	$gst_typetext = 'Inclusive of GST';
	
}
else{
	$gst_typetext = 'Exclusive of GST';
}



$html .= "	<thead style='display: table-header-group;'>
				<tr class='headdetails' style='height:100px'>
				<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b> Sr.No.</b></th>
				<th style='width:20%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>Name of Product / Service</b></th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	HSN Code</b></th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Price Type</b></th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Quantity</b></th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;'><b>	Rate</b></th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'>	<b>Taxable Value</b>[<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]<p><i>(".$gst_typetext.")</i></p></th>

					<th colspan='2' style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'>
					
					
					<table style='border:0;padding:0;margin:0'>
						<tr class='headdetails' style='padding:0;margin:0;height:50px'>
							<th colspan='2' style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'><b>	
					{$gstText}</b></th>
            		    </tr>
						<tr class='headdetails' style='padding:0;margin:0;height:50px'>
							<th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'><b>%</b></th>
							<th style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'><b>Amount</b></th>
            		    </tr>
					</table>
					
					</th>
					<th style='width:10%;padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'>	<b>Total</b>[<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</th>
				</tr>
				 
			</thead>";



$itemsTotal = 0;
$itemsqtyTotal = 0;
$itemstaxableTotal = 0;
$itemgstTotal = 0;
$totgstamtAll=0;
$gstPlusTotal = 0;
$itemstaxableTotal += $sq_total;

$html .= "<tbody>";
for ($i = 0; $i < count($scproduct); $i++) {
	$j = $i + 1;
	$type = '';
	$typesname = '';
	if ($scproduct[$i]['types'] == 1) {
		$typesname = 'Commercial';
	} else {
		$typesname = 'Non Commercial';
	}
	if ($scproduct[$i]['price_type'] == 1) {
		$pricetypes = 'First Copy';
	} else if ($scproduct[$i]['price_type'] == 2) {
		$pricetypes = 'Additional Copy';
	}
	$orientation = '';
	if ($scproduct[$i]['orientation'] == 1) {
		$orientation = 'Length';
	} else if ($scproduct[$i]['orientation'] == 2) {
		$orientation = 'Breadth ';
	}

	$taxableval = '';
	$totgstamt= 0;
if ($gst_type == 1) {
	$gstDecimal = $gstValue / 100;
	$totgstamt = ($scproduct[$i]['sep_qty'] * $scproduct[$i]['sep_price']) * $gstDecimal;
	$taxableval = $scproduct[$i]['sep_qty'] * $scproduct[$i]['sep_price'] - $totgstamt;
	$gstPlusTotal = $taxableval + $totgstamt;

}
else{
	$taxableval = $scproduct[$i]['sep_qty'] *  $scproduct[$i]['sep_price'] ;




$gstDecimal = $gstValue / 100;
$totgstamt = $taxableval * $gstDecimal;

$gstPlusTotal = $taxableval + $totgstamt;


}




	/*$html.=""ce (<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />)</b></th>
			 <th style=''><b>Items     <thead>
			 <tr>
			 <th style='padding-top:4px;padding-bottom:4px;'><b>S.No</b></th>
			 <th style='padding-top:4px;padding-bottom:4px;'><b>Product Name / Model No</b></th>
			 <th style=''><b>Inner Sheet</b></th>
			 <th style=''><b>Special Effects</b></th>
			 <th style=''><b> Size</b></th>
			 <th style=''><b>Quantity</b></th>
			 <th style=''><b>PriAmount (<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />)</b></th>
			 </tr>
			 </thead>*/
	$html .= "<tr>
				<td   style='width:5%;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0;'>".$j."</td>
				<td   style='text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0'>" . htmlspecialchars_decode($scproduct[$i]['itemnames']) . " </td>
				<td  style='text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0'> 4911</td>
				<td  style='text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0'> " . $pricetypes . "</td>
				<td  style='text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0;text-align:center'> " . $scproduct[$i]['sep_qty'] . "</td>
				<td  style='text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px;border-top:0;border-bottom:0;text-align:right'> " . $scproduct[$i]['sep_price'] . "</td>
				<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right;border-top:0;border-bottom:0'>" . number_format($taxableval, 2) . "</td>
				<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right;border-top:0;border-bottom:0'>{$gstValue}</td>
				<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right;border-top:0;border-bottom:0'>" . number_format($totgstamt, 2) . "</td>
				<td  style='padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right;border-top:0;border-bottom:0'>" . number_format($gstPlusTotal, 2) . "</td>
				

			</tr>
			
			";

		
		

	if ($scproduct[$i]['sep_total'] == '' || $scproduct[$i]['sep_total'] == null) {

		$scproduct[$i]['sep_total'] = 0;

	}

	$itemsTotal += $gstPlusTotal;
	$totgstamtAll += $totgstamt;

	$itemsqtyTotal += $scproduct[$i]['sep_qty'];
	$itemgstTotal += $gst_total;

}


if (count($scproduct) < 15) {
	for ($k = 0; $k < (15 - count($scproduct)); $k++) {
		$html .= "  <tr>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;text-transform:uppercase;padding-right: 5px;padding-top: 5px;padding-bottom:5px'></td>
						<td style='border-top:0 !important;border-bottom:0;padding-right: 5px;padding-top: 5px;padding-bottom:5px;text-align:right'></td>
					</tr>";
	}
}

if (empty($datas['gst_total'])) {

	$datas['gst_total'] = 0;

}



$gst_total = 0;

if ($datas['state'] == 33) {

	$gst_total = floatVal($datas['gst_total']) + floatVal($datas['sgst_total']);



} else {

	$gst_total = floatVal($datas['gst_total']);



}


$gstPlusTotal = $totgstamtAll + $gst_total;

$inword = $obj_nu->convertCurrencyToWords(sprintf("%01.2f", $datas['grand_total']));

//$html .= "</tbody></table><table style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-bottom:0;border-top:0;marign-top:80px' class='items'><tbody>





$html .= "</table><table style='border-width:1px;border-color:#000;margin-bottom:0;marign-top:80px;	border-collapse: collapse;border-spacing: 0;' class='items'>";



$html .= "<tr >
			 <td   colspan='4' style='width:20%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'> <b> Total  [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</b></td>

			<td  colspan='1' style='width:10%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:center;padding-right: 4px'> ".$itemsqtyTotal." </td>
			<td  colspan='1' style='width:10%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'> </td>
			<td  colspan='1' style='width:10%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'> ".$itemstaxableTotal."</td>
			<td  colspan='1' style='width:7%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'> </td>
			<td  colspan='1' style='width:8%;border-top:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>".number_format($totgstamtAll, 2)." </td>
			<td colspan='1' style='width:10%;border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'><b>" . $datas['grand_total'] . "</b></td>
		</tr>
		<tr>
			<td  colspan='10' style='height:20px;text-transform:uppercase;vertical-align:middle;padding-top:4px;padding-bottom:2px;'></td>
		</tr>
		";








$final_value = $gst_total + ($symboltype == '-' ? $datas['discount_final'] * -1 : $datas['discount_final']) + ($symboltype2 == '-' ? $datas['discount_final2'] * -1 : $datas['discount_final2']) + ($symboltype3 == '-' ? $datas['discount_final3'] * -1 : $datas['discount_final3']);


$html .= "  <tr>
           	 	<td  colspan='5' style='text-transform:uppercase;vertical-align:middle;padding-top:4px;padding-bottom:2px;text-align:center;font-size:11px;'><b>Total in words</b>
				</td>

            
				<td colspan='5' style='border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px;'>  
					<table style='width: 100%; border: 0;'>
						<tr>
							<td style='border: 0;text-align:left;font-size :10px;'><b>Taxable Amount [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />] </b><p><i>(".$gst_typetext.")</i></p></td>
							<td style='border: 0;text-align:right;font-size :10px;'><b>".number_format($itemstaxableTotal, 2)."</b></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td rowspan='2' colspan='5' style='text-transform:uppercase;vertical-align:middle;padding-top:4px;padding-bottom:2px;text-align:center;font-size:11px;'>" . $inword . " Only.</td>

		
				<td colspan='5' style='border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>
					<table style='width: 100%; border: 0;'>
						<tr>
							<td style='border: 0;text-align:left;font-size:10px;'> <b>{$gstTexttot}</b> </td>
							<td style='border: 0;text-align:left;font-size:10px;'><b>".$gst_totalval ."</b></td>
						</tr>
					</table>
				
				</td>
			</tr>
		 
			<tr>
				<td colspan='5' style='border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>
					<table style='width: 100%; border: 0;'>
						<tr>
							<td style='border: 0;text-align:left;font-size:10px;'><b>Total Tax [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</b></td>
							<td style='border: 0;text-align:right;font-size:10px;'><b>".number_format($totgstamtAll, 2)."</b></td>
						</tr>
					</table>
				</td>
			</tr>
		  	  <tr>
	          
				 <td  colspan='5' style='text-transform:uppercase;vertical-align:middle;padding-top:4px;padding-bottom:2px;text-align:center;font-size :11px;'><b>Bank Details</b></td>
	           <td colspan='5' style='border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>
				<table style='width: 100%; border: 0;'>
					<tr>
						<td style='border: 0;text-align:left;font-size:10px;'><b>Total Amount After Tax [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />] </b></td>
						<td style='border: 0;text-align:right;font-size:12px;'><b>".number_format($datas['grand_total'], 2)."</b></td>
					</tr>
        		</table>
			</td>
          </tr>
		  	 <tr>
	          
				 <td  colspan='5' rowspan='2' style='text-transform:uppercase;vertical-align:middle;padding-top:0px;padding-bottom:0px;'>
				 <table style='width: 100%; border: 0;'>
					<tr>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>Bank Name </b></td>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>State Bank Of India</b></td>
					</tr>
					<tr>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>AC No </b></td>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>00000042660579901</b></td>
					</tr>
					<tr>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>Branch </b></td>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>Avinashi Branch </b></td>
					</tr>
					<tr>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>IFSC Code</b></td>
						<td style='border: 0;text-align:left;font-size: 10px;'><b>SBIN00000759</b></td>
					</tr>
					
        		</table>
				</td>
	          
	        
          </tr>
		 
		
		  	 <tr>
	         
				 <td colspan='5' style='border-top:1px solid #000000;text-transform:uppercase;border-bottom:1px solid #000000;;padding-top:0;padding-bottom:0;text-align:right;padding-right: 4px;padding:0;margin:0;'>
				<table style='width: 100%; border: 0;padding:0;margin:0;'>
					<tr>
						<td colspan='2' style='border: 0;text-align:right;'><img src='../assets/images/avbsignsec.png' style='margin:0 auto;width: 220px; height: 50px;padding-bottom:0;'></td>
					</tr>
					<tr>
						<td colspan='2' style='border: 0;border-top:1px solid #000000;text-align:center;font-size: 10px;'><b>Authorised Signatory</b></td>
					</tr>
        		</table>
			</td>
	      
          </tr>
		  
		   <tr>
  <td  colspan='10'  style='padding-top:0;padding-bottom:0;border-collapse: collapse;border-spacing: 0;padding:0;margin:0;'>
					<table style='width: 100%; border: 0;border-collapse: collapse;border-spacing: 0;padding:0;margin:0;border-left:0;border-right:0;border-top:0;'>
							<tr >
				 				 <td  style='padding-top:0;padding-bottom:0;border-left:0;border-right:0;border-top:0;padding:0;margin:0;text-align:center'>
								 	<b>Terms and Conditions</b>
									</td>

									
							</tr>
							<tr>

										<td    style='border:0;font-size:11px;vertical-align:middle;padding-top:0;padding-bottom:0;'>
										1. Subject to Tiruppur Jurisdiction.<br/>
										2. Our responsibility ceases as soon as the goods leave our premises.<br/>
										3. Goods once sold will not be taken back.<br/>
										4. Delivery ex-premises.<br/>
									</td>
							</tr>
        			</table>
				</td>
		    </tr>
        </tbody>
      </table>
	  
	 
	  ";








$html .= "</main></body></html>";

// var_dump($html);
// exit;

ob_clean();

//$filename="Sales Order";

//$mpdf= new \Mpdf\MpdfmPDF('utf-8','A5-L');



$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 10,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0]);


$mpdf->SetTitle('AV Digital Press ' . $filename);



/*

$mpdf->SetTitle('AV Digital Press '.$filename);

$mpdf->WriteHTML($html);

$mpdf->SetDisplayMode('fullpage');*/

//    $mpdf->Output('AV_Sales_Order.pdf', 'I');

$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output('AVB_Graphics_Invoice.pdf', 'I');

mysqli_close($GLOBALS["___mysqli_ston"]);

?>

<!DOCTYPE html>

<html>

<body>



	<script>var delay = 1000;

		setTimeout(function () { window.location = URL; }, delay);</script>

</body>

</html>