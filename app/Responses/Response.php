<?php

namespace App\Responses;

class Response implements ResponseInterface
{
    private $data;
    private $message;
    private $status;

    public function __construct(
        $data = [],
        $message = 'Oops! Something went wrong. Please contact the system administrator for further assistance.',
        $status = false
    ) {
        $this->data = $data;
        $this->message = $message;
        $this->status = $status;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}
