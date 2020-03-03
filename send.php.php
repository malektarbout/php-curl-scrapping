<?php

function header_parse($header_data) {
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header_data, $matches);
    $cookies = array();
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    if (array_key_exists('KP_UIDz', $cookies)) return $cookies['KP_UIDz'];
    elseif (array_key_exists('kmam_lapoz', $cookies)) return $cookies['kmam_lapoz'];
}

function get_html($main_cookie='') {
    if ($main_cookie)
        $headers =  array(
            "Cookie: 
                reauid=452537170f5000009fde5d5e2e00000028000000; 
                bm_sdc=74a58c71-14ab-c7d1-7a81-8210d0ee627b; 
                kmam_lapoz=$main_cookie",
            "referer: https://www.realestate.com.au/rent/list-1?includeSurrounding=false&activeSort=list-date",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36"
        );
    else
        $headers =  array(
            "referer: https://www.realestate.com.au/rent/list-1?includeSurrounding=false&activeSort=list-date",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36"
        );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.realestate.com.au/rent/list-1?includeSurrounding=false&activeSort=list-date",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_HEADER => 1
    ));

    $response = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($code === 200) return $response;
    $cookie = header_parse($response);
    return get_html($cookie);
}

$result = get_html();
echo $result;

