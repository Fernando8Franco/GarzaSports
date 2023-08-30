<?php

class Sport extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('sport', $con);
    }
}