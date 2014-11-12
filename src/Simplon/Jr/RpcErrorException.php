<?php

    namespace Simplon\Jr;

    class RpcErrorException extends \Exception
    {
        protected $subcode;

        // ######################################

        public function __construct($code, $message, $subcode = 0)
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