<?php

require_once 'api.inc';

class Cry {
    
    private $LEVEL_ERROR = '<div style="border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #D8000C;background-color: #FFBABA;">%s</div>';
    private $LEVEL_WARNING = '<div style="border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #9F6000;background-color: #FEEFB3;">%s</div>' ;
    private $LEVEL_NOTICE = '<div style="border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #D8000C;background-color: #FFBABA;">%s</div>';
    private $level;
    private $message;
    
    
function __construct($msg = '',$level = 'ERROR')
{
    if(!empty($msg))
    {
        $this->CryMessage($msg, $level);
    }
}

public function SetCryMessage($msg,$level = 'ERROR')
{
    $this->level = $level;
    
    if('error' == strtolower($level))
    {
        $this->message = sprintf($this->LEVEL_ERROR,$msg);
    }
    elseif('warning' == strtolower($level))
    {
        $this->message = sprintf($this->LEVEL_WARNING,$msg);
    }
    elseif('notice' == strtolower($level))
    {
        $this->message = sprintf($this->LEVEL_NOTICE,$msg);
    }
    else
    {
        $this->message = sprintf($this->LEVEL_ERROR,$msg);
    }
}

public function CryMessage()
{
    echo $this->message;
    if('error' == strtolower($this->level))
    {
        exit();
    }
}
    
}

?>
