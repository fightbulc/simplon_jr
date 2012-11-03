<?php

  namespace App\Service;

  class FooService
  {
    public function barMe($firstName, $age)
    {
      return array(
        'message' => array(
          'yes' => 123
        ),
        'request' => array(
          'firstName' => $firstName,
          'age'       => $age,
        )
      );
    }
  }
