<?php

namespace App;

class Redirect{
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }
}