<?php

  namespace Simplon\Jr;

  class Gateway extends \Simplon\Jr\Server
  {
    protected $apiDefinition = array();
    protected $apiNamespace;
    protected $apiDomain;
    protected $apiClass;
    protected $apiMethod;
    protected $instantiatedServiceClass;

    // ##########################################

    public function __construct()
    {
      // error/exception handler
      set_error_handler(array('\Simplon\Jr\Error', '_errorHandling'));
      set_exception_handler(array('\Simplon\Jr\Error', '_exceptionHandling'));
      register_shutdown_function(array('\Simplon\Jr\Error', '_fatalErrorHandling'));

      // 512 MB
      ini_set('memory_limit', '536870912');

      // 30 seconds
      ini_set('max_execution_time', 30);

      // default locale: english
      setlocale(LC_ALL, 'en_EN');

      // default timezone: UTC
      date_default_timezone_set('UTC');

      // load gateway
      $this->load();
    }

    // ##########################################

    protected function load()
    {
      $this->setApiDefinition($this->init());

      $this->validateRequest();

      $this->runAuthentication();

      $this->runServiceClass();
    }

    // ##########################################

    /**
     * @return array
     */
    protected function init()
    {
      return array();
    }

    // ##########################################

    /**
     * @param $apiDefinition
     * @return Gateway
     */
    protected function setApiDefinition($apiDefinition)
    {
      // set definition
      $this->apiDefinition = $apiDefinition;

      // extract domain, class and method name
      $this->prepareDomainClassMethodValues();

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @return bool|mixed
     */
    protected function getApiDefinitionByKey($key)
    {
      if(! isset($this->apiDefinition[$key]))
      {
        return FALSE;
      }

      return $this->apiDefinition[$key];
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getRequestId()
    {
      return $this
        ->getRequestHandle()
        ->getJsonRpcId();
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getRequestMethod()
    {
      return $this
        ->getRequestHandle()
        ->getJsonRpcMethod();
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function getRequestParams()
    {
      return $this
        ->getRequestHandle()
        ->getJsonRpcParams();
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function prepareDomainClassMethodValues()
    {
      list($this->apiDomain, $this->apiClass, $this->apiMethod) = explode('.', $this->getRequestMethod());

      return TRUE;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function isEnabled()
    {
      return $this->getApiDefinitionByKey('enabled');
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function getApiNamespace()
    {
      return $this->getApiDefinitionByKey('namespace');
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getApiNamespacePath()
    {
      $namespaceParts = explode('\\', $this->getApiNamespace());

      return implode('/', $namespaceParts);
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function hasAuth()
    {
      return $this->getApiDefinitionByKey('auth');
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function getApiValidServices()
    {
      return $this->getApiDefinitionByKey('validServices');
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getApiDomain()
    {
      return $this->apiDomain;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getApiClass()
    {
      return $this->apiClass;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getApiMethod()
    {
      return $this->apiMethod;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function validateRequest()
    {
      $isJsonRpc = $this
        ->getRequestHandle()
        ->isJsonRpc();

      // generic structure check
      if(! $isJsonRpc)
      {
        $this->throwException('Invalid JSON-RPC request');
      }

      // check if listed within valid services
      if(! $this->isEnabled())
      {
        $this->throwException('Service Gateway access is not permitted.');
      }

      // check if listed within valid services
      if(! in_array($this->getRequestMethod(), $this->getApiValidServices()))
      {
        $this->throwException('Service Request is not permitted.');
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function runAuthentication()
    {
      if($this->hasAuth() === TRUE)
      {
        $authClassName = $this->getApiNamespace() . '\Auth';
        $params = $this->getRequestParams();
        $authClassInstance = new $authClassName();

        // validate class
        $classReflector = new \ReflectionClass($authClassInstance);
        $methodName = 'init';
        $requestParams = $this->getRequestParams();
        $preparedMethodParams = $this->getPreparedMethodParameters($classReflector, $methodName, $requestParams);

        // run auth
        $authClassResponse = $classReflector
          ->getMethod($methodName)
          ->invokeArgs($authClassInstance, $preparedMethodParams);

        if($authClassResponse === FALSE)
        {
          $this->throwException('Authentication failed.');
        }
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function getInstantiatedServiceClass()
    {
      if(! $this->instantiatedServiceClass)
      {
        $serviceClassNamespace = $this->getApiNamespace() . '\\Service\\' . $this->getApiClass() . 'Service';
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
    protected function getPreparedMethodParameters(\ReflectionClass $classReflector, $methodName, $requestParams)
    {
      // check if method exists
      if(! $classReflector->hasMethod($methodName))
      {
        $this->throwException('Missing method (case-sensitive): ' . $methodName);
      }

      // get parameters reflector
      $parametersReflector = $classReflector
        ->getMethod($methodName)
        ->getParameters();

      // check params existence
      $missingParams = array();
      $preparedParams = array();

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
        $this->throwException('Missing parameters (case-sensitive): ' . join(', ', $missingParams));
      }

      // return params in correct order
      return $preparedParams;
    }

    // ##########################################

    protected function runServiceClass()
    {
      $classInstance = $this->getInstantiatedServiceClass();
      $classReflector = new \ReflectionClass($classInstance);
      $methodName = $this->getApiMethod();
      $requestParams = $this->getRequestParams();

      // validate class
      $preparedMethodParams = $this->getPreparedMethodParameters($classReflector, $methodName, $requestParams);

      // run class
      $serviceClassResponse = $classReflector
        ->getMethod($methodName)
        ->invokeArgs($classInstance, $preparedMethodParams);

      // set response
      $this->setSuccessfulResponse($this->getRequestId(), $serviceClassResponse);

      // send
      $this->sendResponse();
    }
  }