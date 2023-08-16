<?php

class Dependency extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('dependency', $con);
    }
}