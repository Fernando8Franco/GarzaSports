<?php

class Employee extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('employee', $con);
    }
}