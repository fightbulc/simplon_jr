<?php

  namespace Simplon\Jr;

  use Simplon\Jr\Interfaces\InterfaceGateway;

  abstract class Gateway extends Server implements InterfaceGateway
  {
    protected $apiDefinition = [];
    protected $apiNamespace;
    protected $_requestedDomain;
    protected $_requestedClass;
    protected $_requestedMethod;
    protected $instantiatedServiceClass;

    // ##########################################

    public function __construct()
    {
      // error/exception handler
      set_error_handler(['\Simplon\Jr\Error', '_errorHandling']);
      set_exception_handler(['\Simplon\Jr\Error', '_exceptionHandling']);
      register_shutdown_function(['\Simplon\Jr\Error', '_fatalErrorHandling']);

      // 512 MB
      ini_set('memory_limit', '536870912');

      // 30 seconds
      ini_set('max_execution_time', 30);

      // default locale: english
      setlocale(LC_ALL, 'en_EN');

      // default timezone: UTC
      date_default_timezone_set('UTC');

      // load gateway
      $this->_load();
    }

    // ##########################################

    /**
     * @return bool
     */
    public function isEnabled()
    {
      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function getNamespace()
    {
      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function hasAuth()
    {
      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function getValidServices()
    {
      return FALSE;
    }

    // ##########################################

    protected function _load()
    {
      // extract domain, class and method name
      $this->_prepareDomainClassMethodValues();

      // is JSON RPC request valid?
      $this->_validateRequest();

      // run auth if enabled
      $this->_runAuthentication();

      // run requested service
      $this->_runServiceClass();
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRequestId()
    {
      return $this
        ->_getRequestHandle()
        ->getJsonRpcId();
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRequestMethod()
    {
      return $this
        ->_getRequestHandle()
        ->getJsonRpcMethod();
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function _getRequestParams()
    {
      return $this
        ->_getRequestHandle()
        ->getJsonRpcParams();
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _prepareDomainClassMethodValues()
    {
      list($this->_requestedDomain, $this->_requestedClass, $this->_requestedMethod) = explode('.', $this->_getRequestMethod());

      return TRUE;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getNamespacePath()
    {
      $namespaceParts = explode('\\', $this->getNamespace());

      return implode('/', $namespaceParts);
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRequestedDomain()
    {
      return $this->_requestedDomain;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRequestedClass()
    {
      return $this->_requestedClass;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRequestedMethod()
    {
      return $this->_requestedMethod;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _validateRequest()
    {
      $isJsonRpc = $this
        ->_getRequestHandle()
        ->isJsonRpc();

      // generic structure check
      if(! $isJsonRpc)
      {
        $this->_throwException('Invalid JSON-RPC request');
      }

      // check if listed within valid services
      if(! $this->isEnabled())
      {
        $this->_throwException('Service Gateway access is not permitted.');
      }

      // check if listed within valid services
      if($this->getValidServices() === FALSE || ! in_array($this->_getRequestMethod(), $this->getValidServices()))
      {
        $this->_throwException('Service Request is not permitted.');
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _runAuthentication()
    {
      if($this->hasAuth() === TRUE)
      {
        $authClassName = $this->getNamespace() . '\Auth';
        $authClassInstance = new $authClassName();

        // validate class
        $classReflector = new \ReflectionClass($authClassInstance);
        $methodName = 'init';
        $requestParams = $this->_getRequestParams();
        $preparedMethodParams = $this->_getPreparedMethodParameters($classReflector, $methodName, $requestParams);

        // run auth
        $authClassResponse = $classReflector
          ->getMethod($methodName)
          ->invokeArgs($authClassInstance, $preparedMethodParams);

        if($authClassResponse === FALSE)
        {
          $this->_throwException('Authentication failed.');
        }
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getInstantiatedServiceClass()
    {
      if(! $this->instantiatedServiceClass)
      {
        $serviceClassNamespace = $this->getNamespace() . '\\Service\\' . $this->_getRequestedClass() . 'Service';
        $this->instantiatedServiceClass = new $serviceClassNamespace();
      }

      return $this->instantiatedServiceClass;
    }

    // ##########################################

    /**
     * @param \ReflectionClass $classReflector
     * @param $methodName
     * @param $requestParams
     * @return array
     */
    protected function _getPreparedMethodParameters(\ReflectionClass $classReflector, $methodName, $requestParams)
    {
      // check if method exists
      if(! $classReflector->hasMethod($methodName))
      {
        $this->_throwException('Missing method (case-sensitive): ' . $methodName);
      }

      // get parameters reflector
      $parametersReflector = $classReflector
        ->getMethod($methodName)
        ->getParameters();

      // check params existence
      $missingParams = [];
      $preparedParams = [];

      foreach($parametersReflector as $parameter)
      {
        $paramName = $parameter->getName();

        if(! isset($requestParams[$paramName]))
        {
          $missingParams[] = $paramName;
        }
        else
        {
          $preparedParams[] = $requestParams[$paramName];
        }
      }

      // report missing parameters
      if(count($missingParams) > 0)
      {
        $this->_throwException('Missing parameters (case-sensitive): ' . join(', ', $missingParams));
      }

      // return params in correct order
      return $preparedParams;
    }

    // ##########################################

    protected function _runServiceClass()
    {
      $classInstance = $this->_getInstantiatedServiceClass();
      $classReflector = new \ReflectionClass($classInstance);
      $methodName = $this->_getRequestedMethod();
      $requestParams = $this->_getRequestParams();

      // validate class
      $preparedMethodParams = $this->_getPreparedMethodParameters($classReflector, $methodName, $requestParams);

      // run class
      $serviceClassResponse = $classReflector
        ->getMethod($methodName)
        ->invokeArgs($classInstance, $preparedMethodParams);

      // set response
      $this->setSuccessfulResponse($this->_getRequestId(), $serviceClassResponse);

      // send
      $this->sendResponse();
    }
  }