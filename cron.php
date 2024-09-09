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
        } else {
            echo "Total Balance: " . $blum->checkAcount()["availableBalance"] . PHP_EOL;
            echo "Total Ticket: " . $blum->checkAcount()["playPasses"] . PHP_EOL;
            if ($blum->dailyCheckin()['message'] === "same day") {
                echo "Checkin Status: Already Checkin" . PHP_EOL;
            } else {
                var_dump($blum->dailyCheckin());
                echo "Checkin Status: Checkin Success" . PHP_EOL;
            }
            if (isset($blum->checkAcount()["farming"])) {
                $blum->claimFarming();
                echo "Farming Claimed...."  . PHP_EOL;
                echo "Farming Status: Started" . PHP_EOL;
            } else {
                $blum->claimFarming();
                echo "Farming Status: Stopped" . PHP_EOL;
                $blum->startFarming();
                echo "Farming Started...."  . PHP_EOL;
            }
        }
        echo "\nSleeping for 12 hours\n";
        sleep(43200);
        $this->__construct();
    }
}
