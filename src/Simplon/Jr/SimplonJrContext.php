<?php

  namespace Simplon\Jr;

  class SimplonJrContext
  {
    /**
     * @var SimplonJrContext
     */
    private static $_instance;

    // ########################################

    /**
     * @return SimplonJrContext
     */
    public static function getInstance()
    {
      if(! SimplonJrContext::$_instance)
      {
        SimplonJrContext::$_instance = new SimplonJrContext();
      }

      return SimplonJrContext::$_instance;
    }
  }
