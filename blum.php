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

        curl_setopt($ch, CURLOPT_URL, 'https://earn-domain.blum.codes/api/v1/tasks');
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
            $tasks = $decode;

            $id = [];
            foreach ($tasks as $allTasks) {
                if ($allTasks['tasks'] !== [] && $allTasks['subSections'] == []) {
                    foreach ($allTasks['tasks'] as $task) {
                        foreach ($task['subTasks'] as $subTask) {
                            if ($subTask['status'] !== "FINISHED" && $subTask['status'] !== "READY_FOR_CLAIM" && $subTask['status'] !== "STARTED" && $subTask['kind'] !== "ONGOING") {
                                $id[] = $subTask;
                            }
                        }
                    }
                } else {
                    foreach ($allTasks['subSections'] as $subSection) {
                        foreach ($subSection['tasks'] as $task) {
                            if ($task['status'] !== "FINISHED" && $task['status'] !== "READY_FOR_CLAIM" && $task['status'] !== "STARTED" && $task['kind'] !== "ONGOING") {
                                $id[] = $task;
                            }
                        }
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

        curl_setopt($ch, CURLOPT_URL, 'https://earn-domain.blum.codes/api/v1/tasks');
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
            $tasks = $decode;
            // var_dump($tasks);
            $id = [];
            foreach ($tasks as $allTasks) {
                if ($allTasks['tasks'] !== [] && $allTasks['subSections'] == []) {
                    foreach ($allTasks['tasks'] as $task) {
                        foreach ($task['subTasks'] as $subTask) {
                            if ($subTask['status'] == "READY_FOR_CLAIM") {
                                $id[] = $subTask;
                            }
                        }
                    }
                } else {
                    foreach ($allTasks['subSections'] as $subSection) {
                        foreach ($subSection['tasks'] as $task) {
                            if ($task['status'] == "READY_FOR_CLAIM") {
                                $id[] = $task;
                            }
                        }
                    }
                }
            }
            if ($id == []) {
                return "No Claimable Task Available" . PHP_EOL;
            } else {
                foreach ($id as $i) {
                    curl_setopt($ch, CURLOPT_URL, 'https://earn-domain.blum.codes/api/v1/tasks/' . $i["id"] . '/claim');
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

        curl_setopt($ch, CURLOPT_URL, 'https://earn-domain.blum.codes/api/v1/tasks/' . $id . '/start');
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

        curl_setopt($ch, CURLOPT_URL, 'https://game-domain.blum.codes/api/v2/game/play');
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

            $payload = $this->createPayload($gameId, $points, 1);
            if (is_object(json_decode($payload))) {
                echo "\nSuccess Create Payload\n";
            } else {
                echo "\nFailed Create Payload\n";
                $payload = $this->createPayload($gameId, $points, 1);
            }


            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, 'https://game-domain.blum.codes/api/v2/game/claim');
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
            // curl setop body
            $data = array(
                'gameId' => $gameId,
                'points' => $points
            );
            $data = json_encode($data);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $payload);
            sleep(30);

            $result1 = curl_exec($ch1);
            if (curl_errno($ch1)) {
                echo 'Error:' . curl_error($ch1);
            }
            curl_close($ch1);

            return "\nSuccess Play Game and Claim Point\n";
        }
    }

    public function createPayload($gameId, $points, $dogs)
    {
        $data = [
            'game_id' => $gameId,
            'points' => $points,
            'dogs' => $dogs,
        ];

        $dataGame = json_encode($data, true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/zuydd/database/main/blum.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $dataDecode = json_decode($result, true);

        $urlServer = [];

        foreach ($dataDecode['payloadServer'] as $data) {
            if ($data['status'] == 1) {
                $urlServer[] = $data;
            }
        }

        $getKey = array_keys($urlServer);
        $lastKey = end($getKey);
        $lastId = $urlServer[$lastKey]['id'];


        $ch2 = curl_init();
        $url = "https://" . $lastId . ".vercel.app/api/blum";
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
        $headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 10; SM-A205U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36';
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $dataGame);

        $payload = curl_exec($ch2);

        if (curl_errno($ch2)) {
            echo 'Error:' . curl_error($ch2);
        }
        curl_close($ch2);
        return $payload;
    }

    public function readInput($message)
    {
        echo $message;
        return trim(fgets(STDIN)); // Membaca input dari pengguna
    }
}
