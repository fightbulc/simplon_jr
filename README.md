<pre>
     _                 _                 _
 ___(_)_ __ ___  _ __ | | ___  _ __     (_)_ __
/ __| | '_ ` _ \| '_ \| |/ _ \| '_ \    | | '__|
\__ \ | | | | | | |_) | | (_) | | | |   | | |
|___/_|_| |_| |_| .__/|_|\___/|_| |_|  _/ |_|
                |_|                   |__/
</pre>

# Simplon/Jr

A JSON-RPC Server

## 1. Introduction

<a name="info-json"></a>
### 1.1. What is JSON?
JSON (JavaScript Object Notation) is a lightweight data-interchange format. It is easy for humans to read and write. It is easy for machines to parse and generate. [Read on](http://www.json.org/).

<a name="info-rpc"></a>
### 1.2. What is RPC?
In computer science, a remote procedure call (RPC) is an inter-process communication that allows a computer program to cause a subroutine or procedure to execute in another address space (commonly on another computer on a shared network) without the programmer explicitly coding the details for this remote interaction. Many different (often incompatible) technologies can be used to implement the concept. [Read on](http://en.wikipedia.org/wiki/Remote_procedure_call).

<a name="jsonrpc-specifications"></a>
### 1.3. Specifications
JSON-RPC is a stateless, light-weight remote procedure call (RPC) protocol. Primarily this specification defines several data structures and the rules around their processing. It is transport agnostic in that the concepts can be used within the same process, over sockets, over http, or in many various message passing environments. It uses JSON (RFC 4627) as data format. [Read on](http://www.jsonrpc.org/specification).

<a name="jsonrpc-examples"></a>
### 1.4. Request/Response examples
Client request:

```json
{"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}
```

Server response:

```json
{"jsonrpc": "2.0", "result": 19, "id": 3}
```

<a name="jsonrpc-setup"></a>
## 2. Example setup
The following steps should demonstrate a standard working example of Simplon/Jr. The example setup [resides within the test folder](test/) which is part of this repo.

<a name="jsonrpc-dependencies"></a>
### 2.1. Dependencies
Simplon/Jr is build on top of the php dependency manager [Composer](http://getcomposer.org). In case composer is not installed do so now, please. It's important that you make yourself familiar with Composer. So take some time and have a look at composer's [documentation](http://getcomposer.org/doc/).

Use your terminal and switch to the test folder. Entering ```ls -la``` should bring up something similiar to the following:

```bash
drwxr-xr-x   6 fightbulc  staff   204 11 Mär 15:09 .
drwxr-xr-x  12 fightbulc  staff   408 11 Mär 11:05 ..
drwxr-xr-x   3 fightbulc  staff   102 11 Mär 14:39 client
-rw-r--r--   1 fightbulc  staff   251 11 Mär 12:00 composer.json
drwxr-xr-x   4 fightbulc  staff   136 11 Mär 11:39 server
```

The ```composer.json``` file reveals some package details and more importantly it tells Composer about our dependencies:

```json
{
  "name": "simplon/jr_testing",
  "description": "JSON-RPC Server testing",

  "require": {
    "php": ">=5.4",
    "simplon/jr": "0.5.2",
    "fightbulc/jsonrpc_curl": "0.5.1"
  },

  "autoload": {
    "psr-0": {
      "App": "server/"
    }
  }
}
```

Ok, enough talk lets install the dependencies via ```composer install```. This should result in some action on your screen which in turn leaves us with our dependencies and some mappings for composer's autoloader class (all within the vendor folder):

```bash
drwxr-xr-x   8 fightbulc  staff   272 11 Mär 15:18 .
drwxr-xr-x  12 fightbulc  staff   408 11 Mär 11:05 ..
drwxr-xr-x   3 fightbulc  staff   102 11 Mär 14:39 client
-rw-r--r--   1 fightbulc  staff   251 11 Mär 12:00 composer.json
-rw-r--r--   1 fightbulc  staff  5571 11 Mär 15:18 composer.lock
drwxr-xr-x   4 fightbulc  staff   136 11 Mär 11:39 server
drwxr-xr-x   6 fightbulc  staff   204 11 Mär 15:18 vendor
```

<a name="jsonrpc-setup-server"></a>
### 2.2. Server
Good, by now we should have our depencies so lets start building our server. To accomplish this we need to make three steps or four in case we want to include authentication. Follow the upcoming steps:

<a name="jsonrpc-setup-gateway"></a>
### 2.2.1. Gateway
First, lets create a ```Gateway class```. The gateway class defines all requirements for a service setup. Services are separated by a given domain name. For our example we will choose the domain ```Web```. Therefore, our gateway class resides ```/server/App/Api/Web/Gateway.php```:

```php
namespace App\Api\Web;

use Simplon\Jr\Interfaces\InterfaceGateway;

class Gateway extends \Simplon\Jr\Gateway implements InterfaceGateway
{
  /**
   * @return bool
   */
  public function isEnabled()
  {
    return TRUE;
  }

  // ##########################################

  /**
   * @return bool|string
   */
  public function getNamespace()
  {
    return __NAMESPACE__;
  }

  // ##########################################

  /**
   * @return bool
   */
  public function hasAuth()
  {
    return FALSE;
  }

  // ##########################################

  /**
   * @return array|bool
   */
  public function getValidServices()
  {
    return array(
      'Web.Base.hello',
      'Web.Base.getUsernameById',
    );
  }
}
```

Leaving inheritence and interface implementation aside: what do we see here?

We declare by ```isEnabled()``` that the service is enabled by simply returning ```TRUE```. Anything else would reject any incoming request for the service. Another interesting method is ```hasAuth()```. Similar to the prior method is authentication either on/off by returning ```TRUE || FALSE```. For now lets assume we have no authentication and lets have a look at ```getValidServices()```. This method returns an array of permitted service requests. Each string is made up of a ```API domain``` (e.g. Web), a ```Service class name``` (e.g. Base) and a ```service class method name```. All parts are separated by a dot.

<a name="jsonrpc-setup-auth"></a>
### 2.2.2. Authentication
Coming back to the above mentioned authentication. If ```hasAuth()``` would return ```TRUE``` the server expects an authentication class within the same directory as the gateway itsself ```/server/App/Api/Web/Auth.php```:

```php
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
```

The authentication class requires at least one method named ```init()```. It can hold any number of parameters as long as they are part of the incoming request parameters. Within this method any type of validation can be run. The server expects ```TRUE``` for a granted access and ```FALSE``` for a failed authentication.

<a name="jsonrpc-setup-service"></a>
### 2.2.3. Service class
In case the gateway is enabled, we pass the authentication (if given) and the incoming service api request matches our valid service list the request will make its way in to the requested service class.

Lets assume the following request has been sent ```Web.Base.hello``` which would reach the following service class ```/server/App/Api/V1/Web/Service/BaseService.php```:

```php
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
   * @param $userId
   * @return string
   */
  public function getUsernameById($userId)
  {
    $username = (new UserManager())->getUsername($userId);

    return $username;
  }
}
```

A service class marks the end of our JSON-RPC server. Our server waits now for a response from the called service class method in order to hand this response back to the client. In our example the server runs the method ```hello()``` which returns a string. Our client receives ```Hello!``` as response.

All other work, for instance a call to our database, should be handled within another class - the so called ```Manager class```. Only the result should be given back to our service class so that it can be returned to the client. ```Service classes``` are __slim and stupid__ while ```Manager classes``` __do all the work and magic__ behind the scenes. This should be always remembered while it supports reusibility of code.

The second method shows you the described technique. The server receives a request ```Web.Base.getUsernameById``` with a ```userId``` as parameter. The method takes the parameter and passes it on to a ```UserManager``` class which will fetch the ```username```. How all this happens doesn't matter to the ```Service``` class. All that matters is the username.

Here you see an example skeleton of the mentioned ```UserManager```:

```php
namespace App\Manager;

class UserManager
{
  /**
   * @param $id
   * @return string
   */
  public function getUsername($id)
  {
    $username = NULL;

    // do some work and return ...
    // return $username;

    // fake return
    return 'Hansi';
  }
}
```

<a name="jsonrpc-setup-bootstrap"></a>
### 2.2.4. Service Bootstrap
Cool, so what's left?

The only thing left to complete our server is a bootstrap file which is used as single-entry-point for a domain service. All what we need is a publically available file which connects to our prior created service gateway ```/server/public/api/web/index.php```:

```php
require __DIR__ . '/../../../../vendor/autoload.php';
$gtw = new App\Api\Web\Gateway();
```

What does this file? It loads composer's autoloader which in turn enables us to autoload our gateway class. As soon as the gateway is loaded the server reads the incoming request and the above mentioned processed begin to unfold.

<a name="jsonrpc-setup-client"></a>
## 2.3. Client
Now that the server is done we need a client which can talk to our service. I wrote a small class called [JSONRPC_CURL](https://github.com/fightbulc/jsonrpc_curl) which makes the request pretty simple. You installed it already at the beginning as it's part of our test folder package.

Our client file can be found here ```/client/request_server_testing.php```.

To go along with our described example the following code will call our service ```Web.Base.hello```. Make sure that __$urlServiceGateway__ holds the correct url to your server:

```php
// url to server gateway
$urlServiceGateway = 'http://localhost/opensource/server/simplon/simplon_jr/test/server/public';

// ############################################

// send request
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')    // server url
  ->setId(1)                                    // request ID (important for batch/async)
  ->setMethod('Web.Base.hello')                 // requested service
  ->send();                                     // send request

// dump response
var_dump($response); // should print: string(6) "Hello!"
```

And since we talked about the ```UserManager``` class here an example:

```php
// url to server gateway
$urlServiceGateway = 'http://localhost/opensource/server/simplon/simplon_jr/test/server/public';

// ############################################

// send request
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')
  ->setId(1)
  ->setMethod('Web.Base.getUsernameById')
  ->setData(['id' => 35])
  ->send();

// dump response
var_dump($response); // prints: Hansi
```

<a name="jsonrpc-setup-client"></a>
## 2.4. Authenticated request
At last here is an example for a authenticated request. Make sure within ```hasAuth()``` within your [gateway class](#jsonrpc-setup-gateway) returns ```TRUE```. This should run each request through our [Auth class](#jsonrpc-setup-auth).

Now lets add the necessary user data to our client:

```php
// url to server gateway
$urlServiceGateway = 'http://localhost/opensource/server/simplon/simplon_jr/test/server/public';

// ############################################

// auth data
$data = [
  'user' => 'admin',
  'pass' => '123456',
];

// send request
$response = (new JsonRpcCurl())
  ->setUrl($urlServiceGateway . '/api/web/')    // server url
  ->setId(1)                                    // request ID (important for batch/async)
  ->setMethod('Web.Base.hello')                 // requested service
  ->setData($data)                              // holds auth data
  ->send();                                     // send request

// dump response
var_dump($response); // should print: string(6) "Hello!"
```

Missing the required auth data or passing the wrong data will not get you anywhere. Try it out.


