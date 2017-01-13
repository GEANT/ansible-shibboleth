# ANSIBLE CODE TO INSTALL SHIBBOLETH PRODUCTS

## Simple flow to install and configure a Shibboleth IdP
1. ```cd /opt/```
2. ```git clone https://github.com/malavolti/ansible-shibboleth.git```
3. Edit the ```[production|test|developmet].ini``` inventory by adding your virtual machine servers.
4. Create your ```.vault_pass.txt``` that contains the encryption password.
5. Create your own ```/opt/ansible-shibboleth/hosts_vars/##FULL.VM.QUALIFIED.DOMAIN.NAME##.yml```:
    ```
    # file: host_vars/##FULL.VM.QUALIFIED.DOMAIN.NAME##.yml
    https_domain: "example.org"
    
    # LDAP Variables
    ldap_basedn: "dc=example,dc=org"
    ldap_domain: "example.org"
    ldap_org: "EXAMPLE Institution"
    ldap_host: "localhost"
    ldap_user: "openldap"
    ldap_root_pw: "##ONE_PASSWORD##"
    ```
6. Ecrypted with your Ansible Vault:
    * cd /opt/ansible-shibboleth
    * ```ansible-vault encrypt host_vars/##FULL.VM.QUALIFIED.DOMAIN.NAME##.yml --vault-password-file .vault_pass.txt ```
7. Insert your "```hostname.domain.name.ext.crt```", "```hostname.domain.name.ext.key```" and "```CA.crt```" inside ```/opt/ansible-shibboleth/roles/common/files```.
8. Run this command to run Ansible on develoment inventory to install and configure an IdP (under development) only on one VM:
    ```ansible-playbook shib-idp.yml -i development.ini --limit ##FULL.VM.QUALIFIED.DOMAIN.NAME## --vault-password-file .vault_pass.txt```

## Documentation ##
The environment (production,development, test, ...) are located into different files:
   - ```development.ini```
   - ```production.ini```
   - ```test.ini```
   - ...

Each environment file divides the architectures through different labels:
   - ```[Debian]```
   - ```[RedHat]```
   - ...

The "```site.yml```" file contains what will be installed on the machine provided by the environment:
   - ```shib-idp.yml``` (Install,Configure and Run a Shibboleth IdP)
   - ...

The "```shib-idp.yml```" contains:
   - ```hosts``` (who will receive the sync)
   - ```remote_user``` (who will access via SSH on the VMs)
   - ```roles``` (what will be installed and configured on the VMs)

"```group_vars/```" directory contains:
   - ```all.yml```      (will contains all shared variable between architectures)
   - ```Debian.yml```   (will contains all variable debian-oriented)
   - ```RedHat.yml```   (will contains all variable redhat-oriented)

"```host_vars```" directory contains one "full.qualified.domain.name.yml" for each VM that will contains some specific variables for the VM. This files have to be encrypted if shared on GitHub or somewhere other.

## Useful Commands ##

```
--- development.ini ---
[Debian]
ansible-slave-1.irccs.garr.it
ansible-slave-2.izs.garr.it
-----------------------
```
1. Test that the connection with the VMs is working:
   * ```ansible all -m ping -i /opt/ansible-shibboleth/development.ini -u debian```
  ("```debian```" is the user used to perform the SSH connection with the client to synchronize)

2. Get the facts from the VMs:
   * ```ansible GROUP_NAME_or_HOST_NAME -m setup -i /opt/ansible-shibboleth/development.ini -u debian```

   Examples:
      * without encrypted files:
         ```ansible ansible-slave-1.irccs.garr.it -m setup -i /opt/ansible-shibboleth/development.ini -u debian```
      * with encrypted files:
         ```ansible ansible-slave-1.irccs.garr.it -m setup -i /opt/ansible-shibboleth/development.ini -u debian --vault-password-file .vault_pass.txt```

   ("```.vault_pass.txt```" is the file you have created that contains the encryption password)

* View Encrypted files:
   * ```ansible-vault view host_vars/ansible-slave-1.irccs.garr.it.yml --vault-password-file .vault_pass.txt```
