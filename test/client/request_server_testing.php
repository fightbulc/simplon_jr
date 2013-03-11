<?php

  require __DIR__ . '/../vendor/autoload.php';

  // url to server gateway
  $urlServiceGateway = 'http://localhost/opensource/server/simplon/simplon_jr/test/server/public';

  // ############################################

  // send request
  $response = (new JsonRpcCurl())
    ->setUrl($urlServiceGateway . '/api/web/')
    ->setId(1)
    ->setMethod('Web.Base.hello')
    ->send();

  // dump response
  echo '<h1>Response for "Web.Base.hello":</h1>';
  var_dump($response); // prints: Hello!

  // ############################################

  // send request
  $response = (new JsonRpcCurl())
    ->setUrl($urlServiceGateway . '/api/web/')
    ->setId(1)
    ->setMethod('Web.Base.getUsernameById')
    ->setData(['id' => 35])
    ->send();

  // dump response
  echo '<h1>Response for "Web.Base.getUsername":</h1>';
  var_dump($response); // prints: Hansi