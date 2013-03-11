<?php

  namespace App\Api\Web\Service;

  use App\Manager\UserManager;

  class BaseService
  {
    /**
     * @return string
     */
    public function hello()
    {
      return 'Hello!';
    }

    // ##########################################

    /**
     * @param $id
     * @return string
     */
    public function getUsernameById($id)
    {
      $username = (new UserManager())->getUsername($id);

      return $username;
    }
  }
