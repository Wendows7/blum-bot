<?php

class Blum
{
    public $token;
    public $refresh_token;

    public function __construct()
    {
        $this->token = file_get_contents('access_token.txt');
        $this->refresh_token = file_get_contents('refresh_token.txt');
    }


    function refeshAccessToken()
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
            'refresh' => $this->refresh_token
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

    function get_tasks()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/tasks');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if ($status_code == 200) {

            $decode = json_decode($result, true);
            $tasks = $decode[0]["subSections"];
            // var_dump($tasks);
            $id = [];
            foreach ($tasks as $task) {
                foreach ($task["tasks"] as $t) {
                    if ($t['status'] !== "FINISHED" && $t['status'] !== "READY_FOR_CLAIM" && $t['status'] !== "STARTED" && $t['kind'] !== "ONGOING") {
                        $id[] = $t;
                    }
                }
            }
            return $id;
        } else {
            return $status_code . " Unauthorized" . PHP_EOL;
        }
    }

    function checkAcount()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/user/balance');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if ($status_code == 200) {
            $decode = json_decode($result, true);
            return $decode;
        } else {
            return $status_code . " Unauthorized" . PHP_EOL;
        }
    }

    function claim_task()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/tasks');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $tasks = $decode[0]['tasks'];
        $no = 1;
        if ($status_code == 200) {

            $decode = json_decode($result, true);
            $tasks = $decode[0]["subSections"];
            // var_dump($tasks);
            $id = [];
            foreach ($tasks as $task) {
                foreach ($task["tasks"] as $t) {
                    if ($t['status'] == "READY_FOR_CLAIM") {
                        $id[] = $t;
                    }
                }
            }
            if ($id == []) {
                return "No Claimable Task Available" . PHP_EOL;
            } else {
                foreach ($id as $i) {
                    curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/tasks/' . $i["id"] . '/claim');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);

                    $claim = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                }
                return $claim;
            }
        } else {
            return $status_code . " Unauthorized" . PHP_EOL;
        }
    }

    function complete_task($id)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/tasks/' . $id . '/start');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        return $result;
    }

    function startFarming()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/farming/start');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        return $result;
    }

    function claimFarming()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/farming/claim');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        return $decode;
    }

    function dailyCheckin()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/daily-reward?offset=-420');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);

        return $decode;
    }

    public function playGame($points)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/game/play');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Authorization: Bearer ' . $this->token . '';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"123\", \"Not:A-Brand\";v=\"8\", \"Chromium\";v=\"123\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 10; SM-A205U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode = json_decode($result, true);
        if (!isset($decode['gameId'])) {
            return "Failed Create gameID\n";
        } else {
            $gameId = $decode['gameId'];
            echo "\nGameID: " . $gameId;

            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, 'https://game-domain.blum.codes/api/v1/game/claim');
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
            // curl setop body
            $data = array(
                'gameId' => $gameId,
                'points' => $points
            );
            $data = json_encode($data);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $data);
            sleep(30);

            $result1 = curl_exec($ch1);
            if (curl_errno($ch1)) {
                echo 'Error:' . curl_error($ch1);
            }
            curl_close($ch1);

            return "\nSuccess Play Game and Claim Point\n";
        }
    }

    public function readInput($message)
    {
        echo $message;
        return trim(fgets(STDIN)); // Membaca input dari pengguna
    }
}
