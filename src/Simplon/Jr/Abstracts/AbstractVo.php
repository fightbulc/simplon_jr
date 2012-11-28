<?php

  namespace Simplon\Jr\Abstracts;

  abstract class AbstractVo
  {
    /**
     * @var array
     */
    protected $_data = [];

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
     * @return array|null|string
     */
    public function getByKey($key)
    {
      if(! isset($this->_data[$key]))
      {
        return NULL;
      }

      $value = $this->_data[$key];

      // if not array/bool: cast to string
      if(! is_array($value) && ! is_bool($value))
      {
        $value = (string)$value;
      }

      return $value;
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
     * @return AbstractVo|static
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
