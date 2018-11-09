# Codeigniter-Hydrofiel
This repository contains all the necesarry files for the hydrofiel.nl website. Contact me at webmaster@hydrofiel.nl if you want the config files for the live version (implying that you are a member of Hydrofiel and are willing to work on the website).

# Installation
## Devilbox
For hosting the local development environment, devilbox is used. You are free to use your own hosting environment but this README will assume that you are also using devilbox.

### Get Devilbox
In order to install Devilbox, follow the instructions at https://devilbox.readthedocs.io/en/latest/index.html. Make sure that you have the prerequisites installed before you start. DO NOT COPY THE env-example FILE BUT INSTEAD USE THE .env FILE PROVIDED IN THIS REPOSITORY.
After installation Devilbox can be started by running ```docker-compose up```.

## Editing /etc/hosts
Make sure to add the following line to your /etc/hosts (Google to see how this is done on windows machines!)
```127.0.0.1 hydrofiel.test```

## Downloading the website files
After you have installed Devilbox, go to the data/www directory and execute the following command:
```shell
git clone https://github.com/bintzandt/CodeIgniter-Hydrofiel.git hydrofiel/htdocs
```
Now hydrofiel/htdocs should contain all the necesarry files to run the website.

## Setting up the database
This repository contains an export of a recent database export. Go to localhost to enter the Devilbox admin page, under the tools section select PHPMyAdmin. Create a new database called hydrofiel. Click on the newly created database and import the file database/hydrofiel.sql in it. By default this also creates one user with username/email admin@hydrofiel.nl and password 'admin'.

## Compiling the assets
The assets are compiled using grunt. Make sure to install node before continuing. 
In order to compile the assets, we first install grunt and it's dependencies. This can be done by using
```npm install```
After that you can use ```grunt``` to start a filewatcher that automatically compiles the sass files and minifies the javascript. Make sure to change sass/hydrofiel.sass and a javascript file in order to compile the assets for the first time. After that you only need to startup grunt when you want to make changes to one of the assets.

## Static assets
Static assets like images and files can be downloaded via FTP from hydrofiel. Contact webmaster@hydrofiel.nl for the details.

## Migrating
In order to make sure that the database is up-to-date, visit http://hydrofiel.test/beheer/migrate after setting up the website.
