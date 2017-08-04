#!/usr/bin/env python
#-*- coding: utf-8 -*-

from operator import itemgetter
import sys, getopt
import argparse
from subprocess import check_output, call
import shlex
import os
from cStringIO import StringIO

# PARAMETERS

os.environ["JAVA_HOME"] = "/usr/lib/jvm/default-java/jre"
ansible_src = "/opt/ansible-shibboleth"

# END PARAMETERS

parser = argparse.ArgumentParser(description='Generates Shibboleth IDP Credentials.')
parser.add_argument("-v", "--verbose", help="increase output verbosity", action="store_true")
parser.add_argument("fqdn", help="Full Qualified Domain Name of Shibboleth IdP")
args = parser.parse_args()

if args.verbose:
    print "verbosity turned on"

idp_fqdn = args.fqdn
idp_cred_pw = check_output(shlex.split("openssl rand -base64 27")).strip()


### Create IDP Credentials DIR
credentials_dir = ansible_src + "/roles/idp/files/restore/"+ args.fqdn +"/credentials"
call(["mkdir", "-p", credentials_dir])

if args.verbose:
   print("IdP Credentials directory created in: %s" % credentials_dir)



### Find the IDP /bin directory
idp_bin_dir = check_output(shlex.split('find / -path "*shibboleth-identity-provider-*/bin"')).strip()

if args.verbose:
   print("IdP bin directory found in: %s" % idp_bin_dir)



### Generate Sealer JKS and KVER

## Check the existance of Sealer JKS and KVER
sealer_jks_check = check_output(shlex.split('find '+credentials_dir+' -name "sealer.jks"')).strip()
sealer_kver_check = check_output(shlex.split('find '+credentials_dir+' -name "sealer.kver"')).strip()

if (not sealer_jks_check and not sealer_kver_check):
   call(["./seckeygen.sh", "--alias", "secret", "--storefile", credentials_dir + "/sealer.jks", "--storepass", idp_cred_pw, "--versionfile", credentials_dir + "/sealer.kver"], cwd=idp_bin_dir)

if args.verbose:
   sealer_jks = check_output(shlex.split('find '+credentials_dir+' -name "sealer.jks"')).strip()
   sealer_kver = check_output(shlex.split('find '+credentials_dir+' -name "sealer.kver"')).strip()
   print("IdP Sealer JKS created into: %s" % sealer_jks)
   print("IdP Sealer KVER created into: %s" % sealer_kver)



## Generate IDP Backchannel Certificate

## Check the existance of IDP Backchannell P12 and CRT
backchannel_p12_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-backchannel.p12"')).strip()
backchannel_crt_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-backchannel.crt"')).strip()

if (not backchannel_p12_check and not backchannel_crt_check):
   call(["./keygen.sh", "--storefile", credentials_dir + "/idp-backchannel.p12", "--storepass", idp_cred_pw, "--hostname", idp_fqdn, "--lifetime", "30", "--uriAltName", "https://" + idp_fqdn + "/idp/shibboleth", "--certfile", credentials_dir + "/idp-backchannel.crt"], cwd=idp_bin_dir)

if args.verbose:
   backchannel_p12 = check_output(shlex.split('find '+credentials_dir+' -name "idp-backchannel.p12"')).strip()
   backchannel_crt = check_output(shlex.split('find '+credentials_dir+' -name "idp-backchannel.crt"')).strip()
   print("IdP Backchannel PCKS12 created into: %s" % backchannel_p12)
   print("IdP Backchannel Certificate created into: %s" % backchannel_crt)



### Generate IDP Signing Certificate and Key

## Check the existance of Signing CRT and KEY
signing_crt_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-signing.crt"')).strip()
signing_key_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-signing.key"')).strip()

if (not signing_crt_check and not signing_key_check):
   call(["./keygen.sh", "--hostname", idp_fqdn, "--lifetime", "30", "--uriAltName", "https://" + idp_fqdn + "/idp/shibboleth", "--certfile", credentials_dir + "/idp-signing.crt", "--keyfile", credentials_dir + "/idp-signing.key"], cwd=idp_bin_dir)



### Generate IDP Encryption Certificate and Key

## Check the existance of Encryption CRT and KEY
encryption_crt_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-encryption.crt"')).strip()
encryption_key_check = check_output(shlex.split('find '+credentials_dir+' -name "idp-encryption.key"')).strip()

if (not encryption_crt_check and not encryption_key_check):
   call(["./keygen.sh", "--hostname", idp_fqdn, "--lifetime", "30", "--uriAltName", "https://" + idp_fqdn + "/idp/shibboleth", "--certfile", credentials_dir + "/idp-encryption.crt", "--keyfile", credentials_dir + "/idp-encryption.key"], cwd=idp_bin_dir)

if args.verbose:
   signing_crt = check_output(shlex.split('find '+credentials_dir+' -name "idp-signing.crt"')).strip()
   signing_key = check_output(shlex.split('find '+credentials_dir+' -name "idp-signing.key"')).strip()
   encryption_crt = check_output(shlex.split('find '+credentials_dir+' -name "idp-encryption.crt"')).strip()
   encryption_key = check_output(shlex.split('find '+credentials_dir+' -name "idp-encryption.key"')).strip()
   print("IdP Signing Certificate created into: %s" % signing_crt)
   print("IdP Signing Key created into: %s" % signing_key)
   print("IdP Encryption Certificate created into: %s" % encryption_crt)
   print("IdP Encryption Key created into: %s" % encryption_key)


### Generate a file containing the Credentials Password

if (not sealer_jks_check and not sealer_kver_check) and (not backchannel_p12_check and not backchannel_crt_check) and (not signing_crt_check and not signing_key_check) and (not encryption_crt_check and not encryption_key_check):
   file = open(credentials_dir+"/"+idp_fqdn+"_pw.txt","w")
   file.write(idp_cred_pw)
   file.close

file = open(credentials_dir+"/"+idp_fqdn+"_pw.txt","r")
print ("Credentials Password: %s " % file.read())
file.close()
