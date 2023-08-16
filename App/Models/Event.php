<?php

class Event extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('event', $con);
    }
}