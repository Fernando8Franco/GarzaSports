<?php

class DependencySport extends Orm {
    public function __construct(PDO $con) {
        parent::__construct('dependency_sport', $con);
    }
}