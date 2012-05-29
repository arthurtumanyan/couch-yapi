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

class Config extends CouchAPI
{
    private $conHandle;
    private $root;
    private $headers;
    
    public function Init() {
        parent::Init();
        $this->conHandle = parent::GetHandle();
        $this->root = parent::GetRoot();
    }
    
    public function GetServerConfig()
    {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root.'_config');
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
    
    public function GetConfigSection($section)
    {
        if(!isset($section)|| empty($section))
        {
            return NULL;
        }
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root.'_config/'.$section);
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
    
    public function GetConfigItemValue($section,$key)
    {
        if( empty($section)||empty($key) )
        {
            return NULL;
        }
        
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root.'_config/'.$section.'/'.$key);
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
    
    public function SetConfigItemValue($section,$key,$value)
    {
        if( empty($section) || empty($key) || empty($value))
        {
            return NULL;
        }
        $path = sprintf("_config/%s/%s",$section,$key);
        return parent::SendRequest('PUT',$path, $value);
        
    }
    
    public function DeleteConfigItemValue($section,$key)
    {
        if( empty($section) || empty($key))
        {
            return NULL;
        }
        $path = sprintf("_config/%s/%s",$section,$key);
        return parent::SendRequest('DELETE',$path);
    }
       
    
} /* CouchAPI::Config */

?>
