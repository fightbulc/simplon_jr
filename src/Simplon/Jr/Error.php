<?php

  namespace Simplon\Jr;

  class Error
  {
    /**
     * @param $errNo
     * @param $errStr
     * @param $errorFile
     * @param $errorLine
     */
    public static function _errorHandling($errNo, $errStr, $errorFile, $errorLine)
    {
      $server = new \Simplon\Jr\Server;

      $error = array(
        'no'      => $errNo,
        'message' => $errStr,
        'file'    => $errorFile,
        'line'    => $errorLine
      );

      $server->setErrorResponse($error);
      $server->sendResponse();

      die();
    }

    // ############################################

    /**
     * @param \Exception $exception
     */
    public static function _exceptionHandling(\Exception $exception)
    {
      $server = new \Simplon\Jr\Server;

      $error = array(
        'no'      => $exception->getCode(),
        'message' => $exception->getMessage(),
        'file'    => $exception->getFile(),
        'line'    => $exception->getLine(),
        'trace'   => $exception->getTrace()
      );

      $server->setErrorResponse($error);
      $server->sendResponse();

      die();
    }

    // ############################################

    public static function _fatalErrorHandling()
    {
      $error = error_get_last();

      if($error)
      {
        print_r($error);
      }
    }
  }
