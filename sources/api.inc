<?php
/*  
    Yet another PHP interface to CouchDB
    Copyright (C) 2012  Arthur Tumanyan <arthurtumanyan@yahoo.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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
