# How to use the Ansible Playbook

1. [Requirements](#requirements)
2. [Environment Setup](#environment-setup)
3. [Usage](#usage)
4. [Restore Procedures](#restore-procedures)
   1. [Databases Restore](#databases-restore)
   2. [Directory Restore](#directory-restore)
5. [Useful Commands](#useful-commands)
6. [Authors](#authors)

## Requirements

* [Ansible](https://www.ansible.com/) - Tested with Ansible v2.4.0
* [Shibboleth IdP source](https://shibboleth.net/downloads/identity-provider/latest/)
* A Debian 9 "Stretch" server/virtual machine where install the Shibboleth IdP v3.x

## Environment Setup

1. Become ROOT:
    * `sudo su -`

2. Retrieve GIT repositories of the project:
    * `apt-get install git`
    * `cd /opt ; git clone https://github.com/GEANT/ansible-shibboleth.git`
    * `cd /opt/ansible-shibboleth ; git clone https://github.com/GEANT/ansible-shibboleth-inventories.git inventories`
    * `cd /opt/ansible-shibboleth ; git clone https://github.com/GEANT/ans-idpcloud-utility.git scripts`

3. Create the right inventory file/files about your IdP servers by following the template provided:
    * `inventories/development/development.ini` for your development servers.
    * `inventories/production/production.ini` for your production servers.
    * `inventories/test/test.ini` for your test servers.

4. Create your `.vault_pass.txt` that contains the encryption password (this is needed ONLY when you use Ansible Vault):
    * `cd /opt/ansible-shibboleth`
    * `openssl rand -base64 64 > .vault_pass.txt`

5. Download the Shibboleth Identity Provider source code:
    * `cd /usr/local/src `
    * `wget https://shibboleth.net/downloads/identity-provider/latest/shibboleth-identity-provider-3.3.2.tar.gz`
    * `tar xzf /usr/local/src/shibboleth-identity-provider-3.3.2.tar.gz`
    * `rm -f /usr/local/src/shibboleth-identity-provider-3.3.2.tar.gz`

6. Modify the following file to adapt "`createIdP/createIdP.py`" to your needs:
    * `createIdp/utils/langUtils.py`: to support new languages
    * `createIdp/utils/ymlUtils.py`: to create correctly the YAML file needed by Ansible
    * `createIdp/utils/idpUtils.py`: to change the IDP credentials creation
    * `createIdp/utils/csrUtils.py`: to change the IDP SSL credentials creation

7. Create all files needed by a new IdP provided via Ansible by running these commands:
    * `cd /opt/ansible-shibboleth/scripts/createIdP`
    * `python createIdP.py -h` (e.g.: `python createIdP.py idp.example.org --everything`)

The ansible recipes use the languages provided by the "`idp_metadata`" dictionary so you **HAVE TO LEAVE** the default language "en" and add all other languages that your IdP will support under that)

[[TOP](#how-to-use-the-ansible-playbook)]

## Usage

   * Move on the `ansible-shibboleth` directory:
     `cd /opt/ansible-shibboleth`
     
   * Execute this command to run Ansible on `develoment` inventory and to install and configure an IdP only on a specific server (FQDN):
     `ansible-playbook site.yml -i inventories/development/development.ini --limit FQDN --vault-password-file .vault_pass.txt`

   * Execute this command to run Ansible on develoment inventory and to install and configure several IdPs on more than one development servers:
     `ansible-playbook site.yml -i inventories/development/development.ini --vault-password-file .vault_pass.txt`

[[TOP](#how-to-use-the-ansible-playbook)]

## Restore Procedures

### Databases Restore

1. Retrieve database backup files from ```/var/local/backups/mysql/``` on the IdP:

2. Put the backups file (for shibboleth and statistics database) into:
   * ```inventories/files/FQDN/idp/mysql-restore/shibboleth-db.sql.gz```
   * ```inventories/files/FQDN/idp/mysql-restore/statistics-db.sql.gz```

3. Set the IDP configuration variable ```idp_db_restore``` to ```"True"``` on its ```host_vars``` file

4. Run again the playbook

[[TOP](#how-to-use-the-ansible-playbook)]

### Directory Restore

1. Retrieve LDAP backup files from ```/var/local/backups/``` on the IdP:

2. Put the LDAP backup into:
   * ```inventories/files/FQDN/openldap/restore/ldap-users.ldif.gz```

3. Set the IDP configuration variable ```idp_ldap_restore``` to ```"True"``` on its ```host_vars``` file

4. Run again the playbook

[[TOP](#how-to-use-the-ansible-playbook)]


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

[[TOP](#how-to-use-the-ansible-playbook)]

## Authors

#### Original Authors

* Marco Malavolti (https://github.com/malavolti)
* Davide Vaghetti (https://github.com/daserzw)

[[TOP](#how-to-use-the-ansible-playbook)]
