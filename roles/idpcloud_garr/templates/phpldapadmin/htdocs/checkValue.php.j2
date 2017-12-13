<?php

        $field = trim($_POST['field']);
        $value = trim($_POST['value']);

        switch($field) {
                case 'uid':
                        $arr = checkUID($value);
                        break;
                default:
                        $arr['return'] = 1;
        }

        /* For the moment remote check of CF is disabled
        function checkCF($value) {
                $arr = array();
                if (strlen($value) == 16) $arr['return'] = 0;
                else $arr['return'] = 1;
                return $arr;
        }
        */

        function checkUID($value) { 
                $result = 2;
                $message = 'Unable to connect to LDAP server.';

                $ds = ldap_connect("{{ fqdn }}", 389);

                if ($ds) {
                        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

                        $r = ldap_bind($ds, "{{ pla['ldap']['root_dn'] }}", "{{ pla['ldap']['root_pw'] }}");
                        if($r) {
                                $filter = "uid=" . $value;
                                $ldap_result = ldap_list($ds, "ou=people,{{ pla['ldap']['basedn'] }}", $filter);
                                $info = ldap_get_entries($ds, $ldap_result);

                                if ($info['count'] == 0) {
                                        $result = 0;
                                        $message = '';
                                } else {
                                        $result = 1;
                                        $message = "Requested UID already in use.";
                                }

                                ldap_close($ds);
                        }
                }

            $arr = array();
            $arr['return'] = $result;
            $arr['message'] = $message;
            return $arr;
        }

        echo json_encode($arr);

?>
