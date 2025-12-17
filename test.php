<?php

// $str = file_get_contents('test.html');


// // $str = str_replace('<li><h2>','<li>',$str);
// // $str = str_replace('</h2></li>','</li>',$str);

// $str = str_replace("<li>\n<p>",'<li>',$str);
// $str = str_replace('</p></li>','</li>',$str);


// // $str = preg_replace('/<b[^>]*>(.*?)<\/b>/s', '$1', $str );
// // $str = preg_replace('/<b[^>]*>(.*?)/s', '$1', $str );
// // $str = preg_replace('/(<(?:li|p)\s+[^>]*)(\b(?:aria-level|role)\s*=\s*"[^"]*"\s*)?([^>]*)>/', '$1$2>', $str );


// // Remove dir attribute from all tags
// $str = preg_replace('/\s+dir="[^"]*"/', '', $str);
// $str = preg_replace('/\s+id="[^"]*"/', '', $str);
// $str = preg_replace('/\s+aria-level="[^"]*"/', '', $str);
// // Remove role attribute from all tags
// $str = preg_replace('/\s+role="[^"]*"/', '', $str);

// echo $str;


// $tidy = new tidy();
// $clean = $tidy->repairString( $str );

// echo $clean ;


//function getLocalCurrencyRate($Amount, $Scr='NGN', $Dist='USD')
//{
//    // API endpoint
//    $url = 'https://api.flutterwave.com/v3/transfers/rates';
//
//    // Request parameters
//    $params = array(
//        'amount' => $Amount,
//        'destination_currency' => $Dist,
//        'source_currency' => $Scr
//    );
//
//    // Headers
//    $headers = array(
//        'Authorization: Bearer FLWSECK_TEST-da56c4922797ba69d6ef152af2b7bd6f-X'
//    );
//
//    // Initialize cURL session
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
//    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $response = curl_exec($ch);
//
//    if (curl_errno($ch)) {
//        echo 'Error: ' . curl_error($ch);
//    }
//    curl_close($ch);
//
//    $json = json_decode($response);
//
//// print_r( $json);
//
//    if($json->status == 'success'){
//		return $json->data->source->amount;
//    } else {
//        return 0;
//    }
//
//}
//
//
//echo getLocalCurrencyRate(599.99, $Scr='NGN', $Dist='GBP');