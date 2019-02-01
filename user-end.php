<?php
function lct_show_form( $atts ){
    ob_start();
?>

	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/lct-custom.css';?>">

	<div class="bootstrap-iso">
		<div class="container-fluid">
					<div class="lct-vertical-from">
						<div class="row">
							<div class="col-md-12">
								<h3 class="lct-form-title" style="color: white; font-weight: bolder;">Compare Home Load</h3>
							</div>
							
							<form method="get" action="<?php echo site_url('/compare-rate/');?>">
								<div class="form-group col-md-3">
									<div class="row">
										<div class="col-md-12">
											<label class="lct-label" for="exampleInputEmail1">Loan amount</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<input type="text" class="form-control" name="amount" aria-describedby="emailHelp" placeholder="Enter amount">
										</div>
									</div>
								</div>

								<div class="form-group col-md-3">
									<div class="row">
										<div class="col-md-12">
											<label class="lct-label" for="exampleFormControlSelect2">Purpose</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<select class="form-control" name="purpose" id="purpose">
												<option value="OwnerOccupied">Owner Occupied</option>
												<option value="Investment">Investment</option>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group col-md-3">
									<div class="row">
										<div class="col-md-12">
											<label class="lct-label" for="state">State</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
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
									</div>
								</div>

								<div class="form-group col-md-3">
									<div class="row">
										<div class="col-md-12"> 	&nbsp;	&nbsp;	&nbsp;</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<input type="submit" class="btn btn-success btn-block" value="Search">
										</div>
									</div>
								</div>
							</form>
						</div>					
					</div>			
		</div>

	</div>

	<p class="demo-ouput"></p>
<?php
	return ob_get_clean();
}
add_shortcode( 'lct-show-form', 'lct_show_form' );



function lct_show_search_result( $atts ){
    ob_start();
?>

	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/lct-custom.css';?>">

			<?php

				
				/*get images*/
				$lender_image_array = [];
				query_posts(array( 
				'post_type' => 'lender',
				'showposts' => -1
				) );  

				while (have_posts()) : the_post();
					$lender_image_array[get_the_title()] = get_the_post_thumbnail_url(get_the_ID(),'full');
				endwhile;
				
				/*get images*/
				$product_array = [];
				query_posts(array( 
				'post_type' => 'product',
				'showposts' => -1
				) );  

				while (have_posts()) : the_post();
					$product_array[get_the_title()] = get_the_permalink();
				endwhile;

		        global $wpdb;
		        $table_name = $wpdb->prefix . 'lct_csv_datsdsa';

		        // variables
		        $amount = str_replace(',','',$_GET['amount']);
				$state = $_GET['state'];
				$purpose = $_GET['purpose'];

                $limit = get_option('lct_number_of_result_to_show');

				$query_str = "FROM $table_name WHERE $state='Y' AND $purpose='Y' AND MinLoanAmount <= '$amount' And MaxLoanAmount >= '$amount' ORDER By ComparisonRate ASC";


		        // check this address is his or not
		        $rowcount = $wpdb->get_var("SELECT COUNT(*) ".$query_str);

		            
		            if(is_numeric($limit)){
		                $limit = " LIMIT ".$limit;
		            }else{
		                $limit = "";
		            }
?>

<div class="bootstrap-iso">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Reverse Mortgage</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="ReverseMortgage">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="ReverseMortgage">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">NoDoc</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="NoDoc">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="NoDoc">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">LowDoc</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="LowDoc">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="LowDoc">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">Equity</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="Equity">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="Equity">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">CreditImpaired</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="CreditImpaired">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="CreditImpaired">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">FixedRate</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="Y" name="FixedRate">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input type="radio" value="N" name="FixedRate">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">FixedPeriod</div>
					<div class="panel-body">
							<label class="radio-inline"><input type="radio" value="1" name="FixedPeriod">1 Years</label>
							<br>
							<label class="radio-inline"><input type="radio" value="2" name="FixedPeriod">2 Years</label>
							<br>
							<label class="radio-inline"><input type="radio" value="3" name="FixedPeriod">3 Years</label>
							<br>
							<label class="radio-inline"><input type="radio" value="4" name="FixedPeriod">4 Years</label>
							<br>
							<label class="radio-inline"><input type="radio" value="5" name="FixedPeriod">5 Years</label>
					</div>
				</div>
				<br>

			</div>
			<div class="col-md-9">
       			<div class="row row-heading">
					<div class="col-md-3">Lender</div>
					<div class="col-md-2">variable rate</div>
					<div class="col-md-2">Fixed rate</div>
					<div class="col-md-2">Comparison rate</div>
					<div class="col-md-3"><button class="btn btn-warning btn-sm" id="compare-selected">Compare</button></div>
				</div>
			  	<?php
		            $results = $wpdb->get_results("SELECT * ".$query_str.$limit, OBJECT);      
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


		        ?>
           				<div class="row">
           					<div class="col-md-12 product-link">
           						<?php echo $current_Name;?>
           					</div>
       					</div>
               			<div class="row row-margin">
							<div class="col-md-3"><?php echo $current_lender;?></div>
							<div class="col-md-2"><?php echo $result->VariableRate;?></div>
							<div class="col-md-2"><?php echo $result->FixedRate;?></div>
							<div class="col-md-2"><?php echo $result->ComparisonRate;?></div>
							<div class="col-md-3"><input type="checkbox" id="<?php echo $result->id;?>" class="checked-lender"></div>
						</div>
            	<?php } ?>		
			</div>
		</div>			
	</div>
</div>
<?php
	return ob_get_clean();
}
add_shortcode( 'lct-show-search-result', 'lct_show_search_result' );



function lct_compare_rate( $atts ){
    ob_start();
?>

	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/lct-custom.css';?>">

	<div class="bootstrap-iso">
		<div class="container">
			<div class="row">
			<?php



				
				/*get images*/
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
				$ids = rtrim($_GET['ids'], ',');

                $limit = get_option('lct_number_of_result_to_show');

				$query_str = "FROM $table_name WHERE id in ($ids) ORDER By ComparisonRate ASC";


	            // make other address NOT primary
	            $results = $wpdb->get_results("SELECT * ".$query_str, OBJECT);      
	            $output = '	<div class="bootstrap-iso">
								<div class="container">
									<div class="row">
										<div class="col-md-3">Lender</div>
										<div class="col-md-2">variable rate</div>
										<div class="col-md-2">Fixed rate</div>
										<div class="col-md-2">Comparison rate</div>
									</div>';

	            foreach ($results as $result) {

	               	$output .='<br><br>
	               				<div class="row">
	               					<div class="col-md-12"><h3>'.$result->Name.'<h3></div>
               					</div>';
	               					if (array_key_exists($result->Lender, $lender_image_array)) {
	               						$current_lender = '<img class="img-responsive" src="'.$lender_image_array[$result->Lender].'">';
	               					}else{
	               						$current_lender = $result->Lender;
	               					}
	               	$output .='<div class="row">
									<div class="col-md-3">'.$current_lender.'</div>
									<div class="col-md-2">'.$result->VariableRate.'</div>
									<div class="col-md-2">'.$result->FixedRate.'</div>
									<div class="col-md-2">'.$result->ComparisonRate.'</div>
								</div>
								<hr>';
	            }   
	            echo $output;
	            
            ?>
			</div>			
		</div>

	</div>

<?php
	return ob_get_clean();
}
add_shortcode( 'lct-compare-rate', 'lct_compare_rate' );