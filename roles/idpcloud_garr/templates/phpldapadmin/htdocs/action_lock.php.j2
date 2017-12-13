<?php
session_start();

function checkPOSTvalue($key, $regexp, $showvalue=false) {
   if (!isset($_POST[$key])) return "";

        $tmpvalue = $_POST[$key];
        if (preg_match($regexp, $tmpvalue)) return $tmpvalue;

   $jsonout["status"] = "ko";
   $jsonout["error"] = "An error occurred during LDAP connection.";

        if ($showvalue) $jsonout["error"] = "Error with the $key paramenter provided: " . $_POST[$key];
        else $jsonout["error"] = "Error with the $key parameter provided.";

   exit(json_encode($jsonout));
}

$text_regexp = '/^[a-zA-Z\d\W_]*$/';
$date_regexp = '/^[0-9]{14}Z$/';

$action = checkPOSTvalue('action', $text_regexp, true);
$userid = checkPOSTvalue('userid', $text_regexp, true);
$dateval = checkPOSTvalue('dateval', $text_regexp, true);

$jsonout["status"] = "ko";
$jsonout["error"] = "An error occurred during LDAP connection.";

$ds = ldap_connect("localhost", 389);

if ($ds) {
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        $connuser = "{{ pla['ldap']['root_dn'] }}";
        $connpasswd = "{{ pla['ldap']['root_pw'] }}";
        $r = ldap_bind($ds, $connuser, $connpasswd);

        if($r) {
      $result = True;

      switch ($action) {
         case "lock":
                     $userdata = array("pwdAccountLockedTime" => array(0 => "000001010000Z"));
            break;
         case "unlock":
                     $userdata = array("pwdAccountLockedTime" => array());
            break;
         case "setdate":
                     $userdata = array("schacExpiryDate" => array(0 => $dateval));
            break;
         case "removedate": 
                     $userdata = array("schacExpiryDate" => array());
            break;
         default:
            $jsonout["error"] = "Error: requested action is not valid.";
            exit(json_encode($jsonout));
      }

                $user_dn = "uid=" . $userid . ",ou=people,{{ pla['ldap']['basedn'] }}";

                if ($action == "lock" || $action == "setdate") {
                         $result = ldap_mod_add($ds, $user_dn, $userdata);
                          if (!$result) {
                                $result = ldap_mod_replace($ds, $user_dn, $userdata);
                        }
                 }
                  else {
                        $result = ldap_mod_del($ds, $user_dn, $userdata);
                 }

                if ($result)  {
                          $jsonout["status"] = "ok";
                         $jsonout["error"] = "";
                }
                  else {
                         $jsonout["error"] = "An error occurred during the user lock/unlock.";
                }
   }
}

echo json_encode($jsonout);
?>
