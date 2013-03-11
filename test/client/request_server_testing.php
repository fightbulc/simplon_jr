<?php

  require __DIR__ . '/../vendor/autoload.php';

  // config
  $urlService = 'http://localhost/opensource/server/simplon/simplon_jr/test/server/public';
  $useProxy = TRUE;

  // ############################################

  // create request
  $request = (new JsonRpcCurl())
    ->setUrl($urlService . '/api/web/')
    ->setId(1)
    ->setMethod('Web.Base.hello')
    ->setData(['name' => 'Mr. Tester']);

  // add proxy to sniff communication
  if($useProxy === TRUE)
  {
    $request->setProxy('127.0.0.1', 8888);
  }

  // send request
  $response = $request->send();

  // dump response
  echo '<h1>Response for "Web.Base.hello":</h1>';
  var_dump($response);

  // ############################################

  // create request
  $request = (new JsonRpcCurl())
    ->setUrl($urlService . '/api/web/')
    ->setId(1)
    ->setMethod('Web.Base.showMoreResponse')
    ->setData(['firstName' => 'Hans', 'age' => 35]);

  // add proxy to sniff communication
  if($useProxy === TRUE)
  {
    $request->setProxy('127.0.0.1', 8888);
  }

  // send request
  $response = $request->send();

  // dump response
  echo '<h1>Response for "Web.Base.showMoreResponse":</h1>';
  var_dump($response);