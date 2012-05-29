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

class Document extends CouchAPI
{
   private $conHandle;
    private $root;
    
     public function Init() {
        parent::Init();
        $this->conHandle = parent::GetHandle();
        $this->root = parent::GetRoot();
    }
    
    public function CreateDoc($database,$doc_id,$data,$update = false,$rev = '')
    {
        if(!empty($doc_id) && !empty($database))
        {
        $d['_id'] = $doc_id;
        if($update && !empty($rev))
        {
            $d['_rev'] = $rev;
        }
        $d['data'] = $data;
        
        ksort($d);
        $postField = json_encode($d);
        }else {
            return false;
        }
        $headers[] = 'Content-Length: '.  strlen($postField);
        $headers[] = 'Content-Type: application/json';
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root .$database.'/');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->conHandle, CURLOPT_POSTFIELDS, $postField);
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }
    
    public function RetrieveDoc($database,$doc_id,$revsInfo = false,$rev = '')
    {
        if(empty($database) || empty($doc_id))
        {
            return false;
        }
        $url = $this->root.'/'.$database.'/'.$doc_id;
        if($revsInfo)
        {
            $url .= '?revs_info=true';
        }
        if(!$revsInfo && !empty($rev))
        {
            $url .= '?rev='.$rev;
        }
        curl_setopt($this->conHandle, CURLOPT_URL, $url);
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
    
    public function ModifyDoc($database,$doc_id,$data,$rev)
    {
        return $this->CreateDoc($database, $doc_id, $data, true, $rev);
    }
    
    public function DeleteDoc($database,$doc_id,$rev)
    {
        if(empty($database) || empty($doc_id) || empty($rev))
        {
            return false;
        }

        $url = $database.'/'.$doc_id.'?rev='.$rev;
        return parent::SendRequest('DELETE',$url);
    }
    
    public function CopyDoc($db,$src,$dst,$rev = '')
    {
        if(empty($db) || empty($src) || empty($dst))
        {
            return false;
        }
        $path = '/'.$db.'/'.$src;
        $data = 'Destination: '.$dst;
        if(!empty($rev))
        {
            $data .= '?rev='.$rev;
        }
        return parent::SendRequest('COPY', $path, $data);
    }
    
    public function GetAttachment($db,$document,$attachment)
    {
        if(empty($db) || empty($document) || empty($attachment))
        {
            return false;
        }

        $url = '/'.$db.'/'.$document.'/'.$attachment;
        return parent::SendRequest('GET',$url);
    }
    
    public function PutAttachment($db,$document,$attachment,$rev)
    {

        if(empty($db) || empty($document) || empty($attachment) || empty($rev))
        {
            return false;
        }
        if(!file_exists($attachment))
        {
            return false;
        }
        $path = $db.'/'.$document.'/'.basename($attachment);
        $data = base64_encode(file_get_contents($attachment));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $attachment);
        if(!empty($rev))
        {
            $path .= '?rev='.$rev;
        }
        return parent::SendRequest('PUT', $path, $data,$mime);
    }
    
    public function DeleteAttachment($database,$doc_id,$attachment,$rev)
    {
        if(empty($database) || empty($doc_id) || empty($rev))
        {
            return false;
        }

        $url = $database.'/'.$doc_id.'/'.$attachment.'?rev='.$rev;
        return parent::SendRequest('DELETE',$url);
    }
        
} /* CouchAPI::Documents */

?>
