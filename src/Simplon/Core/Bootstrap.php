<?php

  require_once __DIR__ . '/../../../../vendor/autoload.php';

  // ############################################

  // error/exception handler
  set_error_handler('_errorHandling');
  set_exception_handler('_exceptionHandling');
  register_shutdown_function('_fatalErrorHandling');

  // 512 MB
  ini_set('memory_limit', '536870912');

  // 30 seconds
  ini_set('max_execution_time', 30);

  // default locale: english
  setlocale(LC_ALL, 'en_EN');

  // default timezone: UTC
  date_default_timezone_set('UTC');

  // ############################################

  function _errorHandling($errNo, $errStr, $errorFile, $errorLine)
  {
    $server = new \Simplon\Server;

    $error = array(
      'error' => array(
        'no'      => $errNo,
        'message' => $errStr,
        'file'    => $errorFile,
        'line'    => $errorLine
      )
    );

    $server->setErrorResponse($error);
    $server->sendResponse();

    die();
  }

  // ############################################

  function _fatalErrorHandling()
  {
    $error = error_get_last();
    print_r($error);
  }

  // ############################################

  /**
   * @param \Exception $exception
   */
  function _exceptionHandling($exception)
  {
    $server = new \Simplon\Server;

    $error = array(
      'error' => array(
        'no'      => $exception->getCode(),
        'message' => $exception->getMessage(),
        'file'    => $exception->getFile(),
        'line'    => $exception->getLine(),
        'trace'   => $exception->getTrace()
      )
    );

    $server->setErrorResponse($error);
    $server->sendResponse();

    die();
  }