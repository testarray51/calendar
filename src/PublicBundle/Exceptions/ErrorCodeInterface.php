<?php

namespace PublicBundle\Exceptions;


interface ErrorCodeInterface
{
    /**
     * @return string
     */
    public function getErrorCode();

}