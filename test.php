<?php
include "model/ball.php";

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://cricbuzz-cricket.p.rapidapi.com/mcenter/v1/89451/hscard",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: cricbuzz-cricket.p.rapidapi.com",
        "X-RapidAPI-Key: 793f8776damsh72733d3ab87e56ap14a719jsn40542b009dce"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
