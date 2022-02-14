# Hello world - api
--- ---
## Install project

### MacOS

- Clone this repo
- Go to project directory
- launch this command : 
 ```make```

### Windows

- Clone this repo
- Got to project directory
- Démerdez-vous pour lancer le projet.

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
