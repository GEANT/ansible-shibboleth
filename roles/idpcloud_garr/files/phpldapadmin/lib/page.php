<?php

class page extends pageOrig {

        public function __construct($index=null) {
                parent::__construct($index);
                parent::head_add("<script type='text/javascript' src='".JSDIR."jquery-1.9.1.min.js'></script>");
                parent::head_add("<script type='text/javascript'>
               var userLang = '" . getenv('LANG') . "';

               if (userLang == \"\") {
                  userLang = navigator.language || navigator.userLanguage;
               }
               </script>");
            parent::head_add("<script type='text/javascript' src='".JSDIR."homeJavascript.js'></script>");

                foreach ($_SESSION[APPCONFIG]->getServerList() as $index => $server) {
                        foreach ($server->getBaseDN() as $tmp => $base) {
                                parent::head_add("<meta name='peoplebase' value='".$base."' />");
                        }
                }
        }
}

?>
