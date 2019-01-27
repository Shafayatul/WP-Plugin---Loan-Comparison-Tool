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
}



/*register_activation_hook( __FILE__, 'pu_create_plugin_tables' );
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