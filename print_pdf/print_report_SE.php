<?php
ob_start();
require_once '../vendor/autoload.php';

//include("../includes/db_config.php");
//include("mpdf.php");
include "../classes/NumbersToWords.php";

include "../includes/db_config.php";

include("../classes/class_sale_estimate.php");
include "../classes/class_company.php";

$obj_nu = new NumbersToWords();
$obj_com = new Company('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$obj_saleestimate = new Estimate('', '', '', '','', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

$data_id = $_REQUEST['soid'];

if (isset($data_id) && ($data_id != '')) {

    $redColor = [255, 0, 0];

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

    $obj_saleestimate->setpk_id($data_id);
	
    $getSQId = $obj_saleestimate->getSalesEstimateById($data_id);
    $datas = mysqli_fetch_array($getSQId);

    //$obj_com->setCusId(1);
    $company = $obj_saleestimate->EditCustomer();
    $companyData = mysqli_fetch_array($company);

    $getSQAddress = $obj_saleestimate->getSalesEstimateAddressById($data_id);
    $sqaddress = mysqli_fetch_array($getSQAddress);

    $unitArr = array();
    $scproduct = array();
    $gradeArr = array();
    $getSCProducts = $obj_saleestimate->getSalesEstimateProductByPROId($data_id);

    while ($datass = mysqli_fetch_array($getSCProducts)) {
		
        $unitArr[] = $datass['uom'];
        $obj_saleestimate->setpk_id($data_id);
        $getSCProduct = $obj_saleestimate->getSalesEstimateProductByIdForPDF($datass['PK_SEP_ID']);
	
        while ($data = mysqli_fetch_array($getSCProduct)) {
		
//$data['type_tables'],$data['fk_item_id']
		/*	$itemdata = $obj_saleestimate->getAllItemDataprint($data['table_pk_id'],$data['type_tables'],$data['fk_item_id']);
			$itemdataval =  mysqli_fetch_array($itemdata);
			$data['itemname'] =	$itemdataval['name'];*/
			//array_push($a,"blue","yellow");

		//	var_dump($itemdataval );
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

$city = $obj_saleestimate->getCityName($address_city);
$state = $obj_saleestimate->getStateName($address_state);

$cityName = mysqli_fetch_array($city);
$stateName = mysqli_fetch_array($state);

$getCustomer = $obj_saleestimate->getCustomer($datas['fk_customer_id']);
$getCustomerdata = mysqli_fetch_array($getCustomer);

$date = date('d-M-Y', strtotime($datas['created_on']));
$cus_name = $datas['cus_name'];
$cus_code = $datas['cus_code'];

$g_no = $datas['reference_no'];
$sq_no=$datas['eno'];

$date = date('d-M-Y', strtotime($datas['sale_date']));


$fmt = new NumberFormatter('hi_IN', NumberFormatter::CURRENCY);
$fmt->setSymbol(NumberFormatter::CURRENCY_SYMBOL, '');
$fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

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

	$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;border:0;margin-bottom:10px'>
	<tbody>
	<tr >
	<td style='border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;'>
	<p><img src='../assets/images/AVogo.png' style='width: 150px;height: auto;'/></p>


	</td><td style='border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;'></td>
				<td style='border:0;margin-top:15px;padding-bottom:0;text-align:right;'><img src='data:image/png;base64," . base64_encode($generator->getBarcode($sq_no, $generator::TYPE_CODE_128)) . "' style='margin:20px;height: 60px;	width: 200px;'>
				</td>


			</tr>
			<tr>
			<td  colspan='3' style='border-top:0;border-bottom:0;border-left:0;border-right:0;line-height:0;padding-top:2px;padding-left:6px;'>

	<p style='text-transform:uppercase;'>51/31, 60 Feet road, J.D Nagar, 2nd street, Tiruppur, Tamil Nadu - 641602</p>
	<p><b>GSTIN/UN:</b> 33EMTPP5008N1ZE</p>
	<p><b>Phone:</b> 04212477600</p>


			</td>


		</tr>

		<tr style='padding-top:10px;padding-bottom:10px'>

		<td colspan='2' style='border:0;'></td>
		</tr>	</tbody>	</table>";

$html .= "<table cellspacing='0' cellpadding='0' style='border-width:0px;border-style:solid;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;margin-bottom:10px'>
	<tbody>


	<tr>
			<td   style='width:60%;border-top:0;border-bottom:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;border:0;'>
				<table style='border-width:0px;border-style:solid;border-color:#000;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;'>

				<tr><td  style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p style='float:left'>Date:</p><p style='text-transform:uppercase;'></td><td style='border:0;padding:0;padding-bottom:2px;font-size:16px'>" . $date . "</p></td>

				</tr>
				<tr >
				<td  style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p>Order:</p></td><td style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p style='text-transform:uppercase;'><b>" . $sq_no . "</b></p></td>
				</tr>
			
				</table>
			</td>
			<td   style='font-size:16px;width:60%;border-top:0;border-bottom:0;border-left:0;border-right:0;vertical-align:baseline;line-height:0;padding-top:2px;padding-left:6px;'><p><b>Customer Details :</b></p>
			<table style='border-width:0px;margin-bottom:0;border-top:0;border-bottom:0;border-left:0;border-right:0;'>

			<tr>
				<td colspan='2' style='border-left:0;border-right:0;border:0;padding:0;padding-bottom:2px;font-size:16px'></td><td style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p style='text-transform:uppercase;'>" . $sqaddress['cus_name'] . "</p></td>
				</tr>
				<tr>
				<td colspan='2' style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p></p></td><td style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p>" . $cityName['city'] . "," . $stateName['state'] . "-" . $sqaddress['cus_std_code'] . "</p></td>
				</tr>
				<tr>
				<td colspan='2' style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p></p></td><td style='border:0;padding:0;padding-bottom:2px;font-size:16px'><p>" . $sqaddress['cus_phone'] . "</p></td>
			</tr>

			</table>
		</td>



	</tbody>
</table>
";
$html .= "<table style='border-width:1px;border-color:#000;margin-bottom:0;marign-top:80px' class='items'>";
$html .= "	<thead>
					
					<tr class='headdetails' style='height:100px'><th colspan='2'><b>Product	</b></th><th  ><b>Category </b></th><th  ><b>Item Types</b></th><th  ><b>Item </b></th><th ><b>	Quantity</b></th><th style='text-align:right'>	<b>Price</b>[<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</th><th style='text-align:right'>	<b>Total</b>[<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</th>
					</tr>
					</thead>";
$itemsTotal = 0;
for ($i = 0; $i < count($scproduct); $i++) {
    $j = $i + 1;
    $type = '';

   
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

    $html .= "<tbody>";
    $html .= "	<tr>
						`	<td colspan='2'  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>" . $scproduct[$i]['product_name'] . "</td>
							<td   style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>" . $scproduct[$i]['cat_name'] . "</td>`
							<td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'>" . $scproduct[$i]['types_name'] . "</td>
						
						
							<td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px;'>". htmlspecialchars_decode($scproduct[$i]['itemnames']) . " </td>
							<td  style='text-transform:uppercase;padding-right: 10px;padding-top: 2px;padding-bottom:2px'> " . $scproduct[$i]['sep_qty'] . "</td>
							<td  style='padding-right: 10px;padding-top: 2px;padding-bottom:2px;text-align:right'>" . number_format($scproduct[$i]['sep_price'], 2) . "</td>
							<td  style='padding-right: 10px;padding-top: 2px;padding-bottom:2px;text-align:right'>" . number_format($scproduct[$i]['sep_total'], 2) . "</td>
							</tr>
							";
    if ($scproduct[$i]['sep_total'] == '' || $scproduct[$i]['sep_total'] == null) {
        $scproduct[$i]['sep_total'] = 0;
    }
    $itemsTotal += $scproduct[$i]['sep_total'];
}

   
    if (empty($datas['gst_total'])) {
        $datas['gst_total'] = 0;
    }

$gstPlusTotal = $datas['item_total'] + $datas['gst_total'];
$inword = $obj_nu->convertCurrencyToWords(sprintf("%01.2f", $datas['grand_total']));
$html .= "</tbody>
				</table><table style='border-width:0px;border-style:none;border-color:#000;margin-bottom:0;border-bottom:0;border-top:0;marign-top:80px' class='items'><tbody>


					<tr>


						<td style='border-right:0;border-bottom:0;border-right:0'></td>
						<td style='border-right:0;border-bottom:0;border-left:0;border-right:0'></td>
						<td style='border-right:0;border-left:0;border-right:0'></td>
						<td style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>Sub Total [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</td>
						<td colspan='1' style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>" . number_format($datas['item_total'], 2) . "</td>

					</tr>
					<tr>


					<td style='border-right:0;border-bottom:0;border-right:0'></td>
					<td style='border-right:0;border-bottom:0;border-left:0;border-right:0'></td>
					<td style='border-right:0;border-left:0;border-right:0'></td>
					<td style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>Discount (" . $datas['discount_final'] . "%) [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</td>
					<td colspan='1' style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>" . number_format($datas['discount_final_amt'], 2) . "</td>

				</tr>
					<tr>


						<td style='border-right:0;border-bottom:0;border-right:0'></td>
						<td style='border-right:0;border-bottom:0;border-left:0;border-right:0'></td>
						<td style='border-right:0;border-bottom:0;border-left:0;border-right:0'></td>
						<td  style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>GST Total(" . $datas['gst_percent'] . "%) [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</td>
						<td colspan='1' style='padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>" . number_format($datas['gst_total'], 2) . "</td>

					</tr>
					<tr>

					<td rowspan='12' colspan='3' style='text-transform:uppercase;vertical-align:middle;padding-top:4px;padding-bottom:2px;'><img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />, In Words : " . $inword . " Only.</td>
					<td  style='border-top:0;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'> Total  [<img src='../assets/images/RS.png' style='width: 13px;height: 8px;'  />]</td>
					<td colspan='1' style='border-top:0;text-transform:uppercase;border-bottom:1px solid #000000;padding-top:2px;padding-bottom:2px;text-align:right;padding-right: 4px'>" . $datas['grand_total'] . "</td>

				</tr>


				</tbody>
			</table>";

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
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('AV_Sales_Estimate.pdf', 'I');
mysqli_close($GLOBALS["___mysqli_ston"]);
?>