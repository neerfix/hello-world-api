# Hello world - api
--- ---
Le carnet de voyage en ligne

## Stack
_Client_: React JS, Tailwind CSS

_Server_: PHP avec le framework Symfony

## Dream Team

- [@Louise BAULAN](https://github.com/Fayaah)
- [@Matisse LIVAIN](https://github.com/MLivain)
- [@Nicolas NOTARARIGO](https://github.com/Neerfix)
- [@Emeline PAL](https://github.com/emelinepal)
- [@Aimée RITLENG](https://github.com/Aimee-RTLNG)
- [@Gregg SANCHEZ](https://github.com/Arty3P)
- [@Romain FRECHET](https://github.com/Hikari-rom)


## Install project

### MacOS

- Clone this repo
- Go to project directory
- launch this command : 
 ```make```

### Windows

Prerequisites : Docker Desktop ( check it here : https://www.docker.com/products/docker-desktop & helpful to have WSL2, see doc : https://docs.microsoft.com/fr-fr/windows/wsl/install )
The doc didn't precise that you have to go to the Microsoft Store to install the distro ( Debian have been used by the members of the team )
- There is a need to activate the docker Desktop integration for WSL2.

- Clone this repo
- Got to project directory ( with preference with a WSL2 terminal ) 
- Launch ``sudo apt-get update```
- If not installed in your WSL2 distro, install make, composer ( Example for Debian : ```sudo apt-get install make``` )
- Add repository links ``curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -``
- Install yarn by the npm package so ```sudo apt-get install nodejs npm ``` and ``` npm install yarn -g```
- When you are in the terminal of the WSL2 distro, you can use ```cd /mnt/c/users/{your user}```to access your Windows files.
- Install PHP 8.0 on the Debian
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
## [Sprint 1](https://github.com/helloworld-ynovlyon/api/milestone/1) (14/02/2022 - 18/02/2022)
### Debut de sprint
- Nombre d'issues pour ce sprint: 7
- Poids total du sprint: 33

### Fin de sprint
- Nombre d'issues pour ce sprint: 11
- Nombre d'issues réalisés: 3
- Poids total du sprint: 33
- Poids total du sprint réalisés: 14


## [Sprint 2](https://github.com/helloworld-ynovlyon/api/milestone/2) (07/03/2022 - 11/03/2022)
