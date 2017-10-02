<?php

namespace PublicBundle\Services;


class ValidationService
{

    /**
     * check if parameters are integers or not
     * @param $params
     * @return bool
     */
    public function validatePositiveNumbers($params) {
        foreach ($params as $param) {
            if (!is_int($param) && !ctype_digit($param) || (int)$param <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if parameters are string or not
     * @param $params
     * @return bool
     */
    public function validateStrings($params) {
        foreach ($params as $param) {
            if (!$param || !is_string($param)) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if parameters are string or not or skip validation if parameter is null
     * @param $params
     * @return bool
     */
    public function validateNullOrStrings($params) {
        foreach ($params as $param) {
            if ($param) {
                if (!is_string($param)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * validate if a string is a correct date time format (y-m-d)
     * @param $date
     * @return bool
     */
    public function validateDate($date){
        if(!$date)
            return true;

        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') === $date;
    }
}