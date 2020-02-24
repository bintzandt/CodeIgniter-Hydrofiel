# Codeigniter-Hydrofiel
This repository contains all the necessary files for Hydrofiel.nl.

# Installation
## Requirements
### Docker
For hosting the local development environment, docker is used. Please install docker for your operating system, see [their documentation](https://docs.docker.com/install) for information.

### Editing /etc/hosts
Make sure to add the following line to your /etc/hosts (see [this article](https://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/) for Windows).

`127.0.0.1 hydrofiel.test`

### yarn
Install `yarn` using their [installation guide](https://yarnpkg.com/lang/en/docs/install).

## Running
Clone the repo in the desired directory and enter the directory.

### Build dependencies
1. Run `yarn install` to download all javascript dependencies
1. Run `grunt build` to build all assets
1. Run `composer install` to install all PHP dependencies

Optional: if you plan on making changes to the `sass` or `js` files, you can run `grunt watch` to keep building the assets in the background.

### Start up the website
Run `docker-compose up` to start up everything. You should be able to visit the website on `hydrofiel.test`.

## Optional: images
The website is not looking good without having the images. To prevent issues with the GDPR, the images are not stored anywhere. Please contact me if you want to have a copy of all the image files for development purposes.

# Project structure
The project adheres to the following directory structure:
- `.github`: contains the workflows for testing and deploying the code
- `application`: the [CodeIgniter](https://codeigniter.com/user_guide/index.html) folder
- `assets`: contains the JS and CSS files needed to run the website
- `docker`: contains docker configuration files, an empty MySQL database dump and a directory for MySQL persistence
- `fonts`: contains FontAwesome files and other fonts that are used on the website
- `fotos` and `images`: both contain foto's and images used on the website
- `js`: the JS source files that are build to the `assets` directory
- `sass`: the sass sources for the website
- `system`: the CodeIgniter system directory 
