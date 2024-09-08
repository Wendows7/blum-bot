<?php
require 'blum.php';

new Main();

class Main
{
    public function __construct()
    {
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
                if ($id == []) {
                    echo "No Task Available" . PHP_EOL;
                } elseif (is_string($id)) {
                    echo "401 Unauthorized, Please paste new token in token.txt" . PHP_EOL;
                } else {
                    foreach ($id as $i) {
                        $result = $blum->complete_task($i);
                        echo $result;
                    }
                }
                break;
            case 2:
                $result = $blum->claim_task();
                echo $result;
                break;
            case 3:
                $points = $blum->readInput("Enter Points: ");
                $result = $blum->playGame($points);
            default:
                echo "Invalid Command";
        }
        $this->run();
    }
}
