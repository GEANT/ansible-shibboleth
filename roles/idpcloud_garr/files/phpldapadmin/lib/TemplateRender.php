<?php

class TemplateRender extends TemplateRenderOrig {

        protected function drawJavascript() {
         parent::drawJavaScript();
                // Custom Javascript include start
                printf('<script type="text/javascript" src="%slivevalidation_standalone.compressed.js"></script>',JSDIR);
                printf('<script type="text/javascript" src="%sformJavascript.js"></script>',JSDIR);
                // Custom Javascript include end
        }

        protected function drawValidateJavascriptAttribute($attribute,$component,$silence,$var_valid) {
                printf('var vals = getAttributeValues("new","%s");',$attribute->getName());
                echo 'if (vals.length <= 0) {';
                printf('%s = false;',$var_valid);
                //printf('alertError("%s: %s",%s);',_('This attribute is required'),$attribute->getFriendlyName(),$silence);
                echo '}';
                echo "\n";

                printf('var comp = getAttributeComponents("new","%s");',$attribute->getName());
                echo 'for (var i = 0; i < comp.length; i++) {';
                printf('comp[i].style.backgroundColor = "%s";',$var_valid ? 'white' : '#FFFFA0');
                echo '}';
        }

}

?>
