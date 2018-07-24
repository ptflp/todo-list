TodoApp by ptflp
----------------
Test application

Dev/stage environment
---------------------
Installation
------------
copy file docker-compose.yml.stage to docker-compose.yml then
```
git pull ptflp/yii2-basic-app:v3
docker volume create appdb
docker network create skynet
docker-compose up
```

OR USE SCRIPTS
--------------
Windows powershell:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned
.\init-docker.ps1

Windows bat file:
.\init-docker.bat

Linux:
chmod +x init-docker.bash
./init-docker.bash


Gulp 4.0
--------
Install Gulp 4.0 by [link](https://gist.github.com/ptflp/f86694ea2320f792af48e691e2e5f1ff#file-install-sh)