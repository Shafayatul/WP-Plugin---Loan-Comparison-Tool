<?php

/**
* Admin Menu
*/
add_action('admin_menu', 'loan_menu');
 
function loan_menu(){
        add_menu_page( 'LOAN TOOL', 'LOAN TOOL', 'manage_options', 'setting-lct', 'setting_lct' );
/*		add_submenu_page("club-member-main-cmr", "Pending Member", "Pending Member", 0, "pending-member", "pendingMemberFunction");
		add_submenu_page("club-member-main-cmr", "Expire Member", "Expire Member", 0, "expire-member", "ExpiredMemberFunction");
		add_submenu_page("club-member-main-cmr", "All Member", "All Member", 0, "all-member", "AllMemberFunction");
		
		// page for php use
		add_submenu_page("", "Detail Member", "Detail Member", 0, "cmr-detail-member", "cmr_detail_member_func");
		add_submenu_page("", "Delete Member", "Delete Member", 0, "cmr-delete-member", "cmr_delete_member_func");
		add_submenu_page("", "Active Member", "Active Member", 0, "cmr-active-member", "cmr_active_member_func");
		add_submenu_page("", "Edit Member", "Edit Member", 0, "cmr-edit-member", "cmr_edit_member_func");
		add_submenu_page("", "Member Date Reset", "Member Date Reset", 0, "cmr-date-reset", "cmr_date_reset_func");*/
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
	    while($data = fgetcsv($handle))//handling csv file 
	    {
	     echo $data[0];
	     echo '<hr>';
	    // echo $item1 = mysqli_real_escape_string($connect, $data[0]);  
	                //$item2 = mysqli_real_escape_string($connect, $data[1]);
	    		//insert data from CSV file 
	                //$query = "INSERT into ts_excel(ts_name, ts_email) values('$item1','$item2')";
	                //mysqli_query($connect, $query);
	    }
	    fclose($handle);
		
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