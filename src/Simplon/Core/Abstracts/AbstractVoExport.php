<?php

  namespace Simplon\Core\Abstracts;

  class AbstractVoExport
  {
    /**
     * @param $vo
     * @return array
     */
    public static function one($vo)
    {
      return array();
    }

    // ##########################################

    /**
     * @param $voMany
     * @return array
     */
    public static function many($voMany)
    {
      $export = array();

      foreach($voMany as $vo)
      {
        $export[] = static::one($vo);
      }

      return $export;
    }
  }
