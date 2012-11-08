<?php

  namespace Simplon\Jr\Abstracts;

  abstract class AbstractTaskClass
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
