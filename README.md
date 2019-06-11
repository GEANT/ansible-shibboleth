# Ansible Playbook to deploy Shibboleth Identity Provider

1. [The Playbook](#the-playbook)
2. [Target Audience](#target-audience)
3. [How to request support](#how-to-request-support)
4. [Documentation](#documentation)
5. [Authors](#authors)

## The Playbook

This playbook provides an easy way to deploy a very detailed Shibboleth IdP through Ansible.

It will install and configure:

1. Apache Web Server (frontend)
2. Jetty Servlet Container (backend)
3. OpenLDAP directory (if needed)
4. MySQL server
5. phpLDAPadmin web-based LDAP client
6. Shibboleth IdP:
   * Customized config files are loadable.
   * Login Page customizations are applied (Logo, Favicon, Multilanguage, Footer Text & Background color, ...)
   * `persistentID` stored on database
7. A set of useful web tools:
   1. `statistics`: A web application that collects authentication statistics.
   2. `lockuser`: A web application that allow to block user's authentication on resources through a button or a date.
   3. `flup`: A web application used to activate users by themselves after their creation (if IDM part it is installed).
8. Check_MK Agent (if needed):
   useful to connect towards a Check_MK server to monitor the health of the IdP.
   It is provided with different checks on: COCO, RS, LDAP availability, Metadata Availability, AACLI, ...
9. SWAP space through a file (if needed)
10. NTP deamon

The playbook is distributed also with a mechanism of Backup and Restore to allows an easily recover Directory and Databases if needed.

The user who wants to use this Ansible Playbook needs to know:
* Linux (Bash terminal)
* Ansible
* Shibboleth
* Python

and has to provide:

* SSL certificate/key/CA (for HTTPS/LDAPS)
* Self-signed certificate/key (for signing and encrypting SAML comunications/assertions)
* a Logo (PNG,JPG, ... sized 160x120 px or more, but respecting 80x60 aspect-ratio)
* a Favicon (PNG,JPG, ... sized 32x32 px or more, but respecting 16x16 aspect-ratio)
* the IdP scopes, entityID, Name, Description, Privacy Policy page, Informative page
* a password for:
  * ROOT user to access the server through a console (not SSH)
  * ROOT user of OpenLDAP directory
  * ROOT user of MySQL server
  * Shibboleth database user
  * Statistics database user
  * PhpLdapAdmin user
* the timezone
* an email (possibly impersonal) used for IdP Technical Contact
* an email (possibly impersonal) used for IdP Support Contact
* an email (possibly impersonal) used for Apache administration
* the metadata URL / signing certificate for each metadata stream they want to connect with the IdP
* text & colors for IdP Login Page
* the url needed to reset user's password if lost
* some info about federations who the institution is a member: Logo, Info URL, Name, registrationAuthority
* An user used to bind and search other users on the directory.
* AUP (Acceptable Usage Policy) URL


All these things and many others needed to create an IdP with all information desired by users and federation operators can be easily collected by a question-&-answer Python script, named "createIdp", located into this repo:

https://github.com/GEANT/ans-idpcloud-utility


[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Target Audience

1. R&E Home Organization
2. Identity Federation Operators 
3. Anyone wants to deploy a Shibboleth Identity Provider with a high level of details

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## How to request support

* Open an [issue](https://github.com/GEANT/ansible-shibboleth/issues) for bug fixing and/or feature requests

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Documentation

* [Architecture](https://github.com/GEANT/ansible-shibboleth/blob/master/architecture.md)
* [Instructions](https://github.com/GEANT/ansible-shibboleth/blob/master/instructions.md)

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Authors

#### Original Authors

* Marco Malavolti (https://github.com/malavolti)
* Davide Vaghetti (https://github.com/daserzw)

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]
