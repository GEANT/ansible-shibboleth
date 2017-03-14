<?php 
// Copyright (c) 2011, SWITCH - Serving Swiss Universities

// This file is used to dynamically create the list of IdPs to be
// displayed for the WAYF/DS service based on the federation metadata.
// Configuration parameters are specified in config.php.
//
// The list of Identity Providers can also be updated by running the script
// readMetadata.php periodically as web server user, e.g. with a cron entry like:
// 5 * * * * /usr/bin/php readMetadata.php > /dev/null

// Init log file
openlog("idp-stats-readMetadata.php", LOG_PID | LOG_PERROR, LOG_LOCAL0);


// Make sure this script is not accessed directly
if(isRunViaCLI()){
        // Run in cli mode.
        // Could be used for testing purposes or to facilitate startup confiduration.
        // Results are dumped in $metadataIDPFile (see config.php)

        // Set dummy server name
        $_SERVER['SERVER_NAME'] = 'localhost';

        // Load configuration files
        require('config.php');
        require_once('functions.php');

        // Set default config options
        initConfigOptions();

        // Load Identity Providers
        require($IDPConfigFile);

        if (
                   !file_exists($metadataFile)
                || trim(@file_get_contents($metadataFile)) == '') {
          exit ("Exiting: File ".$metadataFile." is empty or does not exist\n");
        }

        // Get an exclusive lock to generate our parsed IdP and SP files.
        if (($lockFp = fopen($metadataLockFile, 'a+')) === false) {
                $errorMsg = 'Could not open lock file '.$metadataLockFile;
                die($errorMsg);
        }
        if (flock($lockFp, LOCK_EX) === false) {
                $errorMsg = 'Could not lock file '.$metadataLockFile;
                die($errorMsg);
        }

        echo 'Parsing metadata file '.$metadataFile."\n";
        list($metadataIDProviders, $metadataSProviders) = parseMetadata($metadataFile, $defaultLanguage);

        // If $metadataIDProviders is not FALSE, dump results in $metadataIDPFile.
        if(is_array($metadataIDProviders)){

                echo 'Dumping parsed Identity Providers to file '.$metadataIDPFile."\n";
                dumpFile($metadataIDPFile, $metadataIDProviders, 'metadataIDProviders');
        }
        // If $metadataSProviders is not FALSE, dump results in $metadataSPFile.
        if(is_array($metadataSProviders)){

                echo 'Dumping parsed Service Providers to file '.$metadataSPFile."\n";
                dumpFile($metadataSPFile, $metadataSProviders, 'metadataSProviders');
        }

        // Release the lock, and close.
        flock($lockFp, LOCK_UN);
        fclose($lockFp);

        // If $metadataIDProviders is not FALSE, update $IDProviders and print the Identity Providers lists.
        if(is_array($metadataIDProviders)){

                echo 'Merging parsed Identity Providers with data from file '.$IDProviders."\n";
                $IDProviders = mergeInfo($IDProviders, $metadataIDProviders, $SAML2MetaOverLocalConf, $includeLocalConfEntries);

                echo "Printing parsed Identity Providers:\n";
                print_r($metadataIDProviders);

                echo "Printing effective Identity Providers:\n";
                print_r($IDProviders);
        }

        // If $metadataSProviders is not FALSE, update $SProviders and print the list.
        if(is_array($metadataSProviders)){

                // Fow now copy the array by reference
                $SProviders = &$metadataSProviders;

                echo "Printing parsed Service Providers:\n";
                print_r($metadataSProviders);
        }


} elseif (isRunViaInclude()) {

        // Open the metadata lock file.
        if (($lockFp = fopen($metadataLockFile, 'a+')) === false) {
                $errorMsg = 'Could not open lock file '.$metadataLockFile;
                syslog(LOG_ERR, $errorMsg);
        }

        // Run as included file
        if(!file_exists($metadataIDPFile) or filemtime($metadataFile) > filemtime($metadataIDPFile)){
                // Get an exclusive lock to regenerate the parsed files.
                if ($lockFp !== false) {
                        if (flock($lockFp, LOCK_EX) === false) {
                                $errorMsg = 'Could not get exclusive lock on '.$metadataLockFile;
                                syslog(LOG_ERR, $errorMsg);
                        }
                }
                // Regenerate $metadataIDPFile.
                list($metadataIDProviders, $metadataSProviders) = parseMetadata($metadataFile, $defaultLanguage);

                // If $metadataIDProviders is not an array (parse error in metadata),
                // $IDProviders from $IDPConfigFile will be used.
                if(is_array($metadataIDProviders)){
                        dumpFile($metadataIDPFile, $metadataIDProviders, 'metadataIDProviders');
                        $IDProviders = mergeInfo($IDProviders, $metadataIDProviders, $SAML2MetaOverLocalConf, $includeLocalConfEntries);
                }

                if(is_array($metadataSProviders)){
                        dumpFile($metadataSPFile, $metadataSProviders, 'metadataSProviders');
                        require($metadataSPFile);
                }

                // Release the lock.
                if ($lockFp !== false) {
                        flock($lockFp, LOCK_UN);
                }

                                // Now merge IDPs from metadata and static file
                $IDProviders = mergeInfo($IDProviders, $metadataIDProviders, $SAML2MetaOverLocalConf, $includeLocalConfEntries);

                // Fow now copy the array by reference
                $SProviders = &$metadataSProviders;

        } elseif (file_exists($metadataIDPFile)){

                // Get a shared lock to read the IdP and SP files
                // generated from the metadata file.
                if ($lockFp !== false) {
                        if (flock($lockFp, LOCK_SH) === false) {
                                $errorMsg = 'Could not lock file '.$metadataLockFile;
                                syslog(LOG_ERR, $errorMsg);
                        }
                }

                // Read SP and IDP files generated with metadata
                require($metadataIDPFile);
                require($metadataSPFile);

                // Release the lock.
                if ($lockFp !== false) {
                        flock($lockFp, LOCK_UN);
                }

                // Now merge IDPs from metadata and static file
                $IDProviders = mergeInfo($IDProviders, $metadataIDProviders, $SAML2MetaOverLocalConf, $includeLocalConfEntries);

                // Fow now copy the array by reference
                $SProviders = &$metadataSProviders;
        }

        // Close the metadata lock file.
        if ($lockFp !== false) {
                fclose($lockFp);
        }

} else {
        exit('No direct script access allowed');
}

closelog();

/*****************************************************************************/
// Function parseMetadata, parses metadata file and returns Array($IdPs, SPs)  or
// Array(false, false) if error occurs while parsing metadata file
function parseMetadata($metadataFile, $defaultLanguage){

        if(!file_exists($metadataFile)){
                $errorMsg = 'File '.$metadataFile." does not exist";
                if (isRunViaCLI()){
                        echo $errorMsg."\n";
                } else {
                        syslog(LOG_ERR, $errorMsg);
                }
                return Array(false, false);
        }

        if(!is_readable($metadataFile)){
                $errorMsg = 'File '.$metadataFile." cannot be read due to insufficient permissions";
                if (isRunViaCLI()){
                        echo $errorMsg."\n";
                } else {
                        syslog(LOG_ERR, $errorMsg);
                }
                return Array(false, false);
        }

        $doc = new DOMDocument();
        if(!$doc->load( $metadataFile )){
                $errorMsg = 'Could not parse metadata file '.$metadataFile;
                if (isRunViaCLI()){
                        echo $errorMsg."\n";
                } else {
                        syslog(LOG_ERR, $errorMsg);
                }
                return Array(false, false);
        }

        $EntityDescriptors = $doc->getElementsByTagNameNS( 'urn:oasis:names:tc:SAML:2.0:metadata', 'EntityDescriptor' );

        $metadataIDProviders = Array();
        $metadataSProviders = Array();
        foreach( $EntityDescriptors as $EntityDescriptor ){
                $entityID = $EntityDescriptor->getAttribute('entityID');

                foreach($EntityDescriptor->childNodes as $RoleDescriptor) {
                        $nodeName = $RoleDescriptor->localName;
                        switch($nodeName){
                                case 'IDPSSODescriptor':
                                        $IDP = processIDPRoleDescriptor($RoleDescriptor);
                                        if ($IDP){
                                                $metadataIDProviders[$entityID] = $IDP;
                                        }
                                        break;
                                case 'SPSSODescriptor':
                                        $SP = processSPRoleDescriptor($RoleDescriptor);
                                        if ($SP){
                                                $metadataSProviders[$entityID] = $SP;
                                        } else {
                                                $errorMsg = "Failed to load SP with entityID $entityID from metadata file $metadataFile";
                                                if (isRunViaCLI()){
                                                        echo $errorMsg."\n";
                                                } else {
                                                        syslog(LOG_WARNING, $errorMsg);
                                                }
                                        }
                                        break;
                                default:
                        }
                }
        }


        // Output result
        $infoMsg = "Successfully parsed metadata file ".$metadataFile. ". Found ".count($metadataIDProviders)." IdPs and ".count($metadataSProviders)." SPs";
        if (isRunViaCLI()){
                echo $infoMsg."\n";
        } else {
                syslog(LOG_INFO, $infoMsg);
        }


        return Array($metadataIDProviders, $metadataSProviders);
}

/******************************************************************************/
// Is this script run in CLI mode
function isRunViaCLI(){
        return !isset($_SERVER['REMOTE_ADDR']);
}

/******************************************************************************/
// Is this script run in CLI mode
function isRunViaInclude(){
        return basename($_SERVER['SCRIPT_NAME']) != 'readMetadata.php';
}

/******************************************************************************/
// Processes an IDPRoleDescriptor XML node and returns an IDP entry or false if
// something went wrong
function processIDPRoleDescriptor($IDPRoleDescriptorNode){
        global $defaultLanguage;

        $IDP = Array();

        // Get SSO URL
        $SSOServices = $IDPRoleDescriptorNode->getElementsByTagNameNS( 'urn:oasis:names:tc:SAML:2.0:metadata', 'SingleSignOnService' );
        foreach( $SSOServices as $SSOService ){
                if ($SSOService->getAttribute('Binding') == 'urn:mace:shibboleth:1.0:profiles:AuthnRequest'){
                        $IDP['SSO'] =  $SSOService->getAttribute('Location');
                        break;
                } else if ($SSOService->getAttribute('Binding') == 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect'){
                        $IDP['SSO'] =  $SSOService->getAttribute('Location');
                        break;
                }
        }

        if (!isset($IDP['SSO'])){
                $IDP['SSO'] = 'https://no.saml1.or.saml2.sso.url.defined.com/error';
        }

        // First get MDUI name
        $MDUIDisplayNames = getMDUIDisplayNames($IDPRoleDescriptorNode);
        if (count($MDUIDisplayNames)){
                $IDP['Name'] = current($MDUIDisplayNames);
        }
        foreach ($MDUIDisplayNames as $lang => $value){
                $IDP[$lang]['Name'] = $value;
        }

        // Then try organization names
        if (empty($IDP['Name'])){
                $OrgnizationNames = getOrganizationNames($IDPRoleDescriptorNode);
                $IDP['Name'] = current($OrgnizationNames);

                foreach ($OrgnizationNames as $lang => $value){
                        $IDP[$lang]['Name'] = $value;
                }
        }

        // As last resort, use entityID
        if (empty($IDP['Name'])){
                $IDP['Name'] = $IDPRoleDescriptorNode->parentNode->getAttribute('entityID');
        }

        // Set default name
        if (isset($IDP[$defaultLanguage])){
                $IDP['Name'] = $IDP[$defaultLanguage]['Name'];
        } elseif (isset($IDP['en'])){
                $IDP['Name'] = $IDP['en']['Name'];
        }

        // Get supported protocols
        $protocols = $IDPRoleDescriptorNode->getAttribute('protocolSupportEnumeration');
        $IDP['Protocols'] = $protocols;

        // Get keywords
        $MDUIKeywords = getMDUIKeywords($IDPRoleDescriptorNode);
        foreach ($MDUIKeywords as $lang => $keywords){
                $IDP[$lang]['Keywords'] = $keywords;
        }

        return $IDP;
}

/******************************************************************************/
// Processes an SPRoleDescriptor XML node and returns an SP entry or false if
// something went wrong
function processSPRoleDescriptor($SPRoleDescriptorNode){
        global $defaultLanguage;

        $SP = Array();

        // Get <idpdisc:DiscoveryResponse> extensions
        $DResponses = $SPRoleDescriptorNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:profiles:SSO:idp-discovery-protocol', 'DiscoveryResponse');
        foreach( $DResponses as $DResponse ){
                if ($DResponse->getAttribute('Binding') == 'urn:oasis:names:tc:SAML:profiles:SSO:idp-discovery-protocol'){
                        $SP['DSURL'][] =  $DResponse->getAttribute('Location');
                }
        }

        // First get MDUI name
        $MDUIDisplayNames = getMDUIDisplayNames($SPRoleDescriptorNode);
        if (count($MDUIDisplayNames)){
                $SP['Name'] = current($MDUIDisplayNames);
        }
        foreach ($MDUIDisplayNames as $lang => $value){
                $SP[$lang]['Name'] = $value;
        }

        // Then try attribute consuming service
        if (empty($SP['Name'])){
                $ConsumingServiceNames = getAttributeConsumingServiceNames($SPRoleDescriptorNode);
                $SP['Name'] = current($ConsumingServiceNames);

                foreach ($ConsumingServiceNames as $lang => $value){
                        $SP[$lang]['Name'] = $value;
                }
        }

        // As last resort, use entityID
        if (empty($SP['Name'])){
                $SP['Name'] = $SPRoleDescriptorNode->parentNode->getAttribute('entityID');
        }

        // Set default name
        if (isset($SP[$defaultLanguage])){
                $SP['Name'] = $SP[$defaultLanguage]['Name'];
        } elseif (isset($SP['en'])){
                $SP['Name'] = $SP['en']['Name'];
        }

        // Get Assertion Consumer Services and store their hostnames
        $ACServices = $SPRoleDescriptorNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:metadata', 'AssertionConsumerService');
        foreach( $ACServices as $ACService ){
                $SP['ACURL'][] =  $ACService->getAttribute('Location');
        }

        // Get supported protocols
        $protocols = $SPRoleDescriptorNode->getAttribute('protocolSupportEnumeration');
        $SP['Protocols'] = $protocols;

        // Get keywords
        $MDUIKeywords = getMDUIKeywords($SPRoleDescriptorNode);
        foreach ($MDUIKeywords as $lang => $keywords){
                $SP[$lang]['Keywords'] = $keywords;
        }

        return $SP;
}

/******************************************************************************/
// Dump variable to a file
function dumpFile($dumpFile, $providers, $variableName){

        if(($fp = fopen($dumpFile, 'w')) !== false){

                fwrite($fp, "<?php\n\n");
                fwrite($fp, "// This file was automatically generated by readMetadata.php\n");
                fwrite($fp, "// Don't edit!\n\n");

                fwrite($fp, '$'.$variableName.' = ');
                fwrite($fp, var_export($providers,true));

                fwrite($fp, "\n?>");

                fclose($fp);
        } else {
                $errorMsg = 'Could not open file '.$dumpFile.' for writting';
                if (isRunViaCLI()){
                        echo $errorMsg."\n";
                } else {
                        syslog(LOG_ERR, $errorMsg);
                }
        }
}


/******************************************************************************/
// Function mergeInfo is used to create the effective $IDProviders array.
// For each IDP found in the metadata, merge the values from IDProvider.conf.php.
// If an IDP is found in IDProvider.conf as well as in metadata, use metadata
// information if $SAML2MetaOverLocalConf is true or else use IDProvider.conf data
function mergeInfo($IDProviders, $metadataIDProviders, $SAML2MetaOverLocalConf, $includeLocalConfEntries){

        // If $includeLocalConfEntries parameter is set to true, mergeInfo() will also consider IDPs
        // not listed in metadataIDProviders but defined in IDProviders file
        // This is required if you need to add local exceptions over the federation metadata
        $allIDPS = $metadataIDProviders;
        $mergedArray = Array();
        if ($includeLocalConfEntries) {
                  $allIDPS = array_merge($metadataIDProviders, $IDProviders);
        }

        foreach ($allIDPS as $allIDPsKey => $allIDPsEntry){
                if(isset($IDProviders[$allIDPsKey])){
                        // Entry exists also in local IDProviders.conf.php
                        if (isset($metadataIDProviders[$allIDPsKey]) && is_array($metadataIDProviders[$allIDPsKey])) {

                                // Remove IdP if there is a removal rule in local IDProviders.conf.php
                                if (!is_array($IDProviders[$allIDPsKey])){
                                        unset($metadataIDProviders[$allIDPsKey]);
                                        continue;
                                }

                                // Entry exists in both IDProviders sources and is an array
                                if($SAML2MetaOverLocalConf){
                                        // Metadata entry overwrite local conf
                                        $mergedArray[$allIDPsKey] = array_merge($IDProviders[$allIDPsKey], $metadataIDProviders[$allIDPsKey]);
                                } else {
                                        // Local conf overwrites metada entry
                                        $mergedArray[$allIDPsKey] = array_merge($metadataIDProviders[$allIDPsKey], $IDProviders[$allIDPsKey]);
                                }
                          } else {
                                        // Entry only exists in local IDProviders file
                                        $mergedArray[$allIDPsKey] = $IDProviders[$allIDPsKey];
                          }
                } else {
                        // Entry doesnt exist in in local IDProviders.conf.php
                        $mergedArray[$allIDPsKey] = $metadataIDProviders[$allIDPsKey];
                }
        }

        return $mergedArray;
}

/******************************************************************************/
// Get MD Display Names from RoleDescriptor
function getMDUIDisplayNames($RoleDescriptorNode){

        $Entity = Array();

        $MDUIDisplayNames = $RoleDescriptorNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:metadata:ui', 'DisplayName');
        foreach( $MDUIDisplayNames as $MDUIDisplayName ){
                $lang = $MDUIDisplayName->getAttributeNodeNS('http://www.w3.org/XML/1998/namespace', 'lang')->nodeValue;
                $Entity[$lang] = $MDUIDisplayName->nodeValue;
        }

        return $Entity;
}

/******************************************************************************/
// Get MD Keywords from RoleDescriptor
function getMDUIKeywords($RoleDescriptorNode){

        $Entity = Array();

        $MDUIKeywords = $RoleDescriptorNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:metadata:ui', 'Keywords');
        foreach( $MDUIKeywords as $MDUIKeywordEntry ){
                $lang = $MDUIKeywordEntry->getAttributeNodeNS('http://www.w3.org/XML/1998/namespace', 'lang')->nodeValue;
                $Entity[$lang] = $MDUIKeywordEntry->nodeValue;
        }

        return $Entity;
}
/******************************************************************************/
// Get Organization Names from RoleDescriptor
function getOrganizationNames($RoleDescriptorNode){

        $Entity = Array();

        $Orgnization = $RoleDescriptorNode->parentNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:metadata', 'Organization' )->item(0);
        if ($Orgnization){
                $DisplayNames = $Orgnization->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:metadata', 'OrganizationDisplayName');
                foreach ($DisplayNames as $DisplayName){
                        $lang = $DisplayName->getAttributeNodeNS('http://www.w3.org/XML/1998/namespace', 'lang')->nodeValue;
                        $Entity[$lang] = $DisplayName->nodeValue;
                }
        }

        return $Entity;
}


/******************************************************************************/
// Get Organization Names from RoleDescriptor
function getAttributeConsumingServiceNames($RoleDescriptorNode){

        $Entity = Array();

        $ServiceNames = $RoleDescriptorNode->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:metadata', 'ServiceName' );
        foreach ($ServiceNames as $ServiceName){
                $lang = $ServiceName->getAttributeNodeNS('http://www.w3.org/XML/1998/namespace', 'lang')->nodeValue;
                $Entity[$lang] = $ServiceName->nodeValue;
        }

        return $Entity;
}

?>
