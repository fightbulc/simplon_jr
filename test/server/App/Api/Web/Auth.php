<?php

  namespace App\Api\Web;

  class Auth
  {
    public function init($user, $pass)
    {
      if($user === 'admin' && $pass == '123456')
      {
        return TRUE;
      }

      return FALSE;
    }
  }
