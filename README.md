# ANSIBLE CODE TO INSTALL SHIBBOLETH PRODUCTS

## Requirements

* [Ansible](https://www.ansible.com/) - Tested with Ansible v2.4.0
* [Shibboleth IdP source](https://shibboleth.net/downloads/identity-provider/latest/)
* A Debian 8 "Jessie" server/virtual machine where install the Shibboleth IdP v3.x

## Simple flow to install and configure a Shibboleth IdP

1. Become ROOT:
    * ```sudo su -```

2. Retrieve GIT repositories of the project:
    * ```apt-get install git```
    * ```cd /opt ; git clone https://github.com/[malavolti|ConsortiumGARR|GEANT]/ansible-shibboleth.git```
    * ```cd /opt/ansible-shibboleth ; git clone https://github.com/[malavolti|ConsortiumGARR|GEANT]/ansible-shibboleth-inventories.git inventories```
    * ```cd /opt/ansible-shibboleth ; git clone https://github.com/[malavolti|ConsortiumGARR|GEANT]/ans-idpcloud-utility.git scripts```

3. Create the right inventory file/files about your IdP servers by following the template provided:
    * ```inventories/development/development.ini``` for your development servers.
    * ```inventories/production/production.ini``` for your production servers.
    * ```inventories/test/test.ini``` for your test servers.

4. Create your ```.vault_pass.txt``` that contains the encryption password (this is needed ONLY when you use Ansible Vault):
    * ```cd /opt/ansible-shibboleth```
    * ```openssl rand -base64 64 > .vault_pass.txt```

5. Download the Shibboleth Identity Provider source code:
    * ```cd /usr/local/src ```
    * ```wget https://shibboleth.net/downloads/identity-provider/latest/shibboleth-identity-provider-3.3.2.tar.gz```
    * ```tar xzf /usr/local/src/shibboleth-identity-provider-3.3.2.tar.gz```
    * ```rm -f /usr/local/src/shibboleth-identity-provider-3.3.2.tar.gz```

6. Modify the following file to adapt "```createIdP/createIdP.py```" to your needs:
    * ```createIdp/utils/langUtils.py```: to support new languages
    * ```createIdp/utils/ymlUtils.py```: to create correctly the YAML file needed by Ansible
    * ```createIdp/utils/idpUtils.py```: to change the IDP credentials creation
    * ```createIdp/utils/csrUtils.py```: to change the IDP SSL credentials creation

7. Create all files needed by a new IdP provided via Ansible by running these commands:
    * ```cd /opt/ansible-shibboleth/scripts/createIdP```
    * ```python createIdP.py -h``` (e.g.: ```python createIdP.py idp.example.org --everything```)

The ansible recipes use the languages provided by the "```idp_metadata```" dictionary so you **HAVE TO LEAVE** the default language "en" and add all other languages that your IdP will support under that)

8. Examples of ansible-playbook commands:

   * Execute this command to run Ansible on develoment inventory and to install and configure an IdP only on a specific server (FQDN):
     ```ansible-playbook site.yml -i inventories/development/development.ini --limit FQDN --vault-password-file .vault_pass.txt```

   * Execute this command to run Ansible on develoment inventory and to install and configure several IdPs on more than one development servers:
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
   - ```shib-idp-idm-servers-garr.yml``` (Install,Configure and Run Shibboleth IdP servers with an Identity Management like the IdP-in-the-Cloud project)

The "```shib-idp-servers.yml```", "```shib-idp-idm-servers.yml```" and "```shib-idp-idm-servers-garr.yml```" contains:
   - ```hosts```        (who will receive the sync)
   - ```remote_user```  (who will access via SSH on the servers)
   - ```roles```        (what will be installed and configured on the servers)

Each "```vars/```" directories contains (at least, for each role):
   - ```Debian.yml```   (will contains all variables debian-oriented)

The "```host_vars/```" directory contains one ```FQDN.yml``` file for each server and it contains specific variables for the host into the specific environment.
(These files have to be encrypted (you can do this with Ansible Vault) if shared on GitHub or somewhere other)


The "```roles/idp/vars/attr-defs-dict-java7.yml```" and "```roles/idp/vars/attr-defs-dict-java8.yml``` contain all the attribute definitions supported by default on an IdP for Java 7 or 8. 
If you need to limit or change the default Attribute Definitions provided, you have to implement your "```idp_attrDef```" dictionary on the IdP "*FQDN.yml*" file or modify the files directly.


The default mirror site is "```http://deb.debian.org/debian/```". If you want to change it, add the variable "mirror" on your ```inventories/#_environment_#/host_vars/FQDN.yml```.


The openLDAP logs will be stored on "```/var/log/slapd/```" directory.


The recipes can configure an IdP to be monitored through [Check_MK](https://mathias-kettner.de/check_mk.html).
To be able to add the IdP hosts on the check_mk centralized server, it is needed create an automation user on check_mk server and provide its username and secret as requested by FQDN.yml
To reach this, it is needed to configure ```check_mk``` dictionary on your ```FQDN.yml``` file.


The recipes offer the possibility to configure the IdP to send its logs to a Rsyslog Server through RELP protocol.
To use this feature fill the rsyslog server ```ip``` and ```port``` parameters on your ```FQDN.yml``` file.

The recipes offer the possibility to configure the IdP to send its mysql and ldap backups to a Backups Server through RSYNC.
The best way found to offer this feature on each IdP is to share a pair of SSH credentials, authorized by backups server, on all IdP and consent them to write their own backups on a specific directory named with their FQDN.
The SSH credentials (SSH-CERT and SSH-KEY) HAVE TO BE generate and placed into ```roles/rsync/files/ssh``` renamed as README says.
To use this feature fill the backups server ```ip``` and ```remote_path``` parameters on your ```FQDN.yml``` file, where ```remote_path``` is the directory on the backups server where every IdP will create their own directory and put their mysql/ldap backups. 

## Restore Procedures

### Databases Restore

1. Retrieve database backup files from ```/var/local/backups/mysql/``` on the IdP:

2. Put the backups file (for shibboleth and statistics database) into:
   * ```inventories/files/FQDN/idp/mysql-restore/shibboleth-db.sql.gz```
   * ```inventories/files/FQDN/idp/mysql-restore/statistics-db.sql.gz```

3. Set the IDP configuration variable ```idp_db_restore``` to ```"True"``` on its ```host_vars``` file

4. Run again the playbook


### LDAP Restore

1. Retrieve LDAP backup files from ```/var/local/backups/``` on the IdP:

2. Put the LDAP backup into:
   * ```inventories/files/FQDN/openldap/restore/ldap-users.ldif.gz```

3. Set the IDP configuration variable ```idp_ldap_restore``` to ```"True"``` on its ```host_vars``` file

4. Run again the playbook


## Useful Commands

```
--- development.ini ---
[Debian-IdP-with-IdM]
idp1.example.org

[Debian-IdP-without-IdM]
idp2.example.org
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

## Authors

#### Original Authors

* Marco Malavolti (https://github.com/malavolti)
* Davide Vaghetti (https://github.com/daserzw)
