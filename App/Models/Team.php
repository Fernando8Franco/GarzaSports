<?php

class Team extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('team', $con);
    }
}