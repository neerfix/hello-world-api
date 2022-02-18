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
- If not installed in your WSL2 distro, install make, yarn & composer ( Example for Debian : ```sudo apt-get install make``` )
- Launch the command : 
``` make ```

### linux

Same macOS

## Fichier /etc/hosts

Modifier le fichier `/etc/hosts` pour ajouter la ligne suivante :

```
 sudo nano /etc/hosts
```

```
/etc/hosts



127.0.0.1       api.dev.hello-world.ovh
```

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

--- ---
# suivi de sprint
## (Sprint 1)[] (14/02/2022 - 18/02/2022)
Nombre d'issues pour ce sprint: 11
Nombre d'issues réalisés: 3
Poids total du sprint: 33
Poids total du sprint réalisés: 14

## (Sprint 2)[] (07/03/2022 - 11/03/2022)
