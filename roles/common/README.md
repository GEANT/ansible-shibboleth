common
======

This role on the server:
- Configures the 'hosts' file with the FQDN provided and the 'hostname' of the server
- Configures the nameservers
- Adds root user's password and disables Root SSH Login access
- Installs needed common packages"
- Replaces default mirror site with the preferred one
- Configures NTP service and the timezone
- Configures SSL
- Add/remove a SWAP file

Requirements
------------

Any pre-requisites that may not be covered by Ansible itself or the role should be mentioned here. For instance, if the role uses the EC2 module, it may be a good idea to mention in this section that the boto package is required.

Role Variables
--------------

See defaults/main.yml to discover variables that can/should be set to use this role

Dependencies
------------

No dependency

Example Playbook
----------------

Including an example of how to use your role (for instance, with variables passed in as parameters) is always nice for users too:

    - hosts: servers
      roles:
         - { role: username.rolename, x: 42 }

TODO

License
-------

Apache License v2.0 (January 2004)

Author Information
------------------

Marco Malavolti (marco.malavolti@gmail.com)
