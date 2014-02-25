<?php

    namespace Simplon\Jr;

    class ErrorCodesConstants
    {
        CONST SIMPLON_JR_ERROR_CODE = 30000;

        CONST INVALID_JSON_REQUEST_MESSAGE = 'Invalid JSON-RPC request';
        CONST INVALID_JSON_REQUEST_SUBCODE = 1;

        CONST GATEWAY_ACCESS_DENIED_MESSAGE = 'Service Gateway access is not permitted';
        CONST GATEWAY_ACCESS_DENIED_SUBCODE = 2;

        CONST SERVICE_REQUEST_DENIED_MESSAGE = 'Service Request is not permitted';
        CONST SERVICE_REQUEST_DENIED_SUBCODE = 3;

        CONST AUTH_FAILED_MESSAGE = 'Gateway authentication failed';
        CONST AUTH_FAILED_SUBCODE = 4;

        CONST SERVICE_METHOD_MISSING_MESSAGE = 'Service method does not exist. Make sure you have the correct name (case sensitive).';
        CONST SERVICE_METHOD_MISSING_SUBCODE = 5;

        CONST SERVICE_METHOD_PARAMETERS_MISSING_MESSAGE = 'Service method misses the following case-sensitive parameters: {{parameters}}';
        CONST SERVICE_METHOD_PARAMETERS_MISSING_SUBCODE = 6;
    }