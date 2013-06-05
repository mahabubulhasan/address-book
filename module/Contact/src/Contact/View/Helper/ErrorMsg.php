<?php
namespace Contact\View\Helper;

class ErrorMsg extends \Zend\View\Helper\AbstractHelper{
    public function __invoke($value){
        $msg = '';        
        if(count($value)>0){
            foreach($value as $err){                
                $msgs = $err->getMessages();
                foreach($msgs as $m){
                    $msg .= '<div>'.$m.'</div>';
                }
            }
        }
        return $msg;
    }
}

