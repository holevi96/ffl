<?php
/*
*  Barion PHP library usage example
*  
*  Starting an immediate payment with one product
*  
*  � 2015 Barion Payment Inc.
*/

require_once (realpath(dirname(__FILE__)) . '/../../plugins/pay-via-barion-for-woocommerce/barion-library/library/BarionClient.php');

$myPosKey = "94d1a66fa6a5411384b94f7a2b9d9741"; // <-- Replace this with your POSKey!
$myEmailAddress = "info@karitativ.hu"; // <-- Replace this with your e-mail address in Barion!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);
// SELECT COALESCE(MAX(ID),0) 'id' FROM `recurrent_barion_payments` WHERE user_id=1
// create the item model


/*$woocommerce_item = get_field('adomany_csomag','user_'.get_current_user_id())[0];
$wc = wc_get_product($woocommerce_item->ID);

$item = new ItemModel();
$item->Name = $wc->get_name(); // no more than 250 characters
$item->Description = $woocommerce_item->post_excerpt; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = $wc->get_price();
$item->ItemTotal = $wc->get_price();
$item->SKU = "ITEM-".$wc->get_slug(); // no more than 100 characters
*/

$item = new ItemModel();
$price = get_field('adomanyozni_kivant_osszeg','user_'.get_current_user_id());
$item->Name = "Ételt az Életért - rendszeres adomány"; // no more than 250 characters
$item->Description = "Időszakos adományozási lehetőség!"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = $price;
$item->ItemTotal = $price;
$item->SKU = "ITEM-R-".get_current_user_id(); // no more than 100 characters
global $wpdb;
$results = $wpdb->get_results( "SELECT COALESCE(MAX(ID),0) ID from recurrent_barion_payments", OBJECT );
$ID = $results[0]->ID + 1;

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-".$ID;
$trans->Payee = $myEmailAddress; // no more than 256 characters
$trans->Total =$price;
$trans->Comment = "Recurrent Transaction ID ".$ID; // no more than 640 characters
$trans->AddItem($item); // add the item to the transaction

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "PAY-".$ID; // no more than 100 characters
$psr->PayerHint = get_userdata(get_current_user_id())->user_email; // no more than 256 characters
$psr->Locale = UILocale::EN; // the UI language will be English
$psr->InitiateRecurrence = True;
$psr->RecurrenceId = "recurrence_".get_current_user_id();
$psr->Currency = Currency::HUF;
$psr->OrderNumber = "ORDER-".$ID; // no more than 100 characters
$psr->AddTransaction($trans); // add the transaction to the payment
$psr->RedirectUrl = get_site_url()."/koszonjuk-a-tamogatasat";
// send the request
$myPayment = $BC->PreparePayment($psr);


if ($myPayment->RequestSuccessful === true) {
    $wpdb->insert('recurrent_barion_payments', array(
        'user_id' => get_current_user_id(),
        'date' => current_time('mysql', 1),
        'value'=>$price,// ... and so on
        'paymentID' => $myPayment->PaymentId
    ));
  // redirect the user to the Barion Smart Gateway
  header("Location: " . BARION_WEB_URL_PROD . "?id=" . $myPayment->PaymentId);

}else{
	print_r($myPayment);
}
 ?>