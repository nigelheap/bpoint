<?php 

require_once 'bpoint.php';
require_once 'config.php';

$submitBatch = true;
$getRecentlyModifiedTokens = false;
$downloadBatchByFilename = false;
$addToken = false;
$processPayment = false;

$account = array(
    'username' => BPOINT_USERNAME,
    'password' => BPOINT_PASSWORD,
    'merchantNumber' => BPOINT_MERCHANTNUMBER
);

$bpoint = new Bpoint($account);
$bpoint->connect();

if($processPayment == true){

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

    $results = $bpoint->processPayment($trans)->getResponse();
    var_dump($results);

}

if($addToken == true){

    $trans = array(
        "CardNumber" => "5123456789012346", 
        "CRN1" => "ABE",
        "CRN2" => "",
        "CRN3" => "",
        "CVC" => "123",
        "ExpiryDate" => "9951",
    );

    $results = $bpoint->addToken($trans)->getResponse();
    var_dump($results);

}

if($getRecentlyModifiedTokens == true){

    $search = array(
        "timePeriod" => "30", 
        "numOfTokens" => "99999",
    );
    $results = $bpoint->getRecentlyModifiedTokens($search)->getResponse();
    var_dump($results->DVToken);

}

if($downloadBatchByFilename == true){

    $search = array(
        "filename" => "test.txt", 
        "returnAll" => "True",
        "fileFormat" => 1
    );

    $results = $bpoint->downloadBatchByFilename($search)->getResponse();
    print_r($results);

}

if($submitBatch == true){

    // ID,Service ID,Date,Time,Seq,Version,Merch,
    // 01,CBA-EVOLVE,20140816,033705,,1,5353109693075503 
    // 50,,1234,,,10000,0,,03/15,R,,,,,,,,512345...346,MC,0,172883047,46133083047,DECLINED,4,C57 
    // 99,3,10000,0
    // Headings and rows
    $headings = array('01','CBA-EVOLVE',date('Ymd'),date('His'),'',1,5353109693075503);
    $array = array(
      array(50, '', 1002147647, '', '', '10000', 0, 5999991829848912, 9900, 'R'),
      array(50, '', 1000340594, '', '', '10000', 0, 5999991834769467, 9900, 'R'),
      array(50, '', 1000340594, '', '', '10000', 0, 5999991843646565, 9900, 'R'),
    );
    $tailings = array('99', '5', '30000');
    // Open the output stream
    $fh = fopen('php://output', 'w');
            
    // Start output buffering (to capture stream contents)
    ob_start();
    fputcsv($fh, $headings);
    if (! empty($array)) {
      foreach ($array as $item) {
        fputcsv($fh, $item);
      }
    }
    fputcsv($fh, $tailings);
    $csv = ob_get_clean();
            
    $filename = 'csv_' . date('Ymd') .'_' . date('His');
    $csv = trim($csv);
    var_dump($csv);

    $data = array(
        "filename" => $filename . ".csv", 
        "content" => $csv,

    );

    $results = $bpoint->submitBatch($data)->getRawResponse();
    var_dump($results);

}


