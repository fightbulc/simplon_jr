<?php

  namespace Simplon\Jr\Abstracts;

  class AbstractClass
  {
    /**
     * @return \Simplon\Jr\SimplonJrContext
     */
    protected function getSimplonContext()
    {
      return \Simplon\Jr\SimplonJrContext::getInstance();
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
