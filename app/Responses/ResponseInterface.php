<?php

namespace App\Responses;

interface ResponseInterface
{
    /**
     * Set Response data
     * @param $data
     */
    public function setData($data);

    /**
     * Get Response data
     * @return mixed
     */
    public function getData();

    /**
     * Set message of response
     * @param $message
     */
    public function setMessage($message);

    /**
     * Get message of response
     * @return mixed
     */
    public function getMessage();

    /**
     * Set status of response
     * @param bool $status
     */
    public function setStatus(bool $status);

    /**
     * Get status of response
     * @return bool
     */
    public function getStatus() : bool;
}
