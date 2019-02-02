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
			<div class="col-md-8"></div>
			<div class="col-md-4">
				<div class="row">
						<button  type="button" class="button" id="compare-selected">Compare</button>
				</div>
			</div>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Reverse Mortgage</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="ReverseMortgage">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="ReverseMortgage">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">NoDoc</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="NoDoc">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="NoDoc">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">LowDoc</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="LowDoc">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="LowDoc">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">Equity</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="Equity">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="Equity">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">CreditImpaired</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="CreditImpaired">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="CreditImpaired">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">FixedRate</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="Y" name="FixedRate">Yes</label>
								&nbsp;	&nbsp;
							<label class="radio-inline"><input class="lct-filter" type="radio" value="N" name="FixedRate">No</label>
					</div>
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">FixedPeriod</div>
					<div class="panel-body">
							<label class="radio-inline"><input class="lct-filter" type="radio" value="1" name="FixedPeriod">1 Years</label>
							<br>
							<label class="radio-inline"><input class="lct-filter" type="radio" value="2" name="FixedPeriod">2 Years</label>
							<br>
							<label class="radio-inline"><input class="lct-filter" type="radio" value="3" name="FixedPeriod">3 Years</label>
							<br>
							<label class="radio-inline"><input class="lct-filter" type="radio" value="4" name="FixedPeriod">4 Years</label>
							<br>
							<label class="radio-inline"><input class="lct-filter" type="radio" value="5" name="FixedPeriod">5 Years</label>
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
					<div class="col-md-3">Select to compare</div>
				</div>
				<div class="search-result">
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
</div>
<input type="hidden" id="hidden-amount" value="<?php echo $amount;?>">
<input type="hidden" id="hidden-state" value="<?php echo $state;?>">
<input type="hidden" id="hidden-purpose" value="<?php echo $purpose;?>">
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
		<div class="container-fluid">
			<div class="row">
			<?php
				

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

            ?>
			<div class="facet-compare gold-theme">
			    <div class="comparison-header margin-top-50">
			        <div id="toolbar">
			            <div class="container-fluid">
			                <div class="row">
			                    <div class="alert alert-block alert-danger hidden" id="invalid">
			                        <span>
			                        </span>
			                    </div>
			                    <div class="functions col-xs-12">
			                        <a href="#myModal" role="button" class="button" data-toggle="modal">Email Shortlist</a>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>


	 
				<!-- Modal HTML -->
				<div id="myModal" class="modal fade">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header">
				                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				                <h4 class="modal-title">Email Your Shortlist</h4>
				            </div>

							<div class="row">
							    <div class="col-xs-12">
									<div class="alert alert-success" style="display: none;">
									  <strong>Success!</strong> Email successfully send.
									</div>
									<div class="alert alert-danger" style="display: none;">
									  <strong>Error!</strong> Please try again.
									</div>
							    </div>
							</div>

				            <div class="modal-body">
				            	<div class="form-group">
				            		<label for="email">Email address:</label>
				            		<input type="email" class="form-control" id="lct-email">
				            	</div>
				            	<div class="form-group">
				            		<label for="pwd">Nmae:</label>
				            		<input type="text" class="form-control" id="lct-name">
				            	</div>
				            </div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				                <button type="button" class="btn btn-primary lct-send-email">Send email</button>
				            </div>
				        </div>
				    </div>
				</div>



			    <div class="comparison-table-container col-xs-12 mortgages-abvariant-1">
			        <div class="shortlist-heading row">
			            <div class="col-xs-3">
			                <h3>Home Loans</h3>
			                <span>Shortlist</span>
			            </div>
			        </div>
			        <div class="comparison-table two-products gold-theme">


			            <div class="row comparison-row ">
			                <div class="col-xs-<?php echo $col;?> comparison-item comparison-label company">
			                </div>
			                <div class="col-xs-<?php echo $col;?> comparison-item company">
			                	<?php	if (array_key_exists($results[0]['Lender'], $lender_image_array)) {
			       						echo $current_lender = '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[0]['Lender']].'" title="'.$lender_image_array[$results[0]['Lender']].'" alt="'.$lender_image_array[$results[0]['Lender']].'" value="'.$lender_image_array[$results[0]['Lender']].'">';
			       					}else{
			       						echo $current_lender = $results[0]['Lender'];
			       					}
		       					?>
			                </div>
			                <div class="col-xs-<?php echo $col;?> comparison-item company last">
			                	<?php	if (array_key_exists($results[1]['Lender'], $lender_image_array)) {
			       						echo $current_lender = '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[1]['Lender']].'" title="'.$lender_image_array[$results[1]['Lender']].'" alt="'.$lender_image_array[$results[1]['Lender']].'" value="'.$lender_image_array[$results[1]['Lender']].'">';
			       					}else{
			       						echo $current_lender = $results[1]['Lender'];
			       					}
		       					?>
			                </div>
			                <?php if($result_count==3){?>
							<div class="col-xs-<?php echo $col;?> comparison-item company last">
			                	<?php	if (array_key_exists($results[2]['Lender'], $lender_image_array)) {
			       						echo $current_lender = '<img style="width: 145px;max-width: 145px;" class="img-responsive" src="'.$lender_image_array[$results[2]['Lender']].'" title="'.$lender_image_array[$results[2]['Lender']].'" alt="'.$lender_image_array[$results[2]['Lender']].'" value="'.$lender_image_array[$results[2]['Lender']].'">';
			       					}else{
			       						echo $current_lender = $results[2]['Lender'];
			       					}
		       					?>
			                </div>
			            	<?php } ?>
			            </div>
			            <div class="row comparison-row ">
			                <div class="col-xs-<?php echo $col;?> comparison-item comparison-label loan-type">
			                </div>
			                <div class="col-xs-<?php echo $col;?> comparison-item loan-type">Variable</div>
			                <div class="col-xs-<?php echo $col;?> comparison-item loan-type last">Variable</div>
			                <?php if($result_count==3){?>
			                <div class="col-xs-<?php echo $col;?> comparison-item loan-type last">Variable</div>
			            	<?php } ?>
			            </div>

			            <?php $loop_count =1; ?>
			            <?php foreach ($showing_options_array as $showing_options_single) { ?>
				            <div class="row row-eq-height comparison-row <?php if($loop_count %2 ==1){ echo 'even';}else{ echo 'odd';}?> ">
				                <div class="col-xs-<?php echo $col;?> comparison-item comparison-label "><?php echo get_option($showing_options_single);?></div>
				                <div class="col-xs-<?php echo $col;?> comparison-item "><?php echo $results[0][$showing_options_single];?></div>
				                <div class="col-xs-<?php echo $col;?> comparison-item  last"><?php echo $results[1][$showing_options_single];?></div>
				                <?php if($result_count==3){?>
				                <div class="col-xs-<?php echo $col;?> comparison-item  last"><?php echo $results[2][$showing_options_single];?></div>
				            	<?php } ?>
				            </div>
				            <?php $loop_count++ ?>
			            <?php } ?>
			        </div>
			    </div>



			</div>			
		</div>

	</div>
	<input type="hidden" id="hidden-ids" value="<?php echo $_GET['ids'];?>">
<?php
	return ob_get_clean();
}
add_shortcode( 'lct-compare-rate', 'lct_compare_rate' );