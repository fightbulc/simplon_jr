<?php

  namespace Simplon\Jr;

  class Server
  {
    /** @var \Simplon\Border\Request */
    protected $_requestHandle;

    /** @var \Simplon\Border\Response */
    protected $_responseHandle;

    /** @var string */
    protected $_responseId;

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
     * @param $id
     * @return Server
     */
    protected function setResponseId($id)
    {
      $this->_responseId = $id;

      return $this;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getResponseId()
    {
      return $this->_responseId;
    }

    // ##########################################

    /**
     * @param $type
     * @return Server
     */
    protected function setResponseType($type)
    {
      $this->_responseType = $type;

      return $this;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getResponseType()
    {
      return $this->_responseType;
    }

    // ##########################################

    /**
     * @param array $content
     * @return Server
     */
    protected function setResponseContent(array $content)
    {
      $this->_responseContent = $content;

      return $this;
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
     * @param $responseId
     * @param array $response
     */
    public function setSuccessfulResponse($responseId, array $response)
    {
      $this->setResponseStatusCode('200');
      $this->setResponseId($responseId);
      $this->setResponseType('result');
      $this->setResponseContent($response);
    }

    // ##########################################

    /**
     * @param array $error
     */
    public function setErrorResponse(array $error)
    {
      $this->setResponseStatusCode('500');
      $this->setResponseType('error');
      $this->setResponseContent($error);
    }

    // ##########################################

    /**
     * @return bool
     */
    public function sendResponse()
    {
      return $this
        ->getResponseHandle()
        ->sendJsonRpc($this->getResponseType(), $this->getResponseContent(), $this->getResponseId());
    }

    // ##########################################

    /**
     * @param $errorMessage
     * @throws \Exception
     */
    protected function throwException($errorMessage)
    {
      throw new \Exception($errorMessage);
    }
  }