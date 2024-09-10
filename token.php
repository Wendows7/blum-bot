<?php

new Blum();

class Blum
{
    public $token;

    public function __construct()
    {
        $this->token = file_get_contents('access_token.txt');
        $this->getRefreshToken();
    }


    function getRefreshToken()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://user-domain.blum.codes/api/v1/auth/refresh');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        $data = array(
            'refresh' => $this->token
        );
        $dataEncode = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataEncode);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        file_put_contents('access_token.txt', $decode['access']);
        file_put_contents('refresh_token.txt', $decode['refresh']);
    }
}
