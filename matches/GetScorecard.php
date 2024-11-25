<?php
    $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/' . $series_id . '/' . $match_id . '/latest';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response as string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
