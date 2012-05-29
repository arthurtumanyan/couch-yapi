<?php
/*
    Couch-yapi is a Yet Another aPI for CouchDB written in PHP
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

require_once 'sources/http.php';
require_once 'sources/config.php';
require_once 'sources/db.php';
require_once 'sources/document.php';

$http = new Http();
$config = new Config();
$db = new DB();
$document = new Document();

$http->SetCouchHost('couch.host.com');
$http->SetCouchPort(80);
$http->SetCouchUser('fololo');
$http->SetCouchPassword('pass');
$http->Init();

$http->SetReplicaSrc('http://admin:password@localhost:5984/my');
$http->SetReplicaDst('https://admin:password@user.cloudant.com/my');
//
//  Function descriptions according CouchDB reference manual
//
print_r($http->HttpGetRoot());  //Returns MOTD and version

//print_r($http->HttpGetFavicon());   //Special path for providing a site icon
print_r($http->HttpGetAllDbs());    //Returns a list of all databases on the specified server
print_r($http->HttpGetActiveTasks());   //Returns a list of running tasks
//print_r($http->HttpReplicate());    // Start or cancel replications
print_r($http->HttpGetUUIDs()); // Returns a list of generated UUIDs
//print_r($http->HttpGetStat());  // Returns server statistics
//print_r($http->HttpGetLog());   // Returns the tail of the server's log file, requires admin privileges
//print_r($http->HttpRestart());  // Restart the server, requires admin privileges


?>
