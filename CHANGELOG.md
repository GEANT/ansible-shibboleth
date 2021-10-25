# Ansible-Shibboleth Changelog

## 2021-10-25

* Replaced Python 2 with Python 3
* Fixed HTTPS redirection
* Added Shibboleth IdP v4.1.4
* Update phpLDAPadmin to v1.2.6.2
* Added `sealer.jks` and `sealer.kver` to the backup&restore system
* Configured default persistent-id management to computed
* Refactorized and Cleaned code
* Updated Jetty package
* Removed Java 7 and Java 8 support
* Added Amazon Corretto JDK as default JDK
* Increased Jetty timeout to 120 seconds for edugain metadata
* Removed Debian Jessie support
* Removed useless code
* Removed Shibboleth v3.x support
* Configured default locale to `en_US.UTF-8`
* Added `enable_maintenance` role
* Added `disable_maintenance` role
* Added `make_a_backup` role
* Added recognized NO-GCM SPs to metadata-providers.xml

## 2021-06-22

* Added checks on `updates.txt` file.
* Implemented capability to add own Info and Privacy page from a static HTML file.
* Fixed italian URL on Privacy templates.
* Added `ntp_servers` into `common` role's defaults and changed GARR APT URL.
* Implemented the capability to add other hosts into `/etc/hosts`.
* Added handler to reload of `shibboleth.ReloadableAccessControlService`.
* Implemented the capability to check existance of `shibboleth` db also on an external host.
* Fixes and improvements on `idp-configure.yml`:
  * Fixed `Remove fake CoCo SP Metadata in the '/metadata' directory` and `Remove fake RS SP Metadata from the '/metadata' directory` by adding `state: absent`
  * Inserted code to add own entity's metadata into `/opt/shibboleth-idp/metadata`
  * Fixed `Download Federation's Metadata files` by adding `when: item.url is defined` because the url is needed to download federation's metadata.
  * Inserted code to specify which IP has access to the IdP Status page on the access-control.xml file
* Implemented the capability to check existance of `statistics` db also on an external host.
* Improvements for `global.xml.j2`:
  * Implement the capability to specify an external host for DataSource
  * Add code needed to Memcache hosts
* Improvements for `idp.properties.j2`:
  * Add code to manage correctly `shibboleth.MemcacheStorageServic` is `storage_memcache_host` is defined.
* Improved `metadata-providers.xml.j2` to add `http_proxy_host`.
* Fixed `services.xml.j2` template condition.
* Fixed idp templates.
* Fixed `idp.conf.j2` RedirectMatch rule
* Fixed Logo path on `custom.properties`
* Fixed `statistics` database script to support external database server
* Fixed `dbanalysis.py.j2` script
* Fixed `idp-stats.conf.j2` template to close access to only specific IPs if required
* Changed "insertSP.php.j2` template to support external host
* Fixed logo visualisation on the logout page
* Fixed `check_coco.py` Check_MK plugin script
* Added telephon number on Info pages
* Added Shibboleth v3.3.3 with checksum
* Added `memcached` role
* Added `shib-idp-servers-balancer.yml` playbook
* Fixed empty rsync backup SSH key
* Fixed empty custom attribute-filter
* Replaced `iteritems()` with `items()` for Python 3
* Replaced `-` with `_` for Ansible rules
* Added Amazon Corretto JDK
* Set Shib IdP 3.4.6 as default install
* Removed databases dependencies
* Avoided apostrophes into usernames
* Improved Regexp for username
* Changed `attribute-filter-custom.xml` to `attribute-filter.xml`
* Fixed invalid characters into the username
* Set affiliation `member` and `staff` to all new user
* Fixed MySQL & LDAP backup failfile removing
* Added Jetty 9.4.35 for Amazon Corretto JDK 11
* Removed `idp_attrDef` dict and renamed IdPAttributes into their LDAP names
* Enabled Shibboleth IdP v3.4.8 by default
* Replaced `attribute-resolver-v3-custom.xml` with `attribute-resolver.xml`
* Fixed JAVA_HOME and removed Shibboleth IdP version dependency from file names
* Added Shibboleth IdP v4.0.1 support
* Included all into the VirtualHost of the IdP
* Added the default versions of `subject-c14.xml` for v3 and v4
* Added `shib-localhost` Apache conf
* Added restore task for `sealer.jks` & `sealer.kver`
* Removed useless things for 'common' role
* Fixed bug on idp version selection
* Added patch to make MYSQLDB-Python 1.2.5 works with MariaDB
* Added PLA 1.2.5 compliant with PHP 7.3
* Splitted Mysql main playbook into install & configure
* Updated playbooks for Debian 10
* Updated IDM-Tools page
