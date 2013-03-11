<?php

  namespace App\Api\Web\Service;

  class BaseService
  {
    /**
     * @param string $name
     * @return string
     */
    public function hello($name)
    {
      return 'Hello ' . $name;
    }

    // ##########################################

    /**
     * @param $firstName
     * @param $age
     * @return array
     */
    public function showMoreResponse($firstName, $age)
    {
      return [
        'message' => [
          'yes' => 123
        ],
        'request' => [
          'firstName' => $firstName,
          'age'       => $age,
        ],
      ];
    }
  }
