```
     _                 _                 _
 ___(_)_ __ ___  _ __ | | ___  _ __     (_)_ __
/ __| | '_ ` _ \| '_ \| |/ _ \| '_ \    | | '__|
\__ \ | | | | | | |_) | | (_) | | | |   | | |
|___/_|_| |_| |_| .__/|_|\___/|_| |_|  _/ |_|
                |_|                   |__/
```

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

-------------------------------------------------

# License

Cirrus is freely distributable under the terms of the MIT license.

Copyright (c) 2014 Tino Ehrich ([tino@bigpun.me](mailto:tino@bigpun.me))

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.