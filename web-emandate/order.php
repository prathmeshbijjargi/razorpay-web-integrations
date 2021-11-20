<?php

$key=$_POST['key'];
$secret=$_POST['secret'];
$authType=$_POST['auth_type'];
$email=$_POST['email'];
$name=$_POST['name'];
$acc_no=$_POST['acc'];
$ifsc=$_POST['ifsc'];
$savings=$_POST['savings'];

//Create Customer
    $data=[
        "name"=>$name,
        "contact"=>"9123456780",
        "email"=>$email,
        "fail_existing"=>"0",
    ];
    $data_string = json_encode($data);
    $chi = curl_init('https://api.razorpay.com/v1/customers');
    curl_setopt($chi, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($chi, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chi, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($chi, CURLOPT_USERPWD, $key. ":" .$secret);

    // execute!
    $response = curl_exec($chi);

    // do anything you want with your response
    $responseCustomer = json_decode($response, true);

    //Create Order

    if($responseCustomer['id']) {

        $data=[
            "amount"=> 0,
            "currency"=> "INR",
            "method"=> "emandate",
            "customer_id"=> $responseCustomer['id'],
            "receipt"=> "Receipt No. 1",
            "payment_capture"=>"1",
            "notes"=> [
                "notes_key_1"=> "Beam me up Scotty",
                "notes_key_2"=> "Engage"
            ],
            "token"=> [
                "auth_type"=> $authType,
                "max_amount"=> 200000,
                "bank_account"=> [
                    "beneficiary_name"=> $name,
                    "account_number"=> $acc_no,
                    "account_type"=> $savings,
                    "ifsc_code"=> $ifsc
                ]
            ]
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
    }else{
    curl_close($chi);
}


    if(!isset($responseOrder['id']) || !isset($responseCustomer['id'])) {
        $data = [
            'error' => true
        ];
    } else {
        $data = [
            'order_id' => $responseOrder['id'],
            'customer_id' => $responseCustomer['id'],
        ];

    }
print_r(json_encode($data, true));




