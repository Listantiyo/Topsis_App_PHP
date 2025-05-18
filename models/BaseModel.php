<?php

class Model {
    protected $DB;

    public function __construct(){
        $this->DB = require __DIR__.'/../config/database.php';
    }

    public function getDB(){
        return $this->DB;
    }

    public function getFetcAssoc($result){
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}