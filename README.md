# Ansible Playbook to deploy Shibboleth Identity Provider

1. [The Playbook](#the-playbook)
2. [Target Audience](#target-audience)
3. [How to request support](#how-to-request-support)
4. [Documentation](#documentation)
5. [Contacts](#contacts)

## The Playbook

This playbook provides an easy way to deploy a very detailed Shibboleth IdP through Ansible.

It will install and configure:

1. Apache Web Server (frontend)
2. Jetty Servlet Container (backend)
3. OpenLDAP directory (if needed)
4. MySQL server
5. phpLDAPadmin web-based LDAP client
6. Shibboleth Identity Provider (IdP)
7. Check_MK Agent (if needed)

The playbook is distributed also with a mechanism of Backup and Restore to allow an easily recover Directory and Databases if needed.

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Target Audience

1. R&E Home Organization
2. Identity Federation Operators 
3. Anyone wants to deploy a Shibboleth Identity Provider with a high level of details

This playbook is targeted to Linux System Administrators that have to know:
* Ansible
* Shibboleth:
  * How to configure an Attribute Filter to release attributes to relying-parties
  * How to configure an Attribute Resolver to define new attributes
  * How to configure and use a Directory Service (OpenLDAP/AD) for Identity Management scopes
  * How to configure and use an SQL database

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## How to request support

* Open an [issue](https://github.com/GEANT/ansible-shibboleth/issues) for bug fixing and/or feature requests

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Documentation

* [Architecture](https://github.com/GEANT/ansible-shibboleth/blob/master/architecture.md)
* [Instructions](https://github.com/GEANT/ansible-shibboleth/blob/master/instructions.md)

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]

## Contacts

* Marco Malavolti (https://github.com/malavolti)
* Davide Vaghetti (https://github.com/daserzw)

[[TOP](#ansible-playbook-to-deploy-shibboleth-identity-provider)]
