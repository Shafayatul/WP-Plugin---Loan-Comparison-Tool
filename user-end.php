<?php
function foobar_func( $atts ){

	$html = "";

	$html .= '  
			<div class="form-group">
			    <label for="exampleInputEmail1">Loan amount</label>
			    <input type="text" class="form-control" name="amount" aria-describedby="emailHelp" placeholder="Enter amount">
			</div>

			<div class="form-group">
				<label for="exampleFormControlSelect2">Purpose</label>
				<select class="form-control" name="puspose" id="purpose">
					<option value="OwnerOccupied">Owner Occupied</option>
					<option value="Investment">Investment</option>
				</select>
			</div>

			<div class="form-group">
				<label for="state">State</label>
				<select class="form-control" name="state" id="state">
					<option value="ACT">Australian Capital Territory</option>
					<option value="NSW">New South Wales</option>
					<option value="NT ">Northern Territory</option>
					<option value="QLD">Queensland</option>
					<option value="SA ">South Australia</option>
					<option value="TAS">Tasmania</option>
					<option value="VIC">Victoria</option>
					<option value="WA ">Western Australia</option>
				</select>
			</div>
			<br>
			<br>
			<button type="button" id="lct-search">Search<button>
			<p class="demo-ouput"></p>
			';
	return $html;
}
add_shortcode( 'foobar', 'foobar_func' );

/**
* AJAX
*/
add_action( 'wp_ajax_lct_data_search', 'lct_data_search' );          //TO LOGGED USER
add_action( 'wp_ajax_nopriv_lct_data_search', 'lct_data_search' );   //TO UNLOGGED USER
function lct_data_search(){
    ob_clean();           //TO CLEAN EXTRA SPACE

    $ID = $_POST["ID"];
    // $security = $_POST["security"];
    if(check_ajax_referer( 'ajax_csrf_check', 'security' ) ){
        $user_id = get_current_user_id(); 
        global $wpdb;
        $table_name = $wpdb->prefix . 'lct_csv_datsdsa';

        // variables
        $amount = $_POST['amount'];
		$state = $_POST['state'];
		$purpose = $_POST['purpose'];



		$query_str = "FROM $table_name WHERE $state='Y' AND $purpose='Y' AND MinLoanAmount <= '$amount' And MaxLoanAmount >= '$amount' ";


        // check this address is his or not
        $rowcount = $wpdb->get_var("SELECT COUNT(*) ".$query_str);

        if($rowcount >= 1){

            // make other address NOT primary
            $results = $wpdb->get_results("SELECT * ".$query_str, OBJECT);      
            $output = "<table>";
           	$output .="<tr>"; 
           	$output .="<td>PRODUCT ID</td>"; 
           	$output .="<td>NAME</td>"; 
           	$output .="<td>LENDER</td>"; 
           	$output .="</tr>"; 
            foreach ($results as $result) {
               	$output .="<tr>"; 
               	$output .="<td>".$result->productID."</td>"; 
               	$output .="<td>".$result->Name."</td>"; 
               	$output .="<td>".$result->Lender."</td>"; 
               	$output .="</tr>"; 
            }   
            $output .= "</table>";

            echo $output;
        }
    }else{
        echo 'csrfAttack';
    }      

    wp_die(); // this is required to terminate immediately and return a proper response
}