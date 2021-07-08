# Ansible Playbook Architecture

1. [Inventories](#inventories)
2. [Playbooks](#playbooks)
3. [Vars](#vars)
4. [Miscellanous](#miscellanous)
5. [Authors](#authors)

## Inventories

The inventories are located into different environments (production,development, test, ...):
   - `inventories/development/development.ini`
   - `inventories/production/production.ini`
   - `inventories/test/test.ini`
   - ...

Each environment represent the type of IdP to be installed with different labels:
   - `[Debian-IdP-with-IdM]`
   - `[Debian-IdP-without-IdM]`
   - ...

## Playbooks

The `site.yml` file contains what will be installed on the machine provided by the environment:
   - `shib-idp-servers.yml` (Install,Configure and Run Shibboleth IdP servers **without** an Identity Management)
   - `shib-idp-idm-servers.yml` (Install,Configure and Run Shibboleth IdP servers with an Identity Management)
   - `shib-idp-idm-servers-garr.yml` (Install,Configure and Run Shibboleth IdP servers with an Identity Management like the IdP-in-the-Cloud project)

The `shib-idp-servers.yml`, `shib-idp-idm-servers.yml` and `shib-idp-idm-servers-garr.yml` contains:
   - `hosts`        (who will receive the sync)
   - `remote_user`  (who will access via SSH on the servers)
   - `roles`        (what will be installed and configured on the servers)

[[TOP](#ansible-playbook-architecture)]

## Vars

The `vars/` role's directory may contain:
   - `Debian.yml`   (contains all variables debian-oriented)

The `host_vars/` directory contains one `FQDN.yml` file for each server and it contains specific variables for the host into the specific environment.
(These files have to be encrypted, you can do this with Ansible Vault, if shared on GitHub or somewhere other)

The `attribute-resolver.xml` file can be loaded from the IdP server directory created into the `inventory/files` directory or can be loaded by the templates provided under `role/idp/templates/conf` directory.

The default mirror site for APT is `http://deb.debian.org/debian/`. If you want to change it, add the variable "mirror" on your `inventories/#_environment_#/host_vars/FQDN.yml`.


The openLDAP logs will be stored on `/var/log/slapd/` directory.

[[TOP](#ansible-playbook-architecture)]

## Miscellanous

The recipes can configure an IdP to be monitored through [Check_MK](https://mathias-kettner.de/check_mk.html).
To be able to add the IdP hosts on the check_mk centralized server, it is needed create an automation user on check_mk server and provide its username and secret as requested by FQDN.yml
To reach this, it is needed to configure `check_mk` dictionary on your `FQDN.yml` file.

The recipes offer the possibility to configure the IdP to send its logs to a Rsyslog Server through RELP protocol.
To use this feature fill the rsyslog server `ip` and `port` parameters on your `FQDN.yml` file.

The recipes offer the possibility to configure the IdP to send its mysql and ldap backups to a Backups Server through RSYNC.
The best way found to offer this feature on each IdP is to share a pair of SSH credentials, authorized by backups server, on all IdP and consent them to write their own backups on a specific directory named with their FQDN.
The SSH credentials (SSH-CERT and SSH-KEY) HAVE TO BE generate and placed into `roles/rsync/files/ssh` renamed as README says.
To use this feature fill the backups server `ip` and `remote_path` parameters on your `FQDN.yml` file, where `remote_path` is the directory on the backups server where every IdP will create their own directory and put their mysql/ldap backups.

[[TOP](#ansible-playbook-architecture)]

## Authors

#### Original Authors

* Marco Malavolti (https://github.com/malavolti)
* Davide Vaghetti (https://github.com/daserzw)

[[TOP](#ansible-playbook-architecture)]
