<?php

    namespace Simplon\Jr;

    class ErrorCodesConstants
    {
        CONST EXCEPTION_UNCAUGHT = 'JR000';

        CONST INVALID_JSON_REQUEST_MESSAGE = 'Invalid JSON-RPC request';
        CONST INVALID_JSON_REQUEST_CODE = 'JR001';

        CONST GATEWAY_ACCESS_DENIED_MESSAGE = 'Service Gateway access is not permitted';
        CONST GATEWAY_ACCESS_DENIED_CODE = 'JR002';

        CONST SERVICE_REQUEST_DENIED_MESSAGE = 'Service Request is not permitted';
        CONST SERVICE_REQUEST_DENIED_CODE = 'JR003';

        CONST AUTH_FAILED_MESSAGE = 'Gateway authentication failed';
        CONST AUTH_FAILED_CODE = 'JR004';

        CONST SERVICE_METHOD_MISSING_MESSAGE = 'Service method does not exist. Make sure you have the correct name (case sensitive).';
        CONST SERVICE_METHOD_MISSING_CODE = 'JR005';

        CONST SERVICE_METHOD_PARAMETERS_MISSING_MESSAGE = 'Service method misses the following case-sensitive parameters: {{parameters}}';
        CONST SERVICE_METHOD_PARAMETERS_MISSING_CODE = 'JR006';
    }