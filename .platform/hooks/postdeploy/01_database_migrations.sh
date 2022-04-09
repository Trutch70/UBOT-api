#!/usr/bin/env bash

cd /var/app/current && bin/console doctrine:migrations:migrate --no-interaction
