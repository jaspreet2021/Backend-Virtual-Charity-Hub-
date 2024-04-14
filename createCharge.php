<?php
require_once('vendor/autoload.php');

  header("Access-Control-Allow-Origin: http://localhost:4200");
$token="";
if(isset($_POST['token'])) {
    // Use prepared statement to prevent SQL injection
    $token = $_POST['token'];
}
$amount=0;
if(isset($_POST['amount'])) {
    // Use prepared statement to prevent SQL injection
    $am = $_POST['amount'];
    $amount=(int)$am*100;

}



$stripe = new \Stripe\StripeClient('sk_test_51P1fxcP0bZKcBUr0aovb6J0qX4TpmJiCariZdarZqkcLcaOMijJIvSey4qeQo5o7ZQpHiLEwpfcVw6TpBlfkbhm90001h0F7jc');
$stripe->charges->create([
  'amount' => $amount,
  'currency' => 'cad',
  'source' => $token,


]);
?>