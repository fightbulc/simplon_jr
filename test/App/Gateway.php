<?php

  namespace App;

  class Gateway extends \Simplon\Jr\Gateway
  {
    public function init()
    {
      $this->namespace = __NAMESPACE__;

      return array(
        // state of gateway
        'enabled'       => TRUE,
        // auth definition
        'auth'          => TRUE,
        // allowed services
        'validServices' => array(

          'Auth.Base.getInitialData',

        ),
      );
    }
  }
