<?php

class Jogos {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getPdo() {
        return $this->pdo;
    }


}