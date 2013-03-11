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
        ->setErrorResponse($error)
        ->sendResponse();

      die();
    }

    // ############################################

    /**
     * @param \Exception $exception
     */
    public static function _exceptionHandling(\Exception $exception)
    {
      $error = [
        'no'      => $exception->getCode(),
        'message' => $exception->getMessage(),
        'file'    => $exception->getFile(),
        'line'    => $exception->getLine(),
        'trace'   => $exception->getTrace()
      ];

      // send
      self::_getServer()
        ->setErrorResponse($error)
        ->sendResponse();

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
