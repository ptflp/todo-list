TodoApp by ptflp
----------------
Test application writed on [MyFramewok](https://github.com/ptflp/MyFramework). Framework specialy writed to solve this task.

Dev/stage environment
---------------------
Installation
------------
copy file docker-compose.yml.stage to docker-compose.yml then
```
git clone https://github.com/ptflp/todo-list.git
cd todo-list
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

Roadmap
-------
Fix PSR-2 PSR-4
Set models state to public variable data
Normalize database tasks
Refactor to independent methods for php-unit
Add tests php-unit