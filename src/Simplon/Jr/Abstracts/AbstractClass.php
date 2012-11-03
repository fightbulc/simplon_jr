<?php

  namespace Simplon\Jr\Abstracts;

  class AbstractClass
  {
    /**
     * @return \Simplon\Jr\SimplonContext
     */
    protected function getSimplonContext()
    {
      return \Simplon\Jr\SimplonContext::getInstance();
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
