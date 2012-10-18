<?php

  namespace Simplon\Core\Abstracts;

  class AbstractClass
  {
    /**
     * @return \Simplon\SimplonContext
     */
    protected function getSimplonContext()
    {
      return \Simplon\SimplonContext::getInstance();
    }

    // ########################################

    /**
     * @param $message
     * @throws \Exception
     */
    protected function throwException($message)
    {
      throw new \Exception($message);
      die();
    }
  }
