<?php

    namespace Simplon\Jr;

    class ErrorException extends \Exception
    {
        protected $subcode;

        // ######################################

        public function __construct($message, $code, $subcode = 0)
        {
            $this->message = $message;
            $this->code = $code;
            $this->subcode = $subcode;
        }

        // ######################################

        public function getSubcode()
        {
            return $this->subcode;
        }
    }