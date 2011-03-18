<?php

/**
 * jpEmail actions.
 *
 * @package    jpEmail
 * @author     Voznyak Nazar <voznyaknazar@gmail.com>
 * @website    http://narkozateam.com
 */

class jpEmailActions extends sfActions
{

  public function executeIndex(){
    if ($files = sfFinder::type('file')
        ->name("*.eml")->relative()
        ->prune('om')->ignore_version_control()->in(SF_ROOT_DIR.'/log/mail')){
        sort($files);
        $this->path = SF_ROOT_DIR.'/log/mail';
        $this->files = array_reverse($files);
    }    
  }
  protected function retrieveFile(){
    if(!$filename
       = str_replace('%%', '.',
                     urldecode($this->getRequestParameter('filename')))){
      return false;
    }    
    $file = realpath(SF_ROOT_DIR.'/log/mail/'.$filename);
    $this->logMessage($file, 'debug');
    if(!(0 === strpos($file, SF_ROOT_DIR) && file_exists($file))){
      return false;
    }
    $this->filename = $filename;
    $file = file_get_contents($file);

    $file = '<pre>'.htmlentities($file, ENT_QUOTES, 'UTF-8').'</pre>';
    $file = preg_replace("|https?://[-a-zA-Z0-9~!@#&%^*(),./=_$:]+|","<a href=\"\${0}\">\${0}</a>",$file);
    
    $this->file = $file;
    
    return true;
  }
  public function executeShowFile(){
    $this->forward404Unless($this->retrieveFile());
  }
}
