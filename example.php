<?php
/*  
    Yet another PHP interface to CouchDB
    Copyright (C) 2012  Arthur Tumanyan

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

$http->SetCouchHost('127.0.0.1');
$http->SetCouchPort(5984);
$http->SetCouchUser('user');
$http->SetCouchPassword('qwerty');
$http->UseAuth(true);
$http->Init();
//
$db->SetCouchHost('127.0.0.1');
$db->SetCouchPort(5984);
$db->SetCouchUser('user');
$db->SetCouchPassword('qwerty');
$db->UseAuth(true);
$db->Init();
//
$document->SetCouchHost('127.0.0.1');
$document->SetCouchPort(5984);
$document->SetCouchUser('user');
$document->SetCouchPassword('qwerty');
$document->UseAuth(true);
$document->Init();
//
$http->SetReplicaSrc('http://admin:password@localhost:5984/my');
$http->SetReplicaDst('https://admin:password@user.cloudant.com/my');
//
//  Function descriptions according CouchDB reference manual
//
//print_r($http->HttpGetRoot());  //Returns MOTD and version

//print_r($http->HttpGetFavicon());   //Special path for providing a site icon
//print_r($http->HttpGetAllDbs());    //Returns a list of all databases on the specified server
//print_r($http->HttpGetActiveTasks());   //Returns a list of running tasks
//print_r($http->HttpReplicate());    // Start or cancel replications
//print_r($http->HttpGetUUIDs()); // Returns a list of generated UUIDs
//print_r($http->HttpGetStat());  // Returns server statistics
//print_r($http->HttpGetLog());   // Returns the tail of the server's log file, requires admin privileges
//print_r($http->HttpRestart());  // Restart the server, requires admin privileges


$dbname = 'emerald2';
$doc_category = 'categories';
/*
echo 'Db created: ';
if(!$db->isDBExists($dbname)){
    if(false != ($ret = $db->CreateDB($dbname))){
        echo "Success!<br />";
    }else {
        echo "Fail!<br />";
        echo $db->errno."<br />";
        var_dump($ret);
    }
}
echo '<br />Doc exist: ';
$test = $db->isDBExists('test_suite_db');
$test2 = $document->isDocExists('test_suite_db','1');
var_dump($test2);
*/
$doc = $document->RetrieveDoc($dbname, $doc_category);
if(is_array($doc))
{
    $rev = $doc['_rev'];
    print_r($doc['id']);
    print_r($doc['parentid']);
    print_r($doc['text']);
}
$data = array(  'id' => array(1,2,3,4),
                'parentid' => array(1,1,3,4),
                'text' => array('Horses','Wives','Husbands','Pets'));
//echo json_encode($data);
//$document->ModifyDoc($dbname, $doc_category, $data, $rev);
?>
