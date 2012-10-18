<?php

  namespace Simplon\Core\Abstracts;

  class AbstractTaskClass extends AbstractClass
  {
    protected function respond($message, $dump = FALSE)
    {
      if($dump === FALSE)
      {
        echo "$message\n";
      }
      else
      {
        var_dump($message);
      }
    }
  }
