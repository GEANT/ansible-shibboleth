# Database Restore

Put the following files:

 * ```shibboleth-backup.sql.gz```
 * ```statistics-backup.sql.gz```

into this directory to be able to perform a database restoration.
These files are created by the script ```automysqlbackup``` put on each IdP by default into ```/var/lib/automysqlbackup```.

After that you have to set the ```idp_db_restore``` to ```True``` to perform the restoration at next playbook execution.
