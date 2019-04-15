<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Nochex extends Main_Controller {
 
 public function __construct() {
        parent::__construct();
        $this->load->model('Orders_model');
		}
		
		
  public function index() {
        //check if file exists in views folder
		if ( ! file_exists(EXTPATH .'nochex/views/nochex.php')) { 
            show_404(); 																		
			/* Whoops, show 404 error page!*/
        }

        $payment = $this->extension->getPayment('nochex');
        $order_data = $this->session->userdata('order_data');   
		
       // START of retrieving lines from language file to pass to view.
        $data['code'] = $payment['name']; /* nochex */
        $data['title'] = !empty($payment['ext_data']['title']) ? $payment['ext_data']['title'] : $payment['title']; /* Nochex */
        // END of retrieving lines from language file to send to view.
		
        // retrieve order details from session userdata
        $data['payment'] = !empty($order_data['payment']) ? $order_data['payment'] : '';
        $data['minimum_order_total'] = is_numeric($payment['ext_data']['order_total']) ? $payment['ext_data']['order_total'] : 0;
        $data['order_total'] = $this->cart->total();
		 
        // pass array $data and load view files
        return $this->load->view('nochex/nochex', $data);
    }


	/*--- Function, write to a text file ---*/	
	public function writeDebug($DebugData){
		// Receives and stores the Date and Time
		$debug_TimeDate = date("m/d/Y h:i:s a", time());
		// Puts together, Date and Time, as well as information in regards to information that has been received.
		$stringData = "\n Time and Date: " . $debug_TimeDate . "... " . $DebugData ."... ";
		 // Try - Catch in case any errors occur when writing to nochex_debug file.
			try
			{
			// Variable with the name of the debug file.
				$debugging = EXTPATH."nochex/nochex_debug.txt";
			// variable which will open the nochex_debug file, or if it cannot open then an error message will be made.
				$f = fopen($debugging, 'a') or die("File can't open");
			// Open and write data to the nochex_debug file.
			$ret = fwrite($f, $stringData);
			// Incase there is no data being shown or written then an error will be produced.
			if ($ret === false)
				die("Fwrite failed");			
				// Closes the open file.
				fclose($f)or die("File not close");
			} 
			//If a problem or something doesn't work, then the catch will produce an email which will send an error message.
			catch(Exception $e)
			{
			mail($this->email, "Debug Check Error Message", $e->getMessage());
			}
	}


   public function confirm() {
        $this->lang->load('nochex/nochex');

        $order_data = $this->session->userdata('order_data'); 						// retrieve order details from session userdata
        $cart_contents = $this->session->userdata('cart_contents'); 												// retrieve cart contents
		 
        if (empty($order_data) AND empty($cart_contents)) {
            return FALSE;
        }
		
		/* Checks if the order data (Payment data) is not empty */
        if (!empty($order_data['ext_payment']) AND !empty($order_data['payment']) AND $order_data['payment'] == 'nochex') { 											// else if payment method is cash on delivery

			/* If not empty - collect all order data and send it to the Nochex Payment Form */
            $ext_payment_data = !empty($order_data['ext_payment']['ext_data']) ? $order_data['ext_payment']['ext_data'] : array();

            if (!empty($ext_payment_data['order_total']) AND $cart_contents['order_total'] < $ext_payment_data['order_total']) {
                $this->alert->set('danger', $this->lang->line('alert_min_total'));
                return FALSE;
            }

            if (isset($ext_payment_data['order_status']) AND is_numeric($ext_payment_data['order_status'])) {
                $order_data['status_id'] = $ext_payment_data['order_status'];
            }

            $this->load->model('Orders_model');
			
			//check if Nochex Payment Form exists in views folder
			if ( ! file_exists(EXTPATH .'nochex/views/nochexForm.php')) { 								
				show_404(); 																		
				// Whoops, show 404 error page!
			}
			 
			$this->load->model('Addresses_model');
			$address = $this->Addresses_model->getAddress($order_data['customer_id'], $order_data['address_id']);
			
			/* Debug Info - Address */
			$debugInfo = " - Get Customer Address: " . $address . "\n";
			
			/* Build the Item Collection */
			$itemCollection = "<items>";
			foreach (array_keys($this->cart->contents()) as $key => $rowid) {							// loop through cart items to create items name-value pairs data to be sent to paypal
				foreach ($this->cart->contents() as $cart_item) {   
					$menu_data = $this->Cart_model->getMenu($cart_item["id"]);  
					$itemCollection .= "<item><id>".$cart_item["id"]."</id><name>".$cart_item["name"]."</name><description>".$menu_data["menu_description"]."</description><quantity>".$cart_item["qty"]."</quantity><price>".number_format($cart_item["price"],2)."</price></item>";
				}			
			} 
			
			$itemCollection .= "</items>";
			/* Debug Info - XML / Product Info*/
			$debugInfo .= " - Get Ordered Item Collection: " . $itemCollection;
			
			/* Check if test mode enabled*/
			if ($order_data["ext_payment"]["ext_data"]["api_mode"] == "sandbox"){
				$testTransaction = "100";			
			}else{
				$testTransaction = "";
			}
			
			/* Debug Info - Test Mode*/
			$debugInfo .= " - Is Test Mode Enabled? " . $order_data["ext_payment"]["ext_data"]["api_mode"] . " - Check Test Mode: " . $testTransaction . "\n";
			 
			 /* Check Order Type */
			if($order_data["order_type"] == "1"){ 
				$ordType = "Delivery"; 
			}else{ 
				$ordType = "Collection"; 
			}
			  
			  /* Check Hide Billing Details is enabled */
			  if($order_data["ext_payment"]["ext_data"]["hide"] == "1"){
				$hideBilling = "true";
			  }else{
				$hideBilling = "false";
			  }
			  
			/* Debug Info - Hide Billing*/
			$debugInfo .= " - Hide Billing Details: " . $order_data["ext_payment"]["ext_data"]["hide"] .  " - Hide Billing Value: " . $hideBilling . "\n";
			
			/* If Order Type = 1 / Delivery, check to see if separate postage is enabled before updating the amounts */
			if($order_data["order_type"] == "1"){
			if($order_data["ext_payment"]["ext_data"]["sepPostage"] == "1"){
				$delCost = $cart_contents["totals"]["delivery"]["amount"];
				$totalCost = $cart_contents["order_total"];	
				$calc = $totalCost - $delCost;		
				$fmData['postage'] = number_format($delCost, 2);
				$fmData['amount'] = number_format($calc, 2);
			}else{
				$fmData['postage'] = number_format(0, 2);
				$fmData['amount'] = number_format($cart_contents["order_total"], 2); 			
			} 
			 
			}else{
				$fmData['amount'] = number_format($cart_contents["order_total"], 2); 			
			}
			
			/* Debug Info - Order Type*/
			$debugInfo .= " - Order Type: ((" . $order_data["order_type"] . " - " . $ordType . " ) 1 = Delivery, 2 = Collection) - Check Separate Postage: ". $order_data["ext_payment"]["ext_data"]["sepPostage"] . " - Check Amount Values:" . $fmData['postage'] . " - " . $fmData['amount'] . ", Postage and Amount only separate on the following conditions: Customer has selected delivery, and Separate Postage amount has been enabled!\n";
			
			/* URL path for Nochex actions */
			$callback_uri = 'nochex/authorize';
			$cancel_uri = 'nochex/cancel';	
			$success_uri = 'checkout/success';		
			 
	
			 
			$fmData['billing_fullname'] = $order_data["first_name"] . ", " . $order_data["last_name"];
			$fmData['billing_address'] = $address['address_1'];
			$fmData['billing_city'] = $address['city'];
			$fmData['billing_postcode'] = $address['postcode'];
			$fmData['email_address'] = $order_data["email"];
			$fmData['customer_telephone_number'] = $order_data["telephone"]; 
			$fmData['order_id'] = $order_data["order_id"]; 
			$fmData['order_asap_time'] = $order_data["order_asap_time"]; 
			$fmData['order_time_type'] = $ordType . " (" . $order_data["order_time_type"] . ")";  
			$fmData['order_time'] = $order_data["order_time"]; 
			$fmData['order_type'] = $order_data["order_type"]; 
			$fmData['xml_item_collection'] = $itemCollection; 
			$fmData['merchant_id'] = $order_data["ext_payment"]["ext_data"]["merchantID"]; 
			$fmData['test_transaction'] = $testTransaction; 
			
			$fmData['callback_url'] = site_url($callback_uri); 
			$fmData['cancel_url'] = site_url($cancel_uri); 
			$fmData['success_url'] = site_url($success_uri); 
			$fmData['optional_1'] = $order_data["ext_payment"]["ext_data"]["paid_order_status"]; 
			$fmData['delivery_fullname'] = $order_data["first_name"] . ", " . $order_data["last_name"];
			$fmData['delivery_address'] = $address['address_1'];
			$fmData['delivery_city'] = $address['city'];
			$fmData['delivery_postcode'] = $address['postcode'];
			$fmData['hide_billing_details'] = $hideBilling;
			
	/* Debug Info - Payment Form Info */	
	$debugInfo .= " - Data to be sent to the Payment Form;  \n";
	
	foreach ($fmData as $key => $value){	
		$debugInfo .= "+ ". $key ."=>". $value  . "\n";
	}
	
		/* If debugger enabled, write to text file in the following location ../extensions/nochex/nochex_debug.txt */
		if($order_data["ext_payment"]["ext_data"]["ncxdebug"] == "1"){
		
		$this->writeDebug($debugInfo);
		
		}
			 
			/* Load Payment Form */
			return $this->load->view('nochex/nochexForm', $fmData);
			
        }
    }
	
	public function authorize() {
			/* Nochex APC */
			$order_data = $this->session->userdata('order_data'); 
			$cart_contents = $this->session->userdata('cart_contents');
	 
			/* Debug Info - APC Started */
			$this->writeDebug("Nochex APC");
			
			/* Check if post values are received */
			if($_POST){	
			
			// Get the POST information from Nochex server
			$postvars = http_build_query($_POST);
			 
			/* Debug Info - Before Curl */
			$this->writeDebug("Before APC Call");
			 
			$url = "https://www.nochex.com/apcnet/apc.aspx";
			$ch = curl_init ();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postvars);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$output = curl_exec ($ch);
			curl_close ($ch);
			
			/* Debug Info - After Curl / Response Received */
			$this->writeDebug("APC Response: " . $output . " ");
			  
			// Put the variables in a printable format for the email
			$debug = "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n"; 
			foreach($_POST as $Index => $Value) 
			$debug .= "".$Index ."->". $Value."\r\n"; 
			$debug .= "\r\nRESPONSE:\r\n$output";
			 
			 /* Debug Info - APC values */
			 $debugVals = " APC Values: ";
			 foreach($_POST as $Index => $Value) {
				 $debugVals .= "+ " . $Index ."->". $Value;
			 }
			$this->writeDebug($debugVals);
			 

			//If statement - checks the response to see if AUTHORISED is present if it isn’t a failure message is displayed
			if ($output == "AUTHORISED") {
				$msg = "APC was AUTHORISED. "; // if AUTHORISED was found in the response then it was successful
			}else {   	
				$msg = "APC was not AUTHORISED. \r\n $debug";  // displays debug message (If APC is not authorised)
			}
			
			$msg .= "This was a " . $_POST['status'] . " transaction, (Transaction ID = " . $_POST['transaction_id'] . ")";
			
					/* adds a order history status / comment containing fields received from Nochex */
					$this->load->model('Statuses_model');

					$order_history = array(
						'object_id'  => $_POST['order_id'],
						'status_id'  => $_POST['custom'],
						'notify'     => '0',
						'comment'    => $msg,
						'date_added' => mdate('%Y-%m-%d %H:%i:%s', time()),
					);
					
			/* Debug Info - APC Message */
			$this->writeDebug( "- APC Message " . $msg);
			 
			$this->Statuses_model->addStatusHistory('order', $order_history);
			
			/* Gets and sets data to complete the order */
			$order_status = array(
							'order_status'  => $_POST['custom'],
							'notify'  => '0',
							'status_comment'  => $msg, 
							);
						
			$this->load->model('Orders_model');		
			$order_data['status_id'] = $_POST['custom'];
			$order_data['comment'] .= $_POST['comment'];
			$this->Orders_model->completeOrder($_POST['order_id'], $order_data, $cart_contents);
	
	}else{	
		show_404();
	}
	}
	
	
     public function cancel() {
		/* Cancelled Order - Clear all cache */
		$this->alert->set('alert', "Your order has been cancelled!");
		
		$this->session->unset_userdata('cart_contents');
		$this->session->unset_userdata('order_data');
		
        redirect('checkout');
    }
}

/* End of file nochex.php */
/* Location: ./extensions/nochex/controllers/nochex.php */
