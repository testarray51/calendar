<?php

namespace PublicBundle\Factory;

use PublicBundle\Entity\Event;

class EventFactory
{
    /**
     * create your own object
     * @param $description
     * @param $fromDate
     * @param $toDate
     * @param $location
     * @param $comment
     * @return Event
     */
    public static function create($description, $fromDate, $toDate, $location, $comment)
    {
        return new Event($description, $fromDate, $toDate, $location, $comment);
    }
}