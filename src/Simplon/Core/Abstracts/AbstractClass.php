<?php

  namespace Simplon\Core\Abstracts;

  class AbstractClass
  {
    /**
     * @return \Simplon\Core\SimplonContext
     */
    protected function getSimplonContext()
    {
      return \Simplon\Core\SimplonContext::getInstance();
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
