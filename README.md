# ANSIBLE CODE TO INSTALL SHIBBOLETH PRODUCTS

## Simple flow to install and configure a Shibboleth IdP

1. Move on the ```/opt``` directory and download the Ansible-Shibboleth code:
    * ```cd /opt/```
    * ```git clone https://github.com/malavolti/ansible-shibboleth.git ; cd /opt/ansible-shibboleth```

2. Edit the right inventory file/files about your IdP servers:
    * ```inventories/development/development.ini``` for your development servers.
    * ```inventories/production/production.ini``` for your production servers.
    * ```inventories/test/test.ini``` for your test servers.

3. Create your ```.vault_pass.txt``` that contains the encryption password (this is needed ONLY when you use Ansible Vault):
    * ```echo YOUR_VAULT_PASSWORD > /opt/ansible-shibboleth/.vault_pass.txt```

4. Create the IdP configuration file by copying:
    * ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml-template```
    * ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml-template-no-ldap```

   into the proper ```/opt/ansible-shibboleth/#_environment_#/host_vars/FQDN.yml```  (**FQDN** = **F**ull **Q**ualified **D**omain **N**ame)

5. Ecrypt the IdP configuration file with Ansible Vault (Optional: this is needed ONLY when you use Ansible Vault):
    * ```cd /opt/ansible-shibboleth```
    * ```ansible-vault encrypt inventories/#_environment_#/host_vars/FQDN.yml --vault-password-file .vault_pass.txt```

6. Insert the IdP's SSL Certificate renamed into "```FQDN.crt```", the IdP's SSL Certificate Key renamed into "```FQDN.key```" and the Certification Authority certificate renamed into "```CA.crt```" inside ```/opt/ansible-shibboleth/roles/common/files```.

7. Insert the IdP style's file (flag, favicon and logo) in the "```roles/idp/files/restore/FQDN/styles```" by following the ```README.md``` file. A "hostname-sample" has been created to help you with this.

8. Add the IdP Information and Privacy Policy page templates in the "```roles/idp/templates/styles/```" in your language by copying the english '```en/```' sample and changing each "```idp_metadata['en']```" (inside the "```info.html.j2```" and "```privacy.html.j2```" pages) and be sure to adapt the text of the pages. This step can be avoided if you have already your pages by turning to ```"False"``` the variable "```create_info_and_pp_pages```".

The ansible recipes use the languages provided by the "```idp_metadata```" dictionary so you **HAVE TO LEAVE** the default language "en" and add all other languages that your IdP will support and for which you have provided the needed files. (Point ```7.```)

9. Run this command to run Ansible on develoment inventory to install and configure an IdP only on a specific development server:
    ```ansible-playbook site.yml -i inventories/development/development.ini --limit FQDN --vault-password-file .vault_pass.txt```

10. Run this command to run Ansible on develoment inventory to install and configure an IdP on all development servers:
    ```ansible-playbook site.yml -i inventories/development/development.ini --vault-password-file .vault_pass.txt```

## Documentation ##
The inventories are located into different environments (production,development, test, ...):
   - ```inventories/development/development.ini```
   - ```inventories/production/production.ini```
   - ```inventories/test/test.ini```
   - ...

Each role file divides the architectures through different labels:
   - ```[Debian]```
   - ```[Debian-no-LDAP]```
   - ...

The "```site.yml```" file contains what will be installed on the machine provided by the environment:
   - ```shib-idp-servers.yml``` (Install,Configure and Run Shibboleth IdP servers without an Identity Management)
   - ```shib-idp-ldap-servers.yml``` (Install,Configure and Run Shibboleth IdP servers with an Identity Management)
   - ...

The "```shib-idp-servers.yml```" and "```shib-idp-ldap-servers.yml```" contains:
   - ```hosts```        (who will receive the sync)
   - ```remote_user```  (who will access via SSH on the servers)
   - ```roles```        (what will be installed and configured on the servers)

Each "```vars/```" directories contains (at least, for each role):
   - ```Debian.yml```   (will contains all variable debian-oriented)
   - ```RedHat.yml```   (will contains all variable redhat-oriented)

The "```host_vars/```" directory contains one ```FQDN.yml``` file for each server that contains specific variables for the host into the specific environment.
(These files have to be encrypted (you can do this with Ansible Vault) if shared on GitHub or somewhere other)

The "```roles/idp/vars/attr-defs-dict.yml```" contains all the attribute definitions supported by default on an IdP. If you need to limit or other these Attribute Definitions, you can do it by implementing your "```idp_attrDef```" dictionary on the IdP "*FQDN.yml*" file.

## Restore Procedures ##

### IdP Credentials Restore

1. Put the entire "```/opt/shibboleth-idp/credentials```" directory of your IdP into the "```roles/idp/files/restore/FQDN/```" directory
2. Set the variable ```idp_cert_restore``` to ```"True"```
3. Run again the playbook

### Databases Restore

1. Put the Databases Backup (shibboleth and statistics) of an IdP into:
  - ```roles/idp/files/restore/FDQN/mysql-backups/shibboleth-backup.sql.gz```
  - ```roles/idp/files/restore/FQDN/mysql-backups/statistics-backup.sql.gz```

2. Set the IDP configuration variable ```idp_db_restore``` to ```"True"``` on its ```host_vars``` file
3. Run again the playbook

### LDAP Restore

1. Put the LDAP Backup of an IdP into its ```roles/openldap/files/restore/FQDN/ldap-backup/ldap-backup.gz```
2. Set the IDP configuration variable ```ldap['restore']``` to ```"True"``` on its ```host_vars``` file
3. Run again the playbook


## Useful Commands ##

```
--- development.ini ---
[Debian]
ansible-slave-2.test.garr.it

[Debian-no-LDAP]
ansible-slave-1.example.garr.it
-----------------------
```

1. Test that the connection with the server(s) is working:
   * ```ansible all -m ping -i /opt/ansible-shibboleth/inventories/development/development.ini -u debian```
   ("```debian```" is the user used to perform the SSH connection with the client to synchronize)

2. Get the facts from the server(s):
   * ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/development/development.ini -u debian```

   Examples:
      * without encrypted files:
         ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/development/development.ini -u debian```
      * with encrypted files:
         ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/inventories/development/development.ini -u debian --vault-password-file .vault_pass.txt```

   ("```.vault_pass.txt```" is the file you have created that contains the encryption password)

3. Encrypt files:
   * ```ansible-vault encrypt inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```

4. Decrypt Encrypted files:
   * ```ansible-vault decrypt inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```

5. View Encrypted files:
   * ```ansible-vault view inventories/#_environment_#/host_vars/#_full.qualified.domain.name_#.yml --vault-password-file .vault_pass.txt```
