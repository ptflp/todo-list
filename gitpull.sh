#!/bin/sh
git reset
git checkout .
git pull
docker-compose up -d