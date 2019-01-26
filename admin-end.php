<?php
ob_start();
/**
* Admin Menu
*/
add_action('admin_menu', 'loan_menu');
 
function loan_menu(){
    add_menu_page( 'LOAN TOOL', 'LOAN TOOL', 'manage_options', 'setting-lct', 'setting_lct' );
}

/**
* Settings page
*/
function setting_lct(){
	global $wpdb;
	$message = "";
	if(isset($_POST["lct_setting_submit"])){

		$lct_csv_ftp_link = $_POST["lct_csv_ftp_link"];
		$lct_interval_hour = $_POST["lct_interval_hour"];
			
		update_option( 'lct_csv_ftp_link', $lct_csv_ftp_link);
		update_option( 'lct_interval_hour', $lct_interval_hour);

		// Downlaod from ftp
		$new_path = dirname(__FILE__).'/csv/downloaded.csv';
		downloadUrlToFile($lct_csv_ftp_link, $new_path);

		// Read CSV file
	    $handle = fopen($new_path, "r");

	    // loop to read csv
	    $query_string = "";
	    $run_query = false;
	    while($data = fgetcsv($handle)){

	    	if (($data[88] != "") && ($run_query)) {
	    		$query_string .= "('".esc_sql($data[0])."', '".esc_sql($data[1])."', '".esc_sql($data[2])."', '".esc_sql($data[3])."', '".esc_sql($data[4])."', '".esc_sql($data[5])."', '".esc_sql($data[6])."', '".esc_sql($data[7])."', '".esc_sql($data[8])."', '".esc_sql($data[9])."', '".esc_sql($data[10])."', '".esc_sql($data[11])."', '".esc_sql($data[12])."', '".esc_sql($data[13])."', '".esc_sql($data[14])."', '".esc_sql($data[15])."', '".esc_sql($data[16])."', '".esc_sql($data[17])."', '".esc_sql($data[18])."', '".esc_sql($data[19])."', '".esc_sql($data[20])."', '".esc_sql($data[21])."', '".esc_sql($data[22])."', '".esc_sql($data[23])."', '".esc_sql($data[24])."', '".esc_sql($data[25])."', '".esc_sql($data[26])."', '".esc_sql($data[27])."', '".esc_sql($data[28])."', '".esc_sql($data[29])."', '".esc_sql($data[30])."', '".esc_sql($data[31])."', '".esc_sql($data[32])."', '".esc_sql($data[33])."', '".esc_sql($data[34])."', '".esc_sql($data[35])."', '".esc_sql($data[36])."', '".esc_sql($data[37])."', '".esc_sql($data[38])."', '".esc_sql($data[39])."', '".esc_sql($data[40])."', '".esc_sql($data[41])."', '".esc_sql($data[42])."', '".esc_sql($data[43])."', '".esc_sql($data[44])."', '".esc_sql($data[45])."', '".esc_sql($data[46])."', '".esc_sql($data[47])."', '".esc_sql($data[48])."', '".esc_sql($data[49])."', '".esc_sql($data[50])."', '".esc_sql($data[51])."', '".esc_sql($data[52])."', '".esc_sql($data[53])."', '".esc_sql($data[54])."', '".esc_sql($data[55])."', '".esc_sql($data[56])."', '".esc_sql($data[57])."', '".esc_sql($data[58])."', '".esc_sql($data[59])."', '".esc_sql($data[60])."', '".esc_sql($data[61])."', '".esc_sql($data[62])."', '".esc_sql($data[63])."', '".esc_sql($data[64])."', '".esc_sql($data[65])."', '".esc_sql($data[66])."', '".esc_sql($data[67])."', '".esc_sql($data[68])."', '".esc_sql($data[69])."', '".esc_sql($data[70])."', '".esc_sql($data[71])."', '".esc_sql($data[72])."', '".esc_sql($data[73])."', '".esc_sql($data[74])."', '".esc_sql($data[75])."', '".esc_sql($data[76])."', '".esc_sql($data[77])."', '".esc_sql($data[78])."', '".esc_sql($data[79])."', '".esc_sql($data[80])."', '".esc_sql($data[81])."', '".esc_sql($data[82])."', '".esc_sql($data[83])."', '".esc_sql($data[84])."', '".esc_sql($data[85])."', '".esc_sql($data[86])."', '".esc_sql($data[87])."', '".esc_sql($data[88])."'),"; 
	    	}
	    	$run_query = true;
	    }
	    $query_string = rtrim($query_string, ',');
	    fclose($handle);

        global $wpdb;
	    $table_name = $wpdb->prefix . 'lct_csv_datsdsa';
	    $wpdb->query('TRUNCATE TABLE '.$table_name);
	    $wpdb->query("INSERT INTO ".$table_name."
            (productID, Name, Lender, LenderProductName, ProductDetails, VariableRate, FixedRate, FixedPeriod, IntroDiscRate, IntroDiscPeriod, ApplicationFees, DischargeFees, OngoingFees, OngoingFeesCycle, ValuationFees, LendersLegalFees, LendersSettlementFees, MinLoanAmount, MaxLoanAmount, LVROOPurchaseMin, LVROOPurchaseMax, LVROOSecurityMin, LVROOSecurityMax, LVRINVPurchaseMin, LVRINVPurchaseMax, LVRINVSecurityMin, LVRINVSecurityMax, LVRLandMin, LVRLandMax, OwnerOccupied, Investment, RepaymentOption, GenuineSavingsMin, ACT, NSW, TAS, QLD, VIC, SA, WA, NT, StandardVariable, Fixed, InterestInAdvance, ReverseMortgage, Basic, NoDoc, LowDoc, Equity, Intro, CreditImpaired, ProfessionalPack, MinLoanTerm, MaxLoanTerm, RateDetails, FeeDetails, AutoAppvdCCard, BpayAccess, DebitCardAvailable, DirectCreditAccess, DirectDebitAccess, EFTPOSAccess, GiroPostAustPostAccess, InternetBanking, LOCCreditCardAutoSweep, MortgageOffsetAvailable, PhoneTransferAccess, RedrawDetails, RedrawFacility, TransactionAccountAvailable, EarlyRepayBreakCostDetails, EarlyRepaymentBreakCosts, LMIAvailable, LMICapitalisationMaxLVR, RateLockAvailable, BridgingAvailable, Construction, SelfManagedSuperfund, InterestCapitalisation, InterestCapitalisationDetails, RepaymentsCanMakeExtra, RepaymentsFortnightly, RepaymentsMonthly, RepaymentsWeekly, SalaryDirectAvailable, Portability, SplitAvailable, SplitDetails, ComparisonRate)
            VALUES
        	".$query_string);

		$message = '<div id="message" class="updated notice is-dismissible"><p>Settings successfully<strong> saved</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}else{
		$lct_csv_ftp_link = get_option('lct_csv_ftp_link');
		$lct_interval_hour = get_option('lct_interval_hour');
	}


	echo '
		<div class="wrap">
			<h2>Set the settings for Load Comparison Tool.</h2>
			<br>
			<br>
			'.$message.'
			<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">
	
					<label for="lct_csv_ftp_link"  ><div class="text-left">FTP link for CSV file:</div></label>
					<input name="lct_csv_ftp_link" type="text" class="regular-text myDatepicker" id="lct_csv_ftp_link" value="'.$lct_csv_ftp_link.'">
					<br><br>
					<label for="lct_interval_hour"  ><div class="text-left">Interval period in hour:</div></label>
					<input name="lct_interval_hour" type="text" class="regular-text myDatepicker" id="lct_interval_hour" value="'.$lct_interval_hour.'">
					<br><br>

					<button name="lct_setting_submit" type="submit" class="page-title-action">Submit</button>
			</form>
			
		</div>
	';
}


/**
* download file from ftp
*/
function downloadUrlToFile($url, $path)
{   
    if(is_file($url)) {
        copy($url, $path); 
    } else {
        $options = array(
          CURLOPT_FILE    => fopen($path, 'w'),
          CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
          CURLOPT_URL     => $url
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
    }
}



/*
* Creating a function to create our CPT
*/
 
function lct_custom_post_type() {
 
    register_post_type( 'lender', array(
        'labels' => array(
            'name' 			=> __( 'Lenders' ),
            'singular_name' => __( 'Lender' ),
            'add_new' 		=> __( 'Add New' ),
            'add_new_item' 	=> __( 'Add New Lender' ),
            'new_item' 		=> __( 'New Lender' ),
            'view_item' 	=> __( 'View Lender' ),
            'search_items' 	=> __( 'Search Lenders' ),
            'all_items' 	=> __( 'All Lenders' ),
            'add_new_item' 	=> __( 'Add New Lender' ),
            'not_found' 	=> __( 'No Openings Yet' )
        ),
        'description' 	=> 'Add lender\'s logo.',
        'public' 		=> true,
        'has_archive' 	=> true,
        'supports'      => array( 'title', 'thumbnail'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
    ) );
    register_post_type( 'product', array(
        'labels' => array(
            'name' => __( 'Products' ),
            'singular_name' => __( 'Product' ),
            'add_new' => __( 'Add New' ),
            'add_new_item' => __( 'Add New Product' ),
            'new_item' => __( 'New Product' ),
            'view_item' => __( 'View Product' ),
            'search_items' => __( 'Search Products' ),
            'all_items' => __( 'All Products' ),
            'add_new_item' => __( 'Add New Product' ),
            'not_found' => __( 'No Products Yet' )
        ),
        'description' 	=> 'Add lender\'s logo.',
        'public' 		=> true,
        'has_archive' 	=> true,
        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
    ) );
    flush_rewrite_rules( false );
}
add_action( 'init', 'lct_custom_post_type' );