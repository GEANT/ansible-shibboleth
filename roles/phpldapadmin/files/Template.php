<?php
/**
 * Classes and functions for the template engine.
 *
 * Templates are either:
 * + Creating or Editing, (a Container or DN passed to the object)
 * + A predefined template, or a default template (template ID passed to the object)
 *
 * The template object will know which attributes are mandatory (MUST
 * attributes) and which attributes are optional (MAY attributes). It will also
 * contain a list of optional attributes. These are attributes that the schema
 * will allow data for (they are MAY attributes), but the template has not
 * included a definition for them.
 *
 * The template object will be invalidated if it does contain the necessary
 * items (objectClass, MUST attributes, etc) to make a successful LDAP update.
 *
 * @author The phpLDAPadmin development team
 * @package phpLDAPadmin
 */

/**
 * Template Class
 *
 * @package phpLDAPadmin
 * @subpackage Templates
 * @todo RDN attributes should be treated as MUST attributes even though the schema marks them as MAY
 * @todo RDN attributes need to be checked that are included in the schema, otherwise mark it is invalid
 * @todo askcontainer is no longer used?
 */
class Template extends TemplateOrig {
        public function __construct($server_id,$name=null,$filename=null,$type=null,$id=null) {
                parent::__construct($server_id,$name,$filename,$type,$id);
        }

        /**
         * Return an array, that can be passed to ldap_add().
         * Attributes with empty values will be excluded.
         */
        public function getLDAPadd($attrsOnly=false) {
                $return = parent::getLDAPadd($attrsOnly);

                if ($attrsOnly) {
                        $server = $this->getServer();
                        $attribute_factory = new AttributeFactory();
                        $attribute = $attribute_factory->newAttribute('pwdReset',array('values'=>array('TRUE')),$server->getIndex(),null);

                        $return['pwdReset'] = $attribute;
                } else {
                        $return['pwdReset'] = 'TRUE';
                }

                return $return;
        }
}
?>

