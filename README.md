# Hello world - api
--- ---
Le carnet de voyage en ligne

## Stack
_**Client**_: React JS, Tailwind CSS

_**Server**_: PHP avec le framework Symfony

_**Documentation API**_: https://helloworldtravel.stoplight.io/

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
- copy / paste the `.env.exemple` in `.env`
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
# Suivi de sprint
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
- Nombre d'issues pour ce sprint: 9
- Poids total du sprint: 28

--- ---

## Rétrospective du premier sprint

**Ce qu'il faut commencer à faire :**
- Matisse :
- Emeline : Album + features
- Nicolas : Faire plus de review
- Romain : Faire plus attention à ce que j'envoie 

**Ce qu'il faut continuer à faire**
- Matisse :
- Emeline : / (nouvelle sur le back)
- Nicolas : Alimenter le docs des bonnes pratiques et assister mes camarades.
- Romain : Review les MR et apporter une seconde lecture

**Ce qu'il faut arrêter de faire**
- Matisse :
- Emeline : le front
- Nicolas : de merge force avec les privilèges admin ou d'approved en fermant les yeux 🙈
- Romain : Des erreurs d'inattention dans mes relectures et de chipoter :')
