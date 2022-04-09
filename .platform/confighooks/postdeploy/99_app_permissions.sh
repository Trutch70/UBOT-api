#!/usr/bin/env bash

echo "Current permissions:";
ls -alh /var/app/current/var/cache;
echo "Fixing permissions:";
chown webapp:webapp -R /var/app/current;
echo "Current permissions:";
ls -alh /var/app/current/var/cache;
