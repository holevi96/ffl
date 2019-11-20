<?php
/**
 * Created by IntelliJ IDEA.
 * User: Levi
 * Date: 5/31/2019
 * Time: 6:52 PM
 */

require_once('wp-blog-header.php');
require_once (realpath(dirname(__FILE__)) . '/wp-content/plugins/pay-via-barion-for-woocommerce/barion-library/library/BarionClient.php');
$myPosKey = "94d1a66fa6a5411384b94f7a2b9d9741"; // <-- Replace this with your POSKey!
$myEmailAddress = "info@karitativ.hu"; // <-- Replace this with your e-mail address in Barion!
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);


$blogusers = get_users();

// Array of WP_User objects.
foreach ( $blogusers as $user ) {
	
    $adomany = get_field('adomanyozni_kivant_osszeg','user_'.$user->ID);
    if($adomany != ''){
        $item = new ItemModel();
        $price = $adomany;
        $item->Name = "Ételt az Életért - rendszeres adomány"; // no more than 250 characters
        $item->Description = "Időszakos adományozási lehetőség!"; // no more than 500 characters
        $item->Quantity = 1;
        $item->Unit = "piece"; // no more than 50 characters
        $item->UnitPrice = $price;
        $item->ItemTotal = $price;
        $item->SKU = "ITEM-R-".$user->ID; // no more than 100 characters
        global $wpdb;
        $results = $wpdb->get_results( "SELECT COALESCE(MAX(ID),0) ID from recurrent_barion_payments", OBJECT );
        $ID = $results[0]->ID + 1;

        $results = $wpdb->get_results( " SELECT * from recurrent_barion_payments where user_id = ".$user->ID." order by date desc", OBJECT );
        if (count($results) > 0) {
            $paymentDetails = $BC->GetPaymentState($results[0]->paymentID);

            if($paymentDetails->Status == "Canceled" || $paymentDetails->Status == "Failed" ||$paymentDetails->Status == "Prepared"){
                $wpdb->query("DELETE FROM recurrent_barion_payments WHERE user_id=".$user->ID);
            }else{
			
                $idoszak = get_field('milyen_idoszakonkent','user_'.$user->ID)['value'];

                $kulonbseg = time()-strtotime($results[0]->date);
               // echo $kulonbseg .'/n';
                if($kulonbseg > $idoszak*60*60*24*7){ //hetente
                    // create the transaction
                    $trans = new PaymentTransactionModel();
                    $trans->POSTransactionId = "TRANS-" . $ID;
                    $trans->Payee = $myEmailAddress; // no more than 256 characters
                    $trans->Total = $price;
                    $trans->Comment = "Recurrent Transaction ID " . $ID; // no more than 640 characters
                    $trans->AddItem($item); // add the item to the transaction

                    // create the request model
                    $psr = new PreparePaymentRequestModel();
                    $psr->GuestCheckout = true; // we allow guest checkout
                    $psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
                    $psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
                    $psr->PaymentRequestId = "PAY-" . $ID; // no more than 100 characters
                    $psr->PayerHint = get_userdata($user->ID)->user_email; // no more than 256 characters
                    $psr->Locale = UILocale::EN; // the UI language will be English
                    $psr->InitiateRecurrence = False;
                    $psr->RecurrenceId = "recurrence_" . $user->ID;
                    $psr->Currency = Currency::HUF;
                    $psr->OrderNumber = "ORDER-" . $ID; // no more than 100 characters
                    $psr->AddTransaction($trans); // add the transaction to the payment
                    $psr->RedirectUrl = get_site_url() . "/koszonjuk-a-tamogatasat";
                    // send the request
                    $myPayment = $BC->PreparePayment($psr);
					
					$wpdb->insert('recurrent_barion_payments', array(
						'user_id' => $user->ID,
						'date' => current_time('mysql', 1),
						'value'=>$price,// ... and so on
						'paymentID' => $myPayment->PaymentId
					));
					
                }else{
					echo "User: " .$user->user_login. " - még nem fizet.<br/>";
				}


            }
        }

    }
}