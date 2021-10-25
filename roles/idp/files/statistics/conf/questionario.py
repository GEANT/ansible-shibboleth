#!/usr/bin/env python3
#-*- coding: utf-8 -*-

"""
This script show the login number per federated resource retrieved from loganalisys.py script for a specific federation.
"""

from xml.dom.minidom import parse, parseString
import sys, json
import subprocess
import os

from optparse import OptionParser

parser = OptionParser()
usage = "usage: %prog [options] [files ....]"
parser = OptionParser(usage)
parser.add_option("-m", "--metadata", help="Federation metadata file", action="store", type="string", dest="metadata")

(options, args) = parser.parse_args()
if len(args) == 0:
    print ("Missing filename(s). Specify '-' as filename to read from STDIN.\n")
    parser.print_help()
    sys.exit(-1)

if not options.metadata:
    parses.error("Option -m must be specified.")

#print (options.metadata)
#print (' '.join(args))

curpath = os.path.dirname(os.path.abspath(__file__))
output = subprocess.getoutput (f"python3 {curpath}/loganalysis.py -n {' '.join(args)}")
output = output.split("\n")

logins = {}
stampa = False
for row in output:
    if stampa:
       elems = row.split('|')
       logins[elems[1].strip()] = int(elems[0].strip())
       stampa = False
    if '-------' in row: stampa = True

#print (logins)

parser = parse(options.metadata)

for idemMD in parser.getElementsByTagNameNS("*","EntitiesDescriptor"):
   for entityMD in idemMD.getElementsByTagNameNS("*","EntityDescriptor"):
      for spDescr in entityMD.getElementsByTagNameNS("*","SPSSODescriptor"):
         entityid = spDescr.parentNode.getAttribute("entityID")
         loginnum = 0
         if entityid in logins.keys():
             loginnum = logins[entityid]

         if loginnum > 0:
            print (f"{'{0: <115}'.format(entityid)} {loginnum}")
