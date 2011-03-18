<?php

  class jpEmail extends jpMail {

    public function send() {

      if (SF_ENVIRONMENT == 'prod') 
        parent::send();
      else { 
        $dir = SF_ROOT_DIR.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.'mail';
        if (!is_dir($dir))
        mkdir($dir);

        $base = $dir.DIRECTORY_SEPARATOR.time();
        $i = 1;
        while(true) {
          $filename = "$base-($i).eml";

          if (is_file($filename))
            $i++;
          else
            break;
        }

        $fout = fopen($filename, "w");
        if (!$fout)
          throw new sfActionException('Cant open file '.$filename.' for writing');
    
        fwrite($fout, $this->getRawHeader());
        fwrite($fout, "\n\n");
        fwrite($fout, "Subject: ". mb_decode_mimeheader($this->getSubject()));
        fwrite($fout, "\n");
        fwrite($fout, mb_convert_encoding($this->getBody(),"UTF-8","JIS"));

        fclose ($fout);
      }
    }

  }
