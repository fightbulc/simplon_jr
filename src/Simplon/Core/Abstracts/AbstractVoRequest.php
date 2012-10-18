<?php

  namespace Simplon\Core\Abstracts;

  class AbstractVoRequest extends AbstractVo
  {
    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
      if(! array_key_exists($key, $this->_data))
      {
        $this->throwException('Requested key <' . $key . '> is not implemented.');
      }

      return $this->_data[$key];
    }
  }
