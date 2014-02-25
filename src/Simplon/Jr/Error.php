<?php

    namespace Simplon\Jr;

    class Error
    {
        /**
         * @return Server
         */
        protected static function _getServer()
        {
            return new Server();
        }

        // ##########################################

        /**
         * @param $errNo
         * @param $errStr
         * @param $errorFile
         * @param $errorLine
         */
        public static function _errorHandling($errNo, $errStr, $errorFile, $errorLine)
        {
            $error = [
                'no'      => $errNo,
                'message' => $errStr,
                'file'    => $errorFile,
                'line'    => $errorLine
            ];

            // send
            self::_getServer()
                ->setErrorResponse(500, $error)
                ->sendResponse();

            die();
        }

        // ############################################

        /**
         * @param \Exception $e
         */
        public static function _exceptionHandling(\Exception $e)
        {
            // handle known exception
            if ($e instanceof RpcErrorException)
            {
                $statusCode = 200;

                $error = [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'subcode' => $e->getSubcode(),
                ];
            }

            // handle uncaught exception
            else
            {
                $statusCode = 500;

                $error = [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTrace()
                ];
            }

            // send
            self::_getServer()
                ->setErrorResponse($statusCode, $error)
                ->sendResponse();

            die();
        }

        // ############################################

        public static function _fatalErrorHandling()
        {
            $error = error_get_last();

            if ($error)
            {
                print_r($error);
            }
        }
    }
