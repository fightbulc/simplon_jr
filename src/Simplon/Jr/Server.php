<?php

  namespace Simplon\Jr;

  use Simplon\Border\Request;
  use Simplon\Border\Response;

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
    protected $_responseContent = [];

    // ##########################################

    /**
     * @return \Simplon\Border\Request
     */
    protected function _getRequestHandle()
    {
      if(isset($this->_requestHandle) === FALSE)
      {
        $this->_requestHandle = Request::getInstance();
      }

      return $this->_requestHandle;
    }

    // ##########################################

    /**
     * @return \Simplon\Border\Response
     */
    protected function _getResponseHandle()
    {
      if(isset($this->_responseHandle) === FALSE)
      {
        $this->_responseHandle = new Response();
      }

      return $this->_responseHandle;
    }

    // ##########################################

    /**
     * @param $id
     * @return Server
     */
    protected function _setResponseId($id)
    {
      $this->_responseId = $id;

      return $this;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getResponseId()
    {
      return $this->_responseId;
    }

    // ##########################################

    /**
     * @param $type
     * @return Server
     */
    protected function _setResponseType($type)
    {
      $this->_responseType = $type;

      return $this;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getResponseType()
    {
      return $this->_responseType;
    }

    // ##########################################

    /**
     * @param $content
     * @return Server
     */
    protected function _setResponseContent($content)
    {
      $this->_responseContent = $content;

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getResponseContent()
    {
      return $this->_responseContent;
    }

    // ##########################################

    /**
     * @param $code
     * @return Server
     */
    protected function _setResponseStatusCode($code)
    {
      $this
        ->_getResponseHandle()
        ->setStatusCode($code);

      return $this;
    }

    // ##########################################

    /**
     * @param $responseId
     * @param $response
     */
    public function setSuccessfulResponse($responseId, $response)
    {
      $this
        ->_setResponseStatusCode('200')
        ->_setResponseId($responseId)
        ->_setResponseType('result')
        ->_setResponseContent($response);
    }

    // ##########################################

    /**
     * @param array $error
     * @return Server
     */
    public function setErrorResponse(array $error)
    {
      $this
        ->_setResponseStatusCode('500')
        ->_setResponseType('error')
        ->_setResponseContent($error);

      return $this;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function sendResponse()
    {
      return $this
        ->_getResponseHandle()
        ->sendJsonRpc($this->_getResponseType(), $this->_getResponseContent(), $this->_getResponseId());
    }

    // ##########################################

    /**
     * @param $errorMessage
     * @throws \Exception
     */
    protected function _throwException($errorMessage)
    {
      throw new \Exception($errorMessage);
    }
  }