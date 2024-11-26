<?php

class SimpleController {

    private $action;

    public function __construct() {
        $this->action = $_GET['action'];
    }

    public function getAction() {
        return $this->action;
    }
}

$controller = new SimpleController;
echo $controller->getAction();
