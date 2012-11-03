<?php

  namespace Simplon\Jr;

  class Server
  {
    /** @var \Simplon\Border\Request */
    protected $_requestHandle;

    /** @var \Simplon\Border\Response */
    protected $_responseHandle;

    /** @var string */
    protected $_responseType;

    /** @var array */
    protected $_responseContent = array();

    // ##########################################

    /**
     * @return \Simplon\Border\Request
     */
    protected function getRequestHandle()
    {
      if(isset($this->_requestHandle) === FALSE)
      {
        $this->_requestHandle = \Simplon\Border\Request::getInstance();
      }

      return $this->_requestHandle;
    }

    // ##########################################

    /**
     * @return \Simplon\Border\Response
     */
    protected function getResponseHandle()
    {
      if(isset($this->_responseHandle) === FALSE)
      {
        $this->_responseHandle = \Simplon\Border\Response::init();
      }

      return $this->_responseHandle;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function getResponseType()
    {
      return $this->_responseType;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function getResponseContent()
    {
      return $this->_responseContent;
    }

    // ##########################################

    /**
     * @return bool|string
     */
    protected function getResponseContentId()
    {
      $content = $this->getResponseContent();

      if(! isset($content['id']))
      {
        return FALSE;
      }

      return $content['id'];
    }

    // ##########################################

    /**
     * @param $code
     * @return Server
     */
    protected function setResponseStatusCode($code)
    {
      $this
        ->getResponseHandle()
        ->setStatusCode($code);

      return $this;
    }

    // ##########################################

    /**
     * @param $type
     * @param $content
     * @return Server
     */
    protected function setResponseContent($type, $content)
    {
      $this->_responseType = $type;
      $this->_responseContent = $content;

      return $this;
    }

    // ##########################################

    /**
     * @param array $response
     */
    public function setSuccessfulResponse(array $response)
    {
      $this->setResponseStatusCode('200');
      $this->setResponseContent('result', $response);
    }

    // ##########################################

    /**
     * @param array $error
     */
    public function setErrorResponse(array $error)
    {
      $this->setResponseStatusCode('500');
      $this->setResponseContent('error', $error);
    }

    // ##########################################

    /**
     * @return bool
     */
    public function sendResponse()
    {
      $id = $this->getResponseContentId();
      $type = $this->getResponseType();
      $content = $this->getResponseContent();

      return $this
        ->getResponseHandle()
        ->sendJsonRpc(1, $type, $content);
    }

    // ##########################################

    /**
     * @param $errorMessage
     * @return bool
     * @throws \Exception
     */
    protected function throwException($errorMessage)
    {
      throw new \Exception($errorMessage);

      return FALSE;
    }
  }