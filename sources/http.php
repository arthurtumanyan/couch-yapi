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
require_once 'api.php';

class Http extends CouchAPI {

    private $conHandle;
    private $root;
    /* Use complete url! Please, specify username/password if any exists
     * example: http://admin:password@127.0.0.1:5984/example-database
     */
    private $replicaSrc; /* Complete URL: http://admin:password@127.0.0.1:5984/source-database */
    private $replicaDst; /* Complete URL: http://admin:password@127.0.0.1:5984/destination-database */
    private $headers;

    //
    public function Init() {
        parent::Init();
        $this->conHandle = parent::GetHandle();
        $this->root = parent::GetRoot();
    }
    
    public function SetReplicaSrc($src)
    {
        if(filter_var($src,FILTER_VALIDATE_URL))
        {
            $this->replicaSrc = $src;
        }
    }
    
    public function GetReplicaSrc()
    {
        return $this->replicaSrc;
    }
    
    public function SetReplicaDst($dst)
    {
      if(filter_var($dst,FILTER_VALIDATE_URL))
        {
            $this->replicaDst = $dst;
        }  
    }
    
    public function GetReplicaDst()
    {
        return $this->replicaDst;
    }

    public function HttpGetRoot() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root);
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

    public function HttpGetFavicon() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . 'favicon.ico');
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return $return;
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }

    public function HttpGetAllDbs() {
        
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_all_dbs');
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

    public function HttpGetActiveTasks() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_active_tasks');
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

    public function HttpReplicate(
                    $create = false,
                    $continuous = false, 
                    $cancel = false,
                    $extra = array(
                        'connection_timeout' => 60000,
                        'retries_per_request' => 20,
                        'http_connections' => 30
                    )) {
        
        $this->headers[] = 'Content-Type: application/json;';
        
        $pattern = '"source":"%s", "target":"%s"';
        
        if(isset($extra))
        {
            foreach($extra as $key => $value)
            {
                $pattern .= sprintf(', "%s":%d',$key,intval($value));
            }
        }
        
        if($continuous)
        {
            $pattern .= ', "continuous":true';
        }
        
        if($cancel)
        {
            $pattern .= ', "cancel":true';
        }
        
        if($create)
        {
            $pattern .=', "create_target":true';
        }
        if(parent::UseProxy())
        {
            $pattern .= ', "proxy":"%s"';
        }
        $pattern = '{'.$pattern.'}';
        
        $postFields = sprintf($pattern,
                $this->GetReplicaSrc(),
                $this->GetReplicaDst(),
                $this->GetProxyURL()
                );
        
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_replicate');
        curl_setopt($this->conHandle, CURLOPT_HTTPHEADER, $this->headers); 
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->conHandle, CURLOPT_POSTFIELDS, $postFields);
        
        
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }

    public function HttpGetUUIDs() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_uuids');
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

    public function HttpGetStat() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_stats');
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

    public function HttpGetLog($bytes = 1000, $offset = 0) {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_log?bytes=' . $bytes . '&offset=' . $offset);
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'GET');
        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return $return;
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }

    public function HttpRestart() {
        curl_setopt($this->conHandle, CURLOPT_URL, $this->root . '_restart');
        curl_setopt($this->conHandle, CURLOPT_HEADER, 0);
        curl_setopt($this->conHandle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->conHandle, CURLOPT_USERPWD, parent::GetCouchUSer() . ':' . parent::GetCouchPasswd());

        $return = curl_exec($this->conHandle);
        $info = curl_getinfo($this->conHandle);

        if ($info['http_code'] == 200) {
            return json_decode($return, true);
        } else {
            $this->errno = $info['http_code'];
            return false;
        }
    }

}

/* CouchAPI::Http */
?>
