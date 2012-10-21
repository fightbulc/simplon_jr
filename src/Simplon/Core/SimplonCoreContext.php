<?php

  namespace Simplon\Core;

  class SimplonCoreContext
  {
    /**
     * @var SimplonCoreContext
     */
    private static $_instance;

    // ########################################

    /**
     * @return SimplonCoreContext
     */
    public static function getInstance()
    {
      if(! SimplonCoreContext::$_instance)
      {
        SimplonCoreContext::$_instance = new SimplonCoreContext();
      }

      return SimplonCoreContext::$_instance;
    }
  }
