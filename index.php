<?php
require 'blum.php';

new Main();

class Main
{
    public function __construct()
    {
        $blum = new Blum();
        echo "Blum Bot\n";
        if (is_string($blum->checkAcount())) {
            echo $blum->checkAcount() . PHP_EOL;
        } else {
            echo "Total Balance: " . $blum->checkAcount()["availableBalance"] . PHP_EOL;
            echo "Total Ticket: " . $blum->checkAcount()["playPasses"] . PHP_EOL;
        }
        if ($blum->dailyCheckin()['message'] === "same day") {
            echo "Checkin Status: Already Checkin" . PHP_EOL;
        } else {
            $blum->dailyCheckin();
            echo "Checkin Status: Checkin Success" . PHP_EOL;
        }
        if (isset($blum->checkAcount()["farming"])) {
            echo "Farming Status: Started" . PHP_EOL;
        } else {
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
                }
                // default:
                //     echo "Invalid Command";
        }
        $this->run();
    }
}
