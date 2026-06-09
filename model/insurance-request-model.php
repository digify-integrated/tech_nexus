<?php

class InsuranceRequestModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

  
}