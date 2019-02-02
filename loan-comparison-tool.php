<?php
ob_start();
/*
Plugin Name: LOAN COMPARISON TOOL
Plugin URI: http://webencoder.net
Description: This is a custom made plugin for loan comparison.
Version: 1.0
Author: Web Encoder
Author URI: http://webencoder.net
License: GPLv2 or later
Text Domain: lct
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

include 'admin-end.php';
include 'user-end.php';




// ajax used in front end
add_action( 'init', 'lct_script_enqueuer');
function lct_script_enqueuer() {
    wp_register_script( "lct_js_front", plugin_dir_url( __FILE__ ).'js/lct_ajax.js', array('jquery') );
    wp_localize_script( 'lct_js_front', 'lctAjax', 
                        array( 
                            'ajaxurl' => admin_url( 'admin-ajax.php' ),
                            'ajax_nonce' => wp_create_nonce('ajax_csrf_check'),  
                        ));
    wp_enqueue_script('lct_js_front');
    wp_enqueue_script( 'bootstrap', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), '', false);
    
}
// ajax in the admin end
add_action( 'admin_enqueue_scripts', 'lct_script_enqueuer_admin');
function lct_script_enqueuer_admin() {
	wp_enqueue_script( 'lct-ajax-request', plugin_dir_url( __FILE__ ).'js/admin-ajax.js', array( 'jquery' ) );
}

/**
* Schedule Work
*/
// Let's do an hourly check
add_action('wp', 'activateMe');
 
function activateMe() {
	if ( !wp_next_scheduled( 'hourly_check' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'hourly_check');
	}
}
 
add_action('hourly_check', 'lct_scheduled_scarp');
 
function lct_scheduled_scarp() {
	// do something every hour
    add_option( 'myhack_extraction_length', '255', '', 'yes' );
}



/*get search result with filter*/
add_action( 'wp_ajax_lct_send_email', 'lct_send_email' );          //TO LOGGED USER
add_action( 'wp_ajax_nopriv_lct_send_email', 'lct_send_email' );   //TO UNLOGGED USER
function lct_send_email() {
	ob_clean();  

	$lender_image_array = [];
	query_posts(array( 
	'post_type' => 'lender',
	'showposts' => -1
	) );  

	while (have_posts()) : the_post();
		$lender_image_array[get_the_title()] = get_the_post_thumbnail_url(get_the_ID(),'full');
	endwhile;


    global $wpdb;
    $table_name = $wpdb->prefix . 'lct_csv_datsdsa';

    // variables
	$ids = rtrim($_POST['ids'], ',');

    $limit = get_option('lct_number_of_result_to_show');

	$query_str = "FROM $table_name WHERE id in ($ids) ORDER By ComparisonRate ASC";


    // make other address NOT primary
    $results = $wpdb->get_results("SELECT * ".$query_str, ARRAY_A);   
    $result_count = count($results);
    if($result_count == 2){
    	$col = 4;
    }else{
    	$col = 3;
    }

    /*get option from admin to show data*/
    $showing_options = rtrim(get_option('showing_options'), ',');
    $showing_options_array = explode(',', $showing_options);




	$name = $_POST['name'];
	$to = $_POST['email'];
	$subject = "Loan Comparison Shortlist";

	$str = '
	<table  cellspacing="0" cellpadding="10" border="0">
		<thead>
			<tr>
				<th></th>
				<th>';
	            	if (array_key_exists($results[0]['Lender'], $lender_image_array)) {
	   						$str .= '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[0]['Lender']].'" title="'.$lender_image_array[$results[0]['Lender']].'" alt="'.$lender_image_array[$results[0]['Lender']].'" value="'.$lender_image_array[$results[0]['Lender']].'">';
	   					}else{
	   						$str .= $results[0]['Lender'];
	   					}
						
				$str .= '
				</th>
				<th>';
	            	if (array_key_exists($results[1]['Lender'], $lender_image_array)) {
	   						$str .= '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[1]['Lender']].'" title="'.$lender_image_array[$results[1]['Lender']].'" alt="'.$lender_image_array[$results[1]['Lender']].'" value="'.$lender_image_array[$results[1]['Lender']].'">';
	   					}else{
	   						$str .= $results[1]['Lender'];
	   					}
					
				$str .= '
				</th>';
	            if($result_count==3){
				$str .= '
				<th>';
	            	if (array_key_exists($results[2]['Lender'], $lender_image_array)) {
	   						$str .= '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[2]['Lender']].'" title="'.$lender_image_array[$results[2]['Lender']].'" alt="'.$lender_image_array[$results[2]['Lender']].'" value="'.$lender_image_array[$results[2]['Lender']].'">';
	   					}else{
	   						$str .= $results[2]['Lender'];
	   					}
	            $str .= '
	            </th>';
	        		} 
			$str .= '
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
				<td>Variable</td>
				<td>Variable</td>
				<td>Variable</td>
			</tr>';
	        $loop_count =1;
	        foreach ($showing_options_array as $showing_options_single) {
	            $str .= '<tr class="'; if($loop_count %2 ==1){ $str .= 'even';}else{ $str .= 'odd';} $str .= '">';
	                $str .= '<td>'.get_option($showing_options_single).'</td>';
	                $str .= '<td>'.$results[0][$showing_options_single].'</td>';
	                $str .= '<td>'.$results[1][$showing_options_single].'</td>';
	                if($result_count==3){
	                $str .= '<td>'.$results[2][$showing_options_single].'</td>';
	            	}
	            $str .= '</tr>';
	            $loop_count++;
	        }
		$str .= '</tbody>
	</table>';


	$message = "
	<html>
	<head>
	<title>Loan Comparison Shortlist</title>
	</head>
	<body>
	<h2>Loan Comparison Shortlist</h2>
	".$str."
	</body>
	</html>
	";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: '.$name.' <info@erates.com.au>' . "\r\n";

	// $headers .= 'From: <webmaster@example.com>' . "\r\n";
	// $headers .= 'Cc: myboss@example.com' . "\r\n";

	mail($to,$subject,$message,$headers);

	echo 'done';
	die();
}

/*get search result with filter*/
add_action( 'wp_ajax_lct_data_search', 'lct_data_search' );          //TO LOGGED USER
add_action( 'wp_ajax_nopriv_lct_data_search', 'lct_data_search' );   //TO UNLOGGED USER
function lct_data_search() {  
	ob_clean();           //TO CLEAN EXTRA SPACE
	$output = '';
	
	/*get images*/
	$lender_image_array = [];
	query_posts(array( 
	'post_type' => 'lender',
	'showposts' => -1
	) );  
	while (have_posts()) : the_post();
		$lender_image_array[get_the_title()] = get_the_post_thumbnail_url(get_the_ID(),'full');
	endwhile;
	wp_reset_postdata();
	
	/*get images*/
	$product_array = [];
	query_posts(array( 
	'post_type' => 'product',
	'showposts' => -1
	) );  
	while (have_posts()) : the_post();
		$product_array[get_the_title()] = get_the_permalink();
	endwhile;
	wp_reset_postdata();

    global $wpdb;
    $table_name = $wpdb->prefix . 'lct_csv_datsdsa';

    // variables
    $amount = str_replace(',','',$_POST['amount']);
	$state = $_POST['state'];
	$purpose = $_POST['purpose'];
	$ReverseMortgage = $_POST['ReverseMortgage'];
	$NoDoc = $_POST['NoDoc'];
	$LowDoc = $_POST['LowDoc'];
	$Equity = $_POST['Equity'];
	$CreditImpaired = $_POST['CreditImpaired'];
	$FixedRate = $_POST['FixedRate'];
	$FixedPeriod = $_POST['FixedPeriod']*12;


	$query_str = "SELECT * FROM $table_name WHERE $state='Y' AND $purpose='Y' AND MinLoanAmount <= '$amount' And MaxLoanAmount >= '$amount'";
	
	if($ReverseMortgage !=null){
		$query_str .= " AND ReverseMortgage='$ReverseMortgage'";
	}
	if($NoDoc !=null){
		$query_str .= " AND NoDoc='$NoDoc'";
	}
	if($LowDoc !=null){
		$query_str .= " AND LowDoc='$LowDoc'";
	}
	if($Equity !=null){
		$query_str .= " AND Equity='$Equity'";
	}
	if($CreditImpaired !=null){
		$query_str .= " AND CreditImpaired='$CreditImpaired'";
	}
	if($FixedRate !=null){
		$query_str .= " AND FixedRate != ''";
		// $query_str .= " AND FixedRate <= '$FixedRate' And FixedRate >= '$FixedRate'";
	}
	if($FixedPeriod !=null){
		$query_str .= " AND FixedPeriod='$FixedPeriod'";
	}


	$query_str .= " ORDER By ComparisonRate ASC";
    $limit = get_option('lct_number_of_result_to_show');
    if(is_numeric($limit)){
        $query_str .= " LIMIT ".$limit;
    }
    $results = $wpdb->get_results($query_str, OBJECT); 
	
    foreach ($results as $result) {
		if (array_key_exists($result->Lender, $lender_image_array)) {
			$current_lender = '<img style="height:50px; width:50px;" class="img-responsive" src="'.$lender_image_array[$result->Lender].'">';
		}else{
			$current_lender = $result->Lender;
		}

		if (array_key_exists($result->Name, $product_array)) {
			$current_Name = '<a href="'.$product_array[$result->Name].'">'.$result->Name.'</a>';
		}else{
			$current_Name = '<a href="">'.$result->Name.'</a>';
		}

		$output .='
   				<div class="row">
   					<div class="col-md-12 product-link">
   						'.$current_Name.'
   					</div>
					</div>
       			<div class="row row-margin">
					<div class="col-md-3">'.$current_lender.'</div>
					<div class="col-md-2">'.$result->VariableRate.'</div>
					<div class="col-md-2">'.$result->FixedRate.'</div>
					<div class="col-md-2">'.$result->ComparisonRate.'</div>
					<div class="col-md-3"><input type="checkbox" id="'.$result->id.'" class="checked-lender"></div>
				</div>';	
	}
	echo $output;
	die();		      //IT IS A MAST FOR WORDPRESS AJAX
}



/*get ajax result for available product*/
add_action('wp_ajax_lct_get_available_product', 'lct_get_available_product');
function lct_get_available_product() {  
	ob_clean();

	global $wpdb;
	$table_name = $wpdb->prefix . 'lct_csv_datsdsa';

	// check this address is his or not
	$results = $wpdb->get_results("SELECT Name FROM $table_name ORDER By Name ASC", OBJECT); 
	$products = [];
	foreach ($results as $result) {
		array_push($products, $result->Name);
	}
	$products = array_unique($products);




	$html = '<table class="form-table">
			<tr class="user-display-name-wrap">
			<th><label for="product-new">Select A Product: </label></th>
			<td>
			<select name="product-new" id="product-new">';
	$html .= '<option value=""></option>';			
	foreach ($products as $key => $value) {
			$html .= '<option value="'.$value.'">'.$value.'</option>';		
	}
			
	$html .= '</select>
			</td>
			</tr>
			</tbody>
			</table>';
	echo $html;

	die();		      //IT IS A MAST FOR WORDPRESS AJAX
}

/*get ajax result for available lender*/
add_action('wp_ajax_lct_get_available_lender', 'lct_get_available_lender');
function lct_get_available_lender() {  
	ob_clean();

	global $wpdb;
	$table_name = $wpdb->prefix . 'lct_csv_datsdsa';

	// check this address is his or not
	$results = $wpdb->get_results("SELECT Lender FROM $table_name ORDER By Lender ASC", OBJECT); 
	$lenders = [];
	foreach ($results as $result) {
		array_push($lenders, $result->Lender);
	}
	$lenders = array_unique($lenders);




	$html = '<table class="form-table">
			<tr class="user-display-name-wrap">
			<th><label for="lender-new">Select A Lender: </label></th>
			<td>
			<select name="lender-new" id="lender-new">';
	$html .= '<option value=""></option>';			
	foreach ($lenders as $key => $value) {
			$html .= '<option value="'.$value.'">'.$value.'</option>';		
	}
			
	$html .= '</select>
			</td>
			</tr>
			</tbody>
			</table>';
	echo $html;

	die();		      //IT IS A MAST FOR WORDPRESS AJAX
}

/*
register_activation_hook( __FILE__, 'pu_create_plugin_tables' );
function pu_create_plugin_tables(){

    global $wpdb;

    $table_name = $wpdb->prefix . 'lct_csv_datsdsa';

    $query = "CREATE TABLE ".$table_name." (
		  	id int(11) NOT NULL AUTO_INCREMENT,
			productID varchar(80) DEFAULT NULL,
			Name varchar(255) DEFAULT NULL,
			Lender varchar(255) DEFAULT NULL,
			LenderProductName varchar(255) DEFAULT NULL,
			ProductDetails text DEFAULT NULL,
			VariableRate varchar(80) DEFAULT NULL,
			FixedRate varchar(80) DEFAULT NULL,
			FixedPeriod varchar(80) DEFAULT NULL,
			IntroDiscRate varchar(80) DEFAULT NULL,
			IntroDiscPeriod varchar(80) DEFAULT NULL,
			ApplicationFees varchar(80) DEFAULT NULL,
			DischargeFees varchar(80) DEFAULT NULL,
			OngoingFees varchar(80) DEFAULT NULL,
			OngoingFeesCycle varchar(80) DEFAULT NULL,
			ValuationFees varchar(80) DEFAULT NULL,
			LendersLegalFees varchar(80) DEFAULT NULL,
			LendersSettlementFees varchar(80) DEFAULT NULL,
			MinLoanAmount varchar(80) DEFAULT NULL,
			MaxLoanAmount varchar(80) DEFAULT NULL,
			LVROOPurchaseMin varchar(80) DEFAULT NULL,
			LVROOPurchaseMax varchar(80) DEFAULT NULL,
			LVROOSecurityMin varchar(80) DEFAULT NULL,
			LVROOSecurityMax varchar(80) DEFAULT NULL,
			LVRINVPurchaseMin varchar(80) DEFAULT NULL,
			LVRINVPurchaseMax varchar(80) DEFAULT NULL,
			LVRINVSecurityMin varchar(80) DEFAULT NULL,
			LVRINVSecurityMax varchar(80) DEFAULT NULL,
			LVRLandMin varchar(80) DEFAULT NULL,
			LVRLandMax varchar(80) DEFAULT NULL,
			OwnerOccupied varchar(80) DEFAULT NULL,
			Investment varchar(80) DEFAULT NULL,
			RepaymentOption varchar(80) DEFAULT NULL,
			GenuineSavingsMin varchar(80) DEFAULT NULL,
			ACT varchar(80) DEFAULT NULL,
			NSW varchar(80) DEFAULT NULL,
			TAS varchar(80) DEFAULT NULL,
			QLD varchar(80) DEFAULT NULL,
			VIC varchar(80) DEFAULT NULL,
			SA varchar(80) DEFAULT NULL,
			WA varchar(80) DEFAULT NULL,
			NT varchar(80) DEFAULT NULL,
			StandardVariable varchar(80) DEFAULT NULL,
			Fixed varchar(80) DEFAULT NULL,
			InterestInAdvance varchar(80) DEFAULT NULL,
			ReverseMortgage varchar(80) DEFAULT NULL,
			Basic varchar(80) DEFAULT NULL,
			NoDoc varchar(80) DEFAULT NULL,
			LowDoc varchar(80) DEFAULT NULL,
			Equity varchar(80) DEFAULT NULL,
			Intro varchar(80) DEFAULT NULL,
			CreditImpaired varchar(80) DEFAULT NULL,
			ProfessionalPack varchar(80) DEFAULT NULL,
			MinLoanTerm varchar(80) DEFAULT NULL,
			MaxLoanTerm varchar(80) DEFAULT NULL,
			RateDetails text DEFAULT NULL,
			FeeDetails text DEFAULT NULL,
			AutoAppvdCCard varchar(80) DEFAULT NULL,
			BpayAccess varchar(80) DEFAULT NULL,
			DebitCardAvailable varchar(80) DEFAULT NULL,
			DirectCreditAccess varchar(80) DEFAULT NULL,
			DirectDebitAccess varchar(80) DEFAULT NULL,
			EFTPOSAccess varchar(80) DEFAULT NULL,
			GiroPostAustPostAccess varchar(80) DEFAULT NULL,
			InternetBanking varchar(80) DEFAULT NULL,
			LOCCreditCardAutoSweep varchar(80) DEFAULT NULL,
			MortgageOffsetAvailable varchar(80) DEFAULT NULL,
			PhoneTransferAccess varchar(80) DEFAULT NULL,
			RedrawDetails text DEFAULT NULL,
			RedrawFacility varchar(80) DEFAULT NULL,
			TransactionAccountAvailable varchar(80) DEFAULT NULL,
			EarlyRepayBreakCostDetails text DEFAULT NULL,
			EarlyRepaymentBreakCosts varchar(80) DEFAULT NULL,
			LMIAvailable varchar(80) DEFAULT NULL,
			LMICapitalisationMaxLVR varchar(80) DEFAULT NULL,
			RateLockAvailable varchar(80) DEFAULT NULL,
			BridgingAvailable varchar(80) DEFAULT NULL,
			Construction varchar(80) DEFAULT NULL,
			SelfManagedSuperfund varchar(80) DEFAULT NULL,
			InterestCapitalisation varchar(80) DEFAULT NULL,
			InterestCapitalisationDetails text DEFAULT NULL,
			RepaymentsCanMakeExtra varchar(80) DEFAULT NULL,
			RepaymentsFortnightly varchar(80) DEFAULT NULL,
			RepaymentsMonthly varchar(80) DEFAULT NULL,
			RepaymentsWeekly varchar(80) DEFAULT NULL,
			SalaryDirectAvailable varchar(80) DEFAULT NULL,
			Portability varchar(80) DEFAULT NULL,
			SplitAvailable varchar(80) DEFAULT NULL,
			SplitDetails text DEFAULT NULL,
			ComparisonRate varchar(80) DEFAULT NULL,
			PRIMARY KEY (id)
		);";
    $wpdb->query($query);
}*/