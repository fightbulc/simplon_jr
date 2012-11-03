<?php

  namespace Simplon\Jr\Abstracts;

  class AbstractVo extends AbstractClass
  {
    /**
     * @var array
     */
    public $_data = array();

    // ##########################################

    /**
     * @param string $key
     * @param $val
     * @return AbstractVo
     */
    public function setByKey($key, $val)
    {
      $this->_data[$key] = $val;

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
      if(array_key_exists($key, $this->_data))
      {
        return $this->_data[$key];
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function hasData()
    {
      $data = $this->getData();

      return ! empty($data) ? TRUE : FALSE;
    }

    // ##########################################

    /**
     * @param array $data
     * @return AbstractVo
     */
    public function setData($data)
    {
      if(is_array($data))
      {
        $this->_data = $data;
      }

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    public function getData()
    {
      return $this->_data;
    }
  }
