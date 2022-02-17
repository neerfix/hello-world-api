# Hello world - api
--- ---
## Install project

### MacOS

- Clone this repo
- Go to project directory
- launch this command : 
 ```make```

### Windows

Prerequisites : Docker Desktop ( check it here : https://www.docker.com/products/docker-desktop & helpful to have WSL2, see doc : https://docs.microsoft.com/fr-fr/windows/wsl/install )

- Clone this repo
- Got to project directory ( with preference with a WSL2 terminal ) 
- If not installed in your WSL2 distro, install make ( Example for Debian : ```sudo apt-get install make``` )
- Launch the command : 
``` make ```

### linux

Same macOS
--- ---
## Commands :
 
### Data :
Use ```make db.dev``` to re-install dev data. 
### Docker : 
#### stop docker container
```make docker.down```
#### launch docker container
```make docker.up```
#### exec command on docker container
```make docker.run $(command)```
#### Connect to docker container
```make docker.connect```
#### Reset all docker container in project
```make docker.reset```
