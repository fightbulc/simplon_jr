<?php

  namespace Simplon\Jr;

  class Gateway extends \Simplon\Jr\Server
  {
    protected $namespace;
    protected $apiDefinition = array();
    protected $apiDomain;
    protected $apiClass;
    protected $apiMethod;
    protected $requestedServiceClass;
    protected $preparedMethodParams = array();

    /** @var \ReflectionClass */
    protected $_classReflector;

    /** @var \ReflectionMethod */
    protected $_methodReflector;

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

      // run gateway
      $this->run();
    }

    // ##########################################

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
     * @return bool
     */
    protected function parseDomainClassMethodValue()
    {
      $domainClassMethod = $this
        ->getRequestHandle()
        ->getJsonRpcMethod();

      list($this->apiDomain, $this->apiClass, $this->apiMethod) = explode('.', $domainClassMethod);

      return TRUE;
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
     * @return mixed
     */
    protected function getApiMethod()
    {
      return $this->apiMethod;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getNamespace()
    {
      return $this->namespace;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getNamespacePath()
    {
      $namespaceParts = explode('\\', $this->getNamespace());

      return implode('/', $namespaceParts);
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

      if(! $isJsonRpc)
      {
        $this->throwException('Invalid JSON-RPC request');
      }

      return TRUE;
    }

    // ##########################################

    protected function runAuthentication()
    {
      if($this->hasAuth() === TRUE)
      {
        $authClassName = $this->getNamespace() . '\Auth';
        $params = $this->getRequestParams();
        $authClass = new $authClassName();

        if($authClass->init($params) === FALSE)
        {
          $this->throwException('Authentication failed.');
        }
      }

      return TRUE;
    }

    // ##########################################

    protected function instantiateServiceClass()
    {
      // instantiate service class
      $serviceClassNamespace = $this->getNamespace() . '\\Service\\' . $this->apiClass . 'Service';
      $this->requestedServiceClass = new $serviceClassNamespace();
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
     * @return \ReflectionClass
     */
    protected function getClassReflector()
    {
      if(! isset($this->_classReflector))
      {
        $this->_classReflector = new \ReflectionClass($this->requestedServiceClass);
      }

      return $this->_classReflector;
    }

    // ##########################################

    /**
     * @return \ReflectionMethod
     */
    protected function getMethodReflector()
    {
      if(! isset($this->_methodReflector))
      {
        $this->_methodReflector = $this
          ->getClassReflector()
          ->getMethod($this->getApiMethod());
      }

      return $this->_methodReflector;
    }

    // ##########################################

    protected function validateServiceClass()
    {
      // instantiate service class
      $this->instantiateServiceClass();

      // check if method exists
      $this->validateClassMethod();

      // parameters check
      $this->validateMethodParameters();
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function validateClassMethod()
    {
      $classReflector = $this->getClassReflector();

      if(! $classReflector->hasMethod($this->getApiMethod()))
      {
        return $this->throwException('Missing method: ' . $this->getApiMethod());
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function validateMethodParameters()
    {
      $missingParams = array();
      $requestParams = $this->getRequestParams();

      $parametersReflector = $this
        ->getMethodReflector()
        ->getParameters();

      foreach($parametersReflector as $parameter)
      {
        $paramPos = $parameter->getPosition();
        $paramName = $parameter->getName();

        if(! isset($requestParams[$paramName]))
        {
          $missingParams[] = $paramName;
        }
        else
        {
          $this->addPreparedParamter($paramPos, $requestParams[$paramName]);
        }
      }

      // report missing parameters
      if(count($missingParams) > 0)
      {
        return $this->throwException('Missing parameters (case-sensitive): ' . join(', ', $missingParams));
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @param $pos
     * @param $value
     * @return Gateway
     */
    protected function addPreparedParamter($pos, $value)
    {
      $this->preparedMethodParams[$pos] = $value;

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function getPreparedParamters()
    {
      return $this->preparedMethodParams;
    }

    // ##########################################

    protected function runServiceClass()
    {
      $classReflector = new \ReflectionClass($this->requestedServiceClass);
      $methodReflector = $classReflector->getMethod($this->getApiMethod());

      // call requested method
      $serviceClassResponse = $methodReflector->invokeArgs($this->requestedServiceClass, $this->preparedMethodParams);

      // set response
      $this->setSuccessfulResponse($serviceClassResponse);

      // send
      $this->sendResponse();
    }

    // ##########################################

    protected function run()
    {
      $this->setApiDefinition($this->init());

      $this->validateRequest();

      $this->parseDomainClassMethodValue();

      $this->instantiateServiceClass();

      $this->validateServiceClass();

      $this->runAuthentication();

      $this->runServiceClass();
    }
  }