# ANSIBLE CODE TO INSTALL SHIBBOLETH PRODUCTS

## Requirements

* [Ansible](https://www.ansible.com/) <= 2.3.0
* [Shibboleth IdP source](https://shibboleth.net/downloads/identity-provider/latest/)

## Simple flow to install and configure a Shibboleth IdP

1. Move on the ```/opt``` directory and download the Ansible-Shibboleth code:
    * ```cd /opt/```
    * ```git clone https://github.com/malavolti/ansible-shibboleth.git ; cd /opt/ansible-shibboleth```

2. Create the right inventory file/files about your IdP servers by following the template provided:
    * ```inventories/development/development.ini``` for your development servers.
    * ```inventories/production/production.ini``` for your production servers.
    * ```inventories/test/test.ini``` for your test servers.

3. Create your ```.vault_pass.txt``` that contains the encryption password (this is needed ONLY when you use Ansible Vault):
    * ```echo YOUR_VAULT_PASSWORD > /opt/ansible-shibboleth/.vault_pass.txt```

4. Download the Identity Provider source:
    * ```cd /usr/local/src ```
    * ```wget https://shibboleth.net/downloads/identity-provider/latest/shibboleth-identity-provider-3.3.1.tar.gz```
    * ```tar xzf /usr/local/src/shibboleth-identity-provider-3.3.1.tar.gz```
    * ```rm -f /usr/local/src/shibboleth-identity-provider-3.3.1.tar.gz```

5. Generate the IdP Metadata Certificates and Keys by running these commands:
    * ```cd /opt/ansible-shibbleth/scripts```
    * ```python create-credentials.py FQDN``` (where **FQDN** = **F**ull **Q**ualified **D**omain **N**ame)

   and obtain the password you must set on "```idp_sealer_pw```" and the "```idp_keystore_pw```" host vars (Point ```6.```)

6. Create the IdP configuration file by copying one of these templates:
    * ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml-template```
    * ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml-template-no-ldap```

   into the proper ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml```  (**FQDN** = **F**ull **Q**ualified **D**omain **N**ame)
   This file will provide to Ansible all variables needed to install and to configure a Shibboleth IdP

7. Encrypt the IdP configuration file with Ansible Vault (Optional: this is needed ONLY when you need Ansible Vault):
    * ```cd /opt/ansible-shibboleth```
    * ```ansible-vault encrypt inventories/#_environment_#/host_vars/FQDN.yml --vault-password-file .vault_pass.txt```

8. Insert the IdP's SSL Certificate renamed into "```FQDN.crt```", the IdP's SSL Certificate Key renamed into "```FQDN.key```" and the Certification Authority certificate renamed into "```ca.crt```" inside ```/opt/ansible-shibboleth/roles/common/files``` directory.

9. Insert the IdP style's file (flag, favicon and logo) in the "```roles/idp/files/restore/FQDN/styles```" by following the ```README.md``` file. A "hostname-sample" has been created to help you with this.
(If you have chosen to create an IdP with LDAP, **be sure** to put the organization logo in the ```roles/phpldapadmin/files/restore/FQDN/images/logo.png``` file. An organization logo MUST BE, at least, an image with dimensions 80x60 pixels or their multiples)

10. If you install also phpLDAPadmin, remember to put the logo (80x60 pixels or its multiples) into the "```roles/phpldapadmin/files/restore/FQDN/images/logo.png```" file.

11. Add the IdP Information and Privacy Policy page templates in the "```roles/idp/templates/styles/```" in your language by copying the english '```en/```' sample and changing each "```idp_metadata['en']```" (inside the "```info.html.j2```" and "```privacy.html.j2```" pages) and be sure to adapt the text of the pages to your needs. This step can be avoided if you have already your pages by turning to ```"no"``` the variable "```create_info_and_pp_pages```".

The ansible recipes use the languages provided by the "```idp_metadata```" dictionary so you **HAVE TO LEAVE** the default language "en" and add all other languages that your IdP will support and for which you have provided the needed files. (Point ```7.```)

12. Execute this command to run Ansible on develoment inventory and to install and configure an IdP only on a specific server (FQDN):
    ```ansible-playbook site.yml -i inventories/development/development.ini --limit FQDN --vault-password-file .vault_pass.txt```

13. Execute this command to run Ansible on develoment inventory and to install and configure an IdP on all development servers:
    ```ansible-playbook site.yml -i inventories/development/development.ini --vault-password-file .vault_pass.txt```

## Documentation

The inventories are located into different environments (production,development, test, ...):
   - ```inventories/development/development.ini```
   - ```inventories/production/production.ini```
   - ```inventories/test/test.ini```
   - ...

Each environment represent the type of IdP to be installed with different labels:
   - ```[Debian-IdP-with-IdM]```
   - ```[Debian-IdP-without-IdM]```
   - ...

The "```site.yml```" file contains what will be installed on the machine provided by the environment:
   - ```shib-idp-servers.yml``` (Install,Configure and Run Shibboleth IdP servers **without** an Identity Management)
   - ```shib-idp-idm-servers.yml``` (Install,Configure and Run Shibboleth IdP servers with an Identity Management)
   - ...

The "```shib-idp-servers.yml```" and "```shib-idp-idm-servers.yml```" contains:
   - ```hosts```        (who will receive the sync)
   - ```remote_user```  (who will access via SSH on the servers)
   - ```roles```        (what will be installed and configured on the servers)

Each "```vars/```" directories contains (at least, for each role):
   - ```Debian.yml```   (will contains all variables debian-oriented)
   - ```RedHat.yml```   (will contains all variables redhat-oriented)

The "```host_vars/```" directory contains one ```FQDN.yml``` file for each server and it contains specific variables for the host into the specific environment.
(These files have to be encrypted (you can do this with Ansible Vault) if shared on GitHub or somewhere other)

The "```roles/idp/vars/attr-defs-dict-java7.yml```" and "```roles/idp/vars/attr-defs-dict-java8.yml``` contain all the attribute definitions supported by default on an IdP for Java 7 or 8. 
If you need to limit or change the default Attribute Definitions provided, you have to implement your "```idp_attrDef```" dictionary on the IdP "*FQDN.yml*" file.

The default mirror site is "```https://mi.mirror.garr.it/mirrors/debian/```". If you want to change it, add the variable "mirror" on your ```inventories/#_environment_#/host_vars/FQDN.yml```.

The openLDAP logs will be stored on "```/var/log/slapd/```" directory.

## Restore Procedures

### Databases Restore

1. Retrieve database backup files from ```/var/local/backups/mysql/``` on the IdP:
2. Put the backups file (for shibboleth and statistics database) into:
  - ```roles/idp/files/restore/FDQN/mysql-backup/shibboleth-db.sql.gz```
  - ```roles/idp/files/restore/FQDN/mysql-backup/statistics-db.sql.gz```

3. Set the IDP configuration variable ```idp_db_restore``` to ```"True"``` on its ```host_vars``` file
4. Run again the playbook


### LDAP Restore

1. Retrieve LDAP backup files from ```/var/local/backups/ldap/``` on the IdP:
2. Put the LDAP backup into:
  - ```roles/openldap/files/restore/FQDN/ldap-backup/ldap-users.ldif.gz```
3. Set the IDP configuration variable ```ldap['restore']``` to ```"True"``` on its ```host_vars``` file
4. Run again the playbook


## Useful Commands

```
--- development.ini ---
[Debian-IdP-with-IdM]
ansible-slave-2.test.garr.it

[Debian-IdP-without-IdM]
ansible-slave-1.example.garr.it
-----------------------
```

1. Test that the connection with the server(s) is working:
   * ```ansible all -m ping -i /opt/ansible-shibboleth/inventories/#_environment_#/#_environment_#.ini -u debian```
   ("```debian```" is the user used to perform the SSH connection with the client to synchronize)

2. Get the facts from the server(s):
   * ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/#_environment_#/#_environment_#.ini -u debian```

   Examples:
      * without encrypted files:
         ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/#_environment_#/#_environment_#.ini -u debian```
      * with encrypted files:
         ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/#_environment_#/#_environment_#.ini -u debian --vault-password-file .vault_pass.txt```

   ("```.vault_pass.txt```" is the file you have created that contains the encryption password)

3. Encrypt files:
   * ```ansible-vault encrypt inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```

4. Decrypt Encrypted files:
   * ```ansible-vault decrypt inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```

5. View Encrypted files:
   * ```ansible-vault view inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```


## HOWTO Deploy a Debian Rsyslog Server to centralize IdPs logs

**RECOMMENDED: DON'T USE THE SAME HOSTNAME FOR DIFFERENT IDP, ALSO IF THEY WILL NOT HAVE THE SAME DOMAIN**

1. Become SUDO:
   * ```sudo su -```

2. Install RELP package:
   * ```apt-get install rsyslog-relp```

3. Configure Rsyslog:
   * ```vim /etc/rsyslog.d/22-idpcloud.conf```

     ```bash
     # Load RELP input module
     # http://www.rsyslog.com/doc/v8-stable/configuration/modules/imrelp.html?highlight=imrelp
     module(load="imrelp") # needs to be done just once

     # http://www.rsyslog.com/doc/v8-stable/configuration/properties.html
     # http://www.rsyslog.com/doc/v8-stable/configuration/templates.html
     template(name="RemoteLogSavePath" type="list") {
        constant(value="/var/log/")
        property(name="hostname")
        constant(value="/")
        property(name="timegenerated" dateFormat="year")
        constant(value="/")
        property(name="timegenerated" dateFormat="month")
        constant(value="/")
        property(name="programname")
        constant(value=".log.")
        property(name="timegenerated" dateFormat="year")
        property(name="timegenerated" dateFormat="month")
        property(name="timegenerated" dateFormat="day")
     }

     # define new ruleset and add rules to it
     # http://www.rsyslog.com/doc/v8-stable/configuration/modules/omfile.html
     ruleset(name="remote"){
        action(type="omfile" dynaFile="RemoteLogSavePath")
     }

     # http://www.rsyslog.com/doc/v8-stable/configuration/modules/imrelp.html?highlight=imrelp
     # link the input to the rulset 'remote'
     input(type="imrelp" port="20514" ruleset="remote")
     ```

4. Check Rsyslog Syntax:
   * ```rsyslog -N1```

5. Restart Rsyslog demon:
   * ```service rsyslog restart```
