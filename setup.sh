#!/usr/bin/env bash

source .env

bin/console doctrine:database:create \
  && bin/console doctrine:migrations:migrate \
  && bin/console app:assets:seed \
  && bin/console app:exchange:create \
  && bin/console app:exchange:info \
  && bin/console app:exchange:pair:add \
  && bin/console app:exchange:info
