<?php

namespace Core\Traits;


trait ErrorTracking
{
    protected $errors = [];

    protected function addError($message)
    {
        $this->errors[] = $message;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function clearErrors()
    {
        $this->errors = [];
    }

}
