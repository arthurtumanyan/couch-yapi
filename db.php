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
require_once 'api.php';

class DB extends CouchAPI
{
    private $conHandle;
    private $root;
    
     public function Init() {
        parent::Init();
        $this->conHandle = parent::GetHandle();
        $this->root = parent::GetRoot();
    }
    
    public function GetDBInfo($database)
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database);
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function CreateDB($database)
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database);
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function DeleteDB($database)
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database);
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function GetDBChanges($database)
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/_changes');
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function CompactDB($database)
    {
        $headers[] = 'Content-Type: application/json;';
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/_compact');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function ViewCleanup($database)
    {
        $headers[] = 'Content-Type: application/json;';
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/_view_cleanup');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function TempView($database,$map = '')
    {
        if(empty($map))
        {
            $map = '{ "map" : "function(doc) {  emit( doc._id, doc._rev );  }" }';
        }
        $headers[] = 'Content-Type: application/json;';
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/_temp_view');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->conHandle, CURLOPT_POSTFIELDS, $map);
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function FullCommit()
    {
        $headers[] = 'Content-Type: application/json;';
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .'recipes/_ensure_full_commit');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function PurgeDocument($database,$doc_id,$revs = array())
    {
       $content = '';
       $headers[] = 'Content-Type: application/json;';
       if(!empty($database) && !empty($doc_id) && is_array($revs))
        {
            $content = json_encode(array($doc_id => $revs));
        }else{
            return false;
        }

        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/_purge');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->conHandle, CURLOPT_POSTFIELDS, $content);
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function GetAllDocsView()
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . 'recipes/_all_docs');
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
   
    
} /* CouchAPI::DB */

?>
