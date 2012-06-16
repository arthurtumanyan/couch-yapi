<?php

error_reporting(E_ALL);
require_once 'error.php';

if(!extension_loaded('curl'))
{
    trigger_error("Curl extension not loaded");
}

if(!extension_loaded('json'))
{
    trigger_error("JSON extension not loaded");
}

        define('COUCHDB_HOST','127.0.0.1');
        define('COUCHDB_POST',5984);
        
      
 // Error messages
        
        define('ERR_INVALID_FQDN_IP','Invalid FQDN/IP specified');
        define('ERR_INVALID_PORT','Invalid PORT specified');
        define('ERR_EMPTY_USER','Empty username');
        define('ERR_EMPTY_PWD','Empty password');
        define('ERR_NOT_BOOL','Not a boolean value');
        define('ERR_AUTH_REQUIRED','Auth required');
        define('ERR_NOT_INT','Not an integer');
        
        
        
        
?>
