<?php
require 'blum.php';

new Main();

class Main
{
    public function __construct()
    {
        $blum = new Blum();
        echo "\nBlum Bot\n";
        if (is_string($blum->checkAcount())) {
            echo $blum->checkAcount() . PHP_EOL;
            echo "Generate new token" . PHP_EOL;
            $blum->refeshAccessToken();
            echo "Token Refreshed" . PHP_EOL;
            sleep(5);
        }
        $acount = $blum->checkAcount();
        $balance = isset($acount['availableBalance']) ? $acount['availableBalance'] : "Fail Get Balance";
        $ticket = isset($acount['playPasses']) ? $acount['playPasses'] : "Fail Get Ticket";
        echo "Total Balance: " . $balance . PHP_EOL;
        echo "Total Ticket: " . $ticket . PHP_EOL;
        $daily = $blum->dailyCheckin();
        if (isset($daily['message']) && $daily['message'] === "same day") {
            echo "Checkin Status: Already Checkin" . PHP_EOL;
        } else {
            echo "Checkin Status: Checkin Success" . PHP_EOL;
        }
        if (isset($blum->checkAcount()["farming"])) {
            $claimFarming = $blum->claimFarming();
            if (isset($claimFarming["message"]) && $claimFarming["message"] === "It's too early to claim") {
                echo "Farming Status: Running" . PHP_EOL;
            } else {
                echo "Farming Claimed...."  . PHP_EOL;
                echo "Farming Status: Stopped" . PHP_EOL;
                $blum->startFarming();
                echo "Farming Started...."  . PHP_EOL;
            }
        } else {
            $blum->claimFarming();
            echo "Farming Status: Stopped" . PHP_EOL;
            $blum->startFarming();
            echo "Farming Started...."  . PHP_EOL;
        }
        $this->run();
    }

    function run()
    {
        echo "\nSelect features you want to use?\n1. Complete Task✅\n2. Claim Point Task💰\n3. Play Game🎮\nChoose 1, 2 or 3: ";

        $blum = new Blum();
        $command = $blum->readInput("");


        switch ($command) {
            case 1:
                $id = $blum->get_tasks();
                // var_dump($id);
                // die;
                if ($id == []) {
                    echo "No Task Available" . PHP_EOL;
                } elseif (is_string($id)) {
                    echo "401 Unauthorized, Please paste new token in token.txt" . PHP_EOL;
                } else {
                    foreach ($id as $i) {
                        $result = $blum->complete_task($i["id"]);
                        echo $result . "\n";
                    }
                }
                break;
            case 2:
                $result = $blum->claim_task();
                echo $result . "\n";
                break;
            case 3:
                // $points = $blum->readInput("Enter Points: ");
                $points = 280;
                $ticket = $blum->readInput("Enter total ticket you want to use: ");

                for ($i = 0; $i < $ticket; $i++) {
                    $result = $blum->playGame($points);
                    echo $result;
                    sleep(5);
                }
                // default:
                //     echo "Invalid Command";
        }
        $this->run();
    }
}
