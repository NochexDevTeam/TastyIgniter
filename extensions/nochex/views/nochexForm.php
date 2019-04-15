<style>#nxBackground{background: #fff;height: 100%;width: 100%;position: fixed;z-index: 100000000;}#nxForm{position: fixed;z-index: 100000001;width: 100%;height: 100%;text-align: center;vertical-align: middle;line-height: 100%;top:20%}
.loader {
	border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div id="nxBackground"></div>


<div id="nxForm">
<h4>Loading ... Please Wait ...</h4>
<div class="loader"></div>

<h4>If you are not transferred shortly,<br/>click the button below;</h4>
	<form id="nochexform" name="nochexform" method="POST" action="https://secure.nochex.com/default.aspx">
	<input type="hidden" value="<?php echo $merchant_id; ?>" name="merchant_id" />
	<input type="hidden" value="<?php echo $test_transaction; ?>" name="test_transaction" />
	<input type="hidden" value="<?php echo $xml_item_collection; ?>" name="xml_item_collection" />
	<input type="hidden" value="<?php echo $amount; ?>" name="amount" />
	<input type="hidden" value="<?php echo $billing_fullname; ?>" name="billing_fullname"/>
	<input type="hidden" value="<?php echo $billing_address; ?>" name="billing_address" />
	<input type="hidden" value="<?php echo $billing_city; ?>" name="billing_city" />
	<input type="hidden" value="<?php echo $billing_postcode; ?>" name="billing_postcode" />
	<?php if($order_type == "1"){ ?>
	
	
	<input type="hidden" value="<?php echo $postage; ?>" name="postage"/>
	<input type="hidden" value="<?php echo $delivery_fullname; ?>" name="delivery_fullname"/>
	<input type="hidden" value="<?php echo $delivery_address; ?>" name="delivery_address" />
	<input type="hidden" value="<?php echo $delivery_city; ?>" name="delivery_city" />
	<input type="hidden" value="<?php echo $delivery_postcode; ?>" name="delivery_postcode" />
	<?php } ?>
	<input type="hidden" value="<?php echo $customer_telephone_number; ?>" name="customer_phone_number" />
	<input type="hidden" value="<?php echo $email_address; ?>" name="email_address" />
	<input type="hidden" value="<?php echo $order_id; ?>" name="order_id" /> 
	<input type="hidden" value="<?php echo $hide_billing_details; ?>" name="hide_billing_details" /> 
	<input type="hidden" value="<?php echo $callback_url; ?>" name="callback_url" />
	<input type="hidden" value="<?php echo $cancel_url; ?>" name="cancel_url" />
	<input type="hidden" value="<?php echo $success_url; ?>" name="success_url" />
	<input type="hidden" value="<?php echo $success_url; ?>" name="test_success_url" />
	<input type="hidden" value="<?php echo $optional_1; ?>" name="optional_1" />
	<input type="hidden" value="<?php echo "Order Type: ".$order_time_type.", Expected Date and Time:". $order_time; ?>" name="description" />
	<input class="paybtn" style="padding: 10px;" type="image" src="https://ssl.nochex.com/images/buttons/nochex_pay.png" alt="Pay using Nochex" />
	</form>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">			
	$(document).ready(function(){
     $("#nochexform").submit();
	});			
	</script>
	
 
