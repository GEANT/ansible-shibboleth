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
   * Config files can be retrieved from a directory
   * Few Login Page customizations are applied (Logo, Favicon, Translations, ...)
   * `persistentID` stored on database
7. A set of useful web tools:
   1. `statistics`: A web application that collects authentication statistics.
   2. `lockuser`: A web application that allow to block user's authentication through a button or a date.
   3. `flup`: A web application used to activate users after their creation and by themselves.
8. Check_MK Agent (if needed):
   useful to connet towards a Check_MK server to monitor the health of the IdP.
   It is provided with different checks on: COCO, RS, LDAP availability...

The playbook is distributed also with a mechanism of Backup and Restore for Directory and Databases to easily recover them if needed.

The user who wants to use this Ansible Playbook needs to know:
* Linux (Bash terminal)
* Ansible
* Shibboleth

and has to provide:

* SSL certificate/key/CA (for HTTPS/LDAPS)
* Self-signed certificate/key (for signing and encrypting SAML comunications/assertions)
* a Logo (PNG,JPG, ... sized 160x120 px or more, but respecting 80x60 aspect-ratio)
* a Favicon (PNG,JPG, ... sized 32x32 px or more, but respecting 16x16 aspect-ratio)
* the IdP scope
* a ROOT password used only through direct console
* the timezone of their area
* an email (possibly impersonal) used for IdP Technical Contact
* an email (possibly impersonal) used for IdP Support Contact
* the metadata URL / signing certificate for each metadata stream they want to connect with the IdP
* text & colors for IdP Login Page
* the url needed to reset user's password if lost
* some info about the federation: Logo, Info URL, Name

All these things and many others needed to create an IdP with all information desired by users and federation operators are easily collected by a question-&-answer Python script, named "createIdp", located into this repo:

https://github.com/GEANT/ans-idpcloud-utility


[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Target Audience

1. R&E Home Organization
2. Identity Federation Operators 
3. Anyone wants to deploy a Shibboleth Identity Provider

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
