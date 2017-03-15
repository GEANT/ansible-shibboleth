# LDAP Backup & Restore

Put into this directory the following openLDAP's file to be able to perform a restoration of directory service:

  - ```ldap-backup.gz```

    (The ```ldap-backup.gz ``` file MUST contain only ```ou=people``` branch and its users into LDIF format.
     The ```autoldapbackup``` script, put on each IdP by default, will create it for you into ```/var/lib/autoldapbackup``` directory.)

and then set the ```ldap_restore``` variable to "True" on the IdP configuration ```host_vars``` file.
