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
            $this->__construct();
        } else {
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

            echo "\nSleeping for 3 hours\n";
            sleep(10800);
            $this->__construct();
        }
    }
}
