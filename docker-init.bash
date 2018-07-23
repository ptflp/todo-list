#!/bin/bash
docker network create skynet
docker volume create appdb
docker-compose up