<?php
error_reporting(E_ALL);

require_once 'sources/http.php';
require_once 'sources/config.php';
require_once 'sources/db.php';
require_once 'sources/document.php';

//$couch = new CouchAPI();
$http = new Http();
$config = new Config();
$db = new DB();
$document = new Document();

$http->SetReplicaSrc('http://admin:password@localhost:5984/my');
$http->SetReplicaDst('https://arthurtumanyan:s1322972@arthurtumanyan.cloudant.com/my');

//$http->SetUseSecure(true);
//$http->SetCouchHost('arthurtumanyan.cloudant.com');

$http->SetCouchUser('admin');
$http->SetCouchPassword('password');
$http->Init();

$config->SetUseAuth(true);
$config->SetCouchUser('admin');
$config->SetCouchPassword('password');
$config->Init();
//
//print_r($config->GetServerConfig());
//$config->GetConfigSection('httpd_design_handlers');
//$config->SetConfigItemValue('uuids','algorithm','"utc_random"');
//$config->DeleteConfigItemValue('uuids','algorithm');

$db->SetUseAuth(true);
$db->SetCouchUser('admin');
$db->SetCouchPassword('password');
$db->Init();


//print_r($db->ViewCleanup('my'));
//print_r($db->TempView('my'));
//print_r($db->DeleteDB('emerald2'));
//print_r($db->CompactDB('my'));
//print_r($db->TempView('my'));
//print_r($db->GetAllDocsView());
$arr = array('1-967a00dff5e02add41819138abb3284d','2-7051cbe5c8faecd085a3fa619e6e6337','3-825cb35de44c433bfb2df415563a19de');
//print_r($db->PurgeDocument('my2','04bfb3ada9c4b67bf58620a01a35a6c2',$arr));
//curl https://arthurtumanyan:password@arthurtumanyan.cloudant.com/
//$document->SetCouchHost('arthurtumanyan.cloudant.com');
//$document->SetCouchPort(5984);
//$document->SetUseSecure(true);
$document->SetUseAuth(true);
$document->SetCouchUser('admin');
$document->SetCouchPassword('password');
$document->Init();

    /*POST /somedatabase/ HTTP/1.0
Content-Length: 245
Content-Type: application/json

{
  "Subject":"I like Plankton",
  "Author":"Rusty",
  "PostedDate":"2006-08-15T17:30:12-04:00",
  "Tags":["plankton", "baseball", "decisions"],
  "Body":"I decided today that I don't like baseball. I like plankton."
}*/

$data = array('hello' => array('barev','es','dzer','mery'));
//print_r($document->CreateDoc('my', 'hello_matax', $data));
//print_r($document->DeleteDoc('my','hello_world', '1-b664f71c5a228fd6107d4c48347a0003'));
//print_r($document->CopyDoc('my', 'hello_matax', 'matax'));
//print_r($document->PutAttachment('my','myfirstfile','/home/freeman/replica.sh','1-967a00dff5e02add41819138abb3284d'));

print_r($document->DeleteAttachment('my', 'myfirstfile', 'replica.sh', '2-4b4e07761732eb6b90e8e165dd3b5d0d'));
?>
