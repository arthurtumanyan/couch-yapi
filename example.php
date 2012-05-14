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
$http->SetReplicaDst('https://admin:password@user.cloudant.com/my');

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
$arr = array('1-dab0468f9f2a60a13dc37858868cdfff');
print_r($db->PurgeDocument('my','hello_matax',$arr));
//$document->SetCouchPort(5984);
//$document->SetUseSecure(true);
$document->SetUseAuth(true);
$document->SetCouchUser('admin');
$document->SetCouchPassword('password');
$document->Init();


//$data = array('hello' => array('God','bless','Armenia',array('bobo' => 'gigi')));
$data = 'madonna mia';
//print_r($document->CreateDoc('my', 'hello_matax3', $data));
//print_r($document->DeleteDoc('my','hello_world', '1-b664f71c5a228fd6107d4c48347a0003'));
//print_r($document->CopyDoc('my', 'hello_matax', 'matax'));
//print_r($document->PutAttachment('my','hello_matax','/home/freeman/replica.sh','1-967a00dff5e02add41819138abb3284d'));
//file_put_contents('nkar.gif', $document->GetAttachment('my', 'kapan.tv', 'sositv.gif'));
//print_r($document->DeleteAttachment('my', 'myfirstfile', 'replica.sh', '2-4b4e07761732eb6b90e8e165dd3b5d0d'));
?>
