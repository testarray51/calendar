<?php

namespace PublicBundle\Entity;

class EventId
{
    private $value;

    public function __construct($value)
    {
        $this->value = (int) $value;
    }

    public function getValue()
    {
        return $this->value;
    }
    
}