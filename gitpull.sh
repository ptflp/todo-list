#!/bin/sh
git checkout master
git reset
git checkout .
git pull
docker-compose up -d