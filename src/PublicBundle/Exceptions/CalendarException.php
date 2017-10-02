<?php

namespace PublicBundle\Exceptions;


abstract class CalendarException extends \Exception implements ErrorCodeInterface
{
    /**
     * String code contains information about the Exception's cause
     * Added as part of error standardization initiative
     * Example: INVALID_SOME_PARAM
     * @var string
     */
    private $errorCode;

    /**
     * Constructor
     * @param string $errorCode
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($errorCode, $message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}