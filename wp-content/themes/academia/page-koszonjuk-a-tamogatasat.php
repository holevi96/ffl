
<?php
/*
*  Barion PHP library usage example
*
*  Getting detailed information about a payment
*
*  � 2015 Barion Payment Inc.
*/
require_once (realpath(dirname(__FILE__)) . '/../../plugins/pay-via-barion-for-woocommerce/barion-library/library/BarionClient.php');
$myPosKey = "c069148b701f4c598843246c0085d75f"; // <-- Replace this with your POSKey!
$paymentId = $_GET['paymentId']; // <-- Replace this with the ID of the payment!
// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);
// send the request
$paymentDetails = $BC->GetPaymentState($paymentId);

?>
<?php
ob_start();
get_header();
$header = ob_get_clean();



?>

<?php
if($paymentDetails->Status == "Canceled" || $paymentDetails->Status == "Failed"){
    $header = preg_replace('#<title>(.*?)<\/title>#', '<title>Sikertelen fizetés!</title>', $header);
    echo $header;
    g5plus_get_template('page-unsuccesful');
}else{
    echo $header;

    g5plus_get_template('page');
}


?>
<?php get_footer(); ?>