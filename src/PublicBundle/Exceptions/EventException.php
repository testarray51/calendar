<?php

namespace PublicBundle\Exceptions;

use PublicBundle\Entity\Event;

class EventException extends CalendarException
{
    const RESPONSE_BAD_REQUEST = "Invalid parameters on request.";
    const RESPONSE_NOT_FOUND = "Event not found.";
    const RESPONSE_INVALID_ORDER = "Order should be: " . Event::ORDER_ASC . " or " . Event::ORDER_DESC . '.';
    const RESPONSE_INVALID_DATE_FORMAT = "Invalid date format";

}