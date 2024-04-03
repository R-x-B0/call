<?php

$NUMS = '1234567890';
$LETTS = 'abcdefghijklmnopqrstuvwxyz';

// Function to generate random string
function generateRandomString($characters, $length) {
    $randomString = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Check if phone number is provided via GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['phone'])) {
    $num = $_GET['phone'];
    
    // Generate random values
    $randomDeviceId = generateRandomString($NUMS.$LETTS, 16);
    $randomImsi = generateRandomString($NUMS, 15);
    $randomSimSerial1 = generateRandomString($NUMS, 19);
    $randomSimSerial2 = generateRandomString($NUMS, 20);
    $randomMcc = '413';
    $randomMnc = '2';

    // Prepare data
    $data = array(
        "countryCode" => "",
        "dialingCode" => NULL,
        "installationDetails" => array(
            "app" => array(
                "buildVersion" => 5,
                "majorVersion" => 11,
                "minorVersion" => 75,
                "store" => "GOOGLE_PLAY"
            ),
            "device" => array(
                "deviceId" => $randomDeviceId,
                "language" => "en",
                "manufacturer" => "Xiaomi",
                "mobileServices" => array("GMS"),
                "model" => "M2010J19SG",
                "osName" => "Android",
                "osVersion" => "10",
                "simSerials" => array($randomSimSerial1, $randomSimSerial2)
            ),
            "language" => "en",
            "sims" => array(
                array(
                    "imsi" => $randomImsi,
                    "mcc" => $randomMcc,
                    "mnc" => $randomMnc,
                    "operator" => NULL
                )
            )
        ),
        "phoneNumber" => $num,
        "region" => "region-2",
        "sequenceNo" => rand(1, 2)
    );

    // Set headers
    $headers = array(
        "content-type: application/json; charset=UTF-8",
        "accept-encoding: gzip",
        "user-agent: Truecaller/11.75.5 (Android;10)",
        "clientsecret: lvc22mp3l1sfv6ujg83rd17btt"
    );

    // Initiate cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://account-asia-south1.truecaller.com/v2/sendOnboardingOtp");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $json_response = json_decode($response, true);

    // Output response
    if ($json_response['status'] == 1 || $json_response['status'] == 9) {
        echo "Successful: " . $json_response['message'] . "\n";
    } else {
        echo "Failed: " . $json_response['message'] . "\n";
    }
}
?>