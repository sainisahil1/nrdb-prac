<?php

class SimpleController {

    private $action;

    public function __construct() {
        $this->action = $_GET['action'];

        switch ($this->action) {
            case 'show': $this->showAction();
            break;
            case 'action': $this->displayAction();
            break;
        }
    }

    public function showAction () {
        echo 'Action: ' . $this->action;
    }

    public function displayAction () {
        echo 'Action: ' . $this->action;
    }

    public function getAction() {
        return $this->action;
    }



}

$controller = new SimpleController();
