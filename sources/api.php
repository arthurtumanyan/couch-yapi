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
include_once 'api.inc';
require_once 'error.php';

class CouchAPI
{
    private $couchDB_host;
    private $couchDB_port;
    private $couchDB_user;
    private $couchDB_passwd;
    private $proto;
    private $useSecure;
    private $useAuth;
    private $root;
    private $conHandle;
    //
    protected $errorstr;
    public $errno;
    private $error;
    private $timeout = 30;
    //
    protected $proxyAuthName;
    protected $proxyAuthPassword;
    protected $proxyHost;
    protected $proxyPort;
    protected $useProxy;
    
    public function __construct()
    {
        $this->error = new Cry();
        $this->useSecure = false;
        $this->useProxy = false;
        $this->useAuth = false;
                  
    }
    
    public function __destruct()
    {
      
    }
    
    public function SetTimeout($timeout)
    {
        if(filter_var($timeout,FILTER_VALIDATE_INT))
        {
            $this->timeout = $timeout;
        }else
        {
            $this->error->SetCryMessage(ERR_NOT_INT, 'error');
            $this->error->CryMessage();
        }
    }
    
    public function GetTimeout()
    {
        return $this->timeout;
    }
    
    public function GetProto()
    {
        return ($this->UseSecure())?"https":"http";
    }
    
    public function GetProxyURL()
    {
        if($this->UseProxy())
        {
            if(!empty($this->proxyAuthName) && !empty($this->proxyAuthPassword))
            {
                return sprintf("%s://%s:%s@%s:%d",
                        $this->GetProto(),
                        $this->GetProxyAuthName(),
                        $this->GetProxyAuthPasswd(),
                        $this->GetProxyHost(),
                        $this->GetProxyPort());
            }else{
             
                return sprintf("%s://%s:%d",
                        $this->GetProto(),
                        $this->GetProxyHost(),
                        $this->GetProxyPort());
            }
        }else return false;
    }
    
    public function SetProxyHost($host)
    {
        if(!isset($host) || empty($host))
        {
          $host = COUCHDB_HOST;
        }
        
        if(filter_var($host, FILTER_VALIDATE_IP)||filter_var(gethostbyname($host), FILTER_VALIDATE_IP))
            {
                $this->couchDB_host = $host;
            }
            else
            {
                $this->error->SetCryMessage(ERR_INVALID_FQDN_IP, 'error');
                $this->error->CryMessage();
            }
    }
    
    public function GetProxyHost()
    {
        return $this->proxyHost;
    }
    
    public function SetProxyPort($port)
    {
        if(!isset($port)||  empty($host))
        {
            $port = COUCHDB_POST;
        }
        if(filter_var($port,FILTER_VALIDATE_INT) && ($port > 0 && $port < 65535))
        {
            $this->couchDB_port = $port;
        }
        else
            {
                $this->error->SetCryMessage(ERR_INVALID_PORT, 'error');
                $this->error->CryMessage();
            }
    }
    
    public function GetProxyPort()
    {
        return $this->proxyPort;
    }

    public function SetProxyAuthName($authName)
    {
        if(isset($authName))
        {
           $this->proxyAuthName = $authName;
        }
    }
    
    public function GetProxyAuthName()
    {
        return $this->proxyAuthName;
    }


    public function SetProxyAuthPasswd($authPasswd)
    {
        if(isset($authPasswd))
        {
           $this->proxyAuthName = $authPasswd;
        }
    }
    
    public function GetProxyAuthPasswd()
    {
        return $this->proxyAuthPassword;
    }

    public function UseAuth()
    {
       return $this->useAuth;
    }
    public function UseProxy()
    {
        return $this->useProxy;
    }
    
    public function SetUseProxy($useProxy)
    {
        if(is_bool($useProxy))
        {
            $this->useProxy = $useProxy;
        }
        else
        {
        
            $this->error->SetCryMessage(ERR_NOT_BOOL, 'notice');
            $this->error->CryMessage(); 
        }
    }
    
     public function SetUseAuth($useAuth)
    {
        if(is_bool($useAuth))
        {   
            $this->useAuth = $useAuth;
        }
        else
        {
        
            $this->error->SetCryMessage(ERR_NOT_BOOL, 'notice');
            $this->error->CryMessage(); 
        }
    }
        
    protected function GetRoot()
    {
        return $this->root;
    }
    
    protected function GetHandle()
    {
        return $this->conHandle;
    }
    
    public function UseSecure()
    {
        return $this->useSecure;
    }
    
    public function SetUseSecure($secure)
    {
        if(is_bool($secure))
        {
            $this->useSecure = $secure;
        }
        else
        {
        
            $this->error->SetCryMessage(ERR_NOT_BOOL, 'notice');
            $this->error->CryMessage(); 
        }
    }
    public function SetCouchHost($host)
    {
        if(!isset($host) || empty($host))
        {
          $host = COUCHDB_HOST;
        }
        
        if(filter_var($host, FILTER_VALIDATE_IP)||filter_var(gethostbyname($host), FILTER_VALIDATE_IP))
            {
                $this->couchDB_host = $host;
            }
            else
            {
                $this->error->SetCryMessage(ERR_INVALID_FQDN_IP, 'error');
                $this->error->CryMessage();
            }
    }
    
    public function SetCouchPort($port)
    {
        if(!isset($port))
        {
            $port = COUCHDB_POST;
        }
        if(is_integer($port) && ($port > 0 && $port < 65535))
        {
            $this->couchDB_port = $port;
        }
        else
            {
                $this->error->SetCryMessage(ERR_INVALID_PORT, 'error');
                $this->error->CryMessage();
            }
            
         }
    
    public function SetCouchUser($username)
    {
        if(isset($username))
        {
            $this->couchDB_user = $username;
        }
        else
        {
            $this->error->SetCryMessage(ERR_EMPTY_USER, 'error');
            $this->error->CryMessage();
        }
    }
    
    public function GetCouchUser()
    {
        return $this->couchDB_user;
    }
    
    public function SetCouchPassword($password)
    {
         if(isset($password))
        {
            $this->couchDB_passwd = $password;
        }
        else
        {
            $this->error->SetCryMessage(ERR_EMPTY_PWD, 'error');
            $this->error->CryMessage();
        }
    }
    
    public function GetCouchDBHost()
    {
        return $this->couchDB_host;
    }
    
    public function GetCouchDBPort()
    {
        return $this->couchDB_port;
    }
    
    
    public function GetCouchPasswd()
    {
        return $this->couchDB_passwd;
    }
    
    private function GetRootURL()
    {
        if($this->UseAuth())
        {
            $this->root = sprintf("%s://%s:%s@%s:%d/",
                        $this->GetProto(),
                        $this->GetCouchUSer(),
                        $this->GetCouchPasswd(),
                        $this->GetCouchDBHost(),
                        $this->GetCouchDBPort()
                        );  
        }
            else
            {
                $this->root = sprintf("%s://%s:%d/",
                        $this->GetProto(),
                        $this->GetCouchDBHost(),
                        $this->GetCouchDBPort()
                        ); 
            }
        }

        public function SendRequest($method,$path,$data = '',$mime = 'application/json')
        {
            $retval = '';
            $host = sprintf("%s",$this->GetCouchDBHost());
            $auth = base64_encode($this->GetCouchUser().':'.$this->GetCouchPasswd());
            $handle = fsockopen($host,$this->GetCouchDBPort(),
                    $this->errno,
                    $this->errorstr,
                    $this->timeout
                    );
         
    if (!$handle) {
        $this->error->SetCryMessage($this->errorstr, 'error');
        $this->error->CryMessage();
    } else { 
            $out = "$method /$path HTTP/1.1\r\n";
            $out .= "Content-Type: $mime\r\n";
            $out .= "Connection: close\r\n";
            if(!empty($data))
            {
                $length = strlen($data);
                $out .= "Content-Length: $length\r\n";
            }
            
            if($this->UseAuth()){
                $out .= "Authorization: Basic $auth\r\n";
            }
            $out .= "\r\n";
            if(!empty($data))
            {
              $out .= "$data";
            }   

            $return = fwrite($handle, $out);
            
            while (!feof($handle)) {
                 $retval .= fgets($handle, 128);
            }
                fclose($handle);
            }
                if(false != $return)
                {
                    return $retval;
                }
            return false;
        }
        
    protected function addQuotes($word)
    {
        return (is_string($word))? sprintf("\"%s\"",$word):$word;
    }
    
    public function Init()
    {      
        if(empty($this->couchDB_host))
        {
            $this->SetCouchHost('');
        }
        
        if(empty($this->couchDB_port)){
            $this->SetCouchPort('');
        }
        
        $this->GetRootURL(); /* Must be before Connect */
        $this->conHandle = curl_init($this->root);
            if(FALSE !== $this->conHandle)
            {
                curl_setopt($this->conHandle, CURLOPT_RETURNTRANSFER, true);
            }
    }
}

?>
