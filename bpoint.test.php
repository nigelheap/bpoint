<?php 

require_once 'bpoint.php';

require_once 'config.php'

$account = array(
    'username' => BPOINT_USERNAME,
    'password' => BPOINT_PASSWORD,
    'merchantNumber' => BPOINT_MERCHANTNUMBER
);

$bpoint = new Bpoint($account);
$bpoint->connect();

/*
$trans = array(
    "Amount" => 10000,
    "CardNumber" => "5123456789012346", 
    "CRN1" => "ABE",
    "CRN2" => "",
    "CRN3" => "",
    "CVC" => "123",
    "ExpiryDate" => "9951",
    "PaymentType" => "PAYMENT",
    "TxnType" => "INTERNET_ANONYMOUS", 
    "MerchantReference" => "OnlineBikeStore", 
    "OriginalTransactionNumber" => ""
);

$bpoint->processPayment($trans)->getResponse();

$trans = array(
    "CardNumber" => "5123456789012346", 
    "CRN1" => "ABE",
    "CRN2" => "",
    "CRN3" => "",
    "CVC" => "123",
    "ExpiryDate" => "9951",
);

$bpoint->addToken($trans)->getResponse();
*/

$search = array(
    "timePeriod" => "14", 
    "numOfTokens" => "99999",

);

$results = $bpoint->getRecentlyModifiedTokens($search)->getResponse();

var_dump($results->DVToken);