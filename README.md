# These sources are outdated. Check the new version [here](https://github.com/bintzandt/CodeIgniter-Hydrofiel-v2)

# Codeigniter-Hydrofiel
This repository contains all the necessary files for Hydrofiel.nl. Contact me at webmaster@hydrofiel.nl if you want the config files for the live version (implying that you are a member of Hydrofiel and are willing to work on the website).

# Installation
First and foremost, clone this repository in the desired directory!

## Docker
For hosting the local development environment, docker is used.

Install docker for your operating system. See [their documentation](https://docs.docker.com/install) for information.

## Editing /etc/hosts
Make sure to add the following line to your /etc/hosts (Google to see how this is done on windows machines!)
`127.0.0.1 hydrofiel.test`

## Install dependencies and build all files
1. Make sure that you have `yarn` installed on your machine ([installation guide](https://yarnpkg.com/lang/en/docs/install]))
1. Run `yarn install` to download all dependencies
1. Run `grunt build` to build all assets

Optional: if you plan on making changes to the `sass` or `js` files, you can run `grunt watch` to keep building the assets in the background.

## Start up the website
Run `docker-compose up` to start up everything. You should be able to visit the website on `hydrofiel.test`.

## Static assets
Static assets like images are stored in `git-lfs`. Make sure you have installed this on your machine and then run `git lfs pull` to fetch all images.

# Project structure
The project adheres to the following directory structure:
- `application`: the [CodeIgniter](https://codeigniter.com/user_guide/index.html) folder
- `assets`: contains the JS and CSS files needed to run the website
- `docker`: contains docker configuration files, an empty MySQL database dump and a directory for MySQL persistence
- `fonts`: contains FontAwesome files and other fonts that are used on the website
- `fotos` and `images`: both contain foto's and images used on the website
- `js`: the JS source files that are build to the `assets` directory
- `sass`: the sass sources for the website
- `system`: the CodeIgniter system directory 
