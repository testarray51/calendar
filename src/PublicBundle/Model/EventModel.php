<?php

namespace PublicBundle\Model;


use PublicBundle\Entity\Event;

class EventModel
{
    /**
     * @param Event $event
     * @param $description
     * @param $from
     * @param $to
     * @param $location
     * @param $comment
     * @return Event
     */
    public static function updateObject(Event $event, $description, $from, $to, $location, $comment) {

        if($description)
            $event->setDescription($description);

        if($from)
            $event->setFromDate($from);

        if($to)
            $event->setToDate($to);

        if($location)
            $event->setLocation($location);

        if($comment)
            $event->setComment($comment);

        return $event;
    }

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';
}