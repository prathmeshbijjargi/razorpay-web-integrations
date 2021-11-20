<?php

$key=$_POST['key'];
$secret=$_POST['secret'];
$amount=intval($_POST['amount']);
$email=$_POST['email'];
$name=$_POST['name'];

    //Create Order

    $data = [
            "amount" => $amount,
            "currency" => "INR",
            "receipt" => "rcptid_11"
        ];

        $data_string = json_encode($data);
        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_USERPWD, $key . ":" . $secret);

        $response = curl_exec($ch);

        $responseOrder = json_decode($response, true);

        // close the connection, release resources used
        curl_close($ch);



    if(!isset($responseOrder['id']) ) {
        $data = [
            'error' => true
        ];
    } else {
        $data = [
            'order_id' => $responseOrder['id'],
        ];

    }
print_r(json_encode($data, true));




