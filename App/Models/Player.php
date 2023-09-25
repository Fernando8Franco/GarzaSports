<?php

class Player extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('player', $con);
    }
}