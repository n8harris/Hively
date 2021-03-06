# Hively Web Application #

**This document assumes the user is starting from scratch with no dependencies installed. If one of the dependencies mentioned is already installed on your system, please skip that step.**

This website is a single-page application. The front-end of the site uses a custom API to retrieve content. The site can be access by going to [http://hivelyapp.io](http://hivelyapp.io) and clicking Sign Up.

## Project Setup

Before starting project setup. Checkout the [Useful Tools](#troubleshooting-and-useful-tools) section.

### Installation

#### Server Requirements

1. Apache 2.x (with mod_rewrite installed) or Nginx
1. PHP 5.6.x
1. MongoDB (latest version)

#### Alternate Server Setup

- A quick way to setup an environment for this project is to use the prepackaged solution that Scotchbox provides.
This installation is a Vagrant box with all server requirements installed in addition to some extras. The documentation
for Scotchbox installation can be found [here](https://box.scotch.io/).

#### Clone Repository
Navigate to the folder on your local system in terminal and run this command.

```bash
$git clone https://github.com/GoCodeColorado/Hively.git
```

Or copy the same url `https://github.com/GoCodeColorado/Hively.git` for a new repository in SourceTree

### Application Setup

#### Package Managers

Install identified dependencies with package managers from the root folder `Hively`. If you do not have these package managers installed on your system, install them according to these directions:

If you are using a Mac, some of these package managers can easily be installed using [Homebrew](http://brew.sh/).

- [Composer](https://getcomposer.org/doc/00-intro.md#globally)
- [Npm](https://nodejs.org/en/download/package-manager/)
- [Bower](http://bower.io/#install-bower)

Now we can run these commands using the installed package managers:

```
$ composer install
$ npm install
$ bower install
$ bundle install
```

#### Config Files
 - Duplicate `.env.example` file and rename to `.env` and set variable for environment

#### Seeding

You will need to run two commands to seed Mongo with some data the application will need to run. The first will pre-populate Colorado Business Entities. These commands will need to be run from the Hively directory.

```
$ app/Console/cake AddBusinesses
$ app/Console/cake AddCategories
```

#### File Permissions
  Make the following files writeable by the web server (i.e. use chmod -R 755 from the terminal after navigating to each of these folders):
  * `app/tmp`
  * `app/webroot/css/build`
  * `app/webroot/js/build`

## Testing

More will be added to this section once automated tests are implemented.

## Coding Standards

### PHP Coding Standards

The Hively project is based on the CakePHP 2.x code base, thus, the custom PHP code follows this style. The team has selected the
historical CakePHP standard that predates the PSR-2 standard. The historical standard can be found at
[2.0 Coding Standards](http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html).

#### PHPCS Tool

phpcs (PHP Code Sniffer) can be used to assist in following the CakePHP 2.x standard. phpcs is a command line tool that can also be integrated into Sublime Text
and other code editors.

Setup phpcs on OS X:

```
$ cd /path/to/somedir
$ git clone git@github.com:cakephp/cakephp-codesniffer.git
$ cd cakephp-codesniffer
$ git checkout 1.x
$ composer install
$ ./vendor/bin/phpcs --version
```

The version should emit:

```
PHP_CodeSniffer version 1.5.6 (stable) by Squiz (http://www.squiz.net)
```

phpcs needs to include to the CakePHP ruleset in addition to the other standards (e.g. PSR2, Zend, etc.)

```
$ ./vendor/bin/phpcs -i
```

If CakePHP coding standard is missing, add it to the install paths:

```
$ ./vendor/bin/phpcs --config-set installed_paths /path/to/somedir/cakephp-codesniffer
$ ./vendor/bin/phpcs --config-show
```

To check source code files, for example check the Account model:

```
$ cd /path/to/odyssey-adventure-club
$ /path/to/somedir/cakephp-codesniffer/vendor/bin/phpcs --standard=CakePHP app/Model/Account.php
```

Using the "-n" option suppresses warning messages.

#### Sublime Text integration

Once PHP Code Sniffer pluggin is installed, the following should be added to your project config file. Note that the path name needs
to be absolute.

```
{
    "phpcs_executable_path": "/path/to/somedir/cakephp-codesniffer/vendor/bin/phpcs",
    "phpcs_additional_args": {
        "--standard": "CakePHP",
        "-n": ""
    }
}
```

## Deploying and Building

### Task Runner

Gulp is used for task management and utilizes the following file resources for dependencies.  

1. `package.json`: dev dependencies for gulp and gulp modules.
2. `gulpfile.js`: Gulp tasks/commands.
3. `gulp.config.js`: Gulp resource configuration. File and package resource locations.
4. `bower.json`: Web asset package management.

#### Gulp Resource configuration

Edit the `gulp.conf.js` to identify resource location references and groups of files for concatonation and minification that will be built and included by the applications for use and display.

#### Tasks

`$ gulp`: Default task - will start `watch` tasks and will auto build and minify dependencies changed.

`$ gulp watch`: starts watch tasks to auto build assets when files are changed.

`$ gulp build`: Will build all dev resources to minified scripts for application inclusion.  

`$ gulp build-prod` : RUN FOR PRODUCTION ASSETS BUILD FOR A RELEASE, same as build but removes resource maps.

`$ gulp clean`: deletes generated directories and resources. Run if pulling a branch results in merge error of `assets` folders or files.

## Troubleshooting and Useful Tools

### Troubleshooting

TBD

### Tools

#### RoboMongo
- This tool will allow you to interact with the Mongo datbase. You can find it [here](https://robomongo.org/).

#### Atom

- This is our text editor of choice. It can be found [here](https://atom.io/).

#### SourceTree

- This is a GUI for interacting with Git repos. It is simply an easier alternative to the command line. You can find it
[here](https://www.sourcetreeapp.com/).

#### Postman

- This is a helpful application for testing the OAC API as well as various requests that the website may make. You
can find it [here](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en).

## Architecture

### Backend

The backend of this application is CakePHP.  The Model layer is heavily leveraged, the controller and view layers and primarily to load the chrome for the front-end single-page application - you won't find a lot of controller code.

There is one version of the chrome:

* The controller for this is LandingController and the layout file is `app/View/Layouts/landing.ctp`. There is no view file.

The app leverages the Alloy library, which makes handling RPC requests from javascript/mobile clients quite a bit easier.  The base library is in `app/Lib/Alloy`.  All calls are routed through the ApiController, which loads an appropriate API class from the ones available in `app/Lib/Api`.

### Frontend

The front-end of the application is based on `backbone.js`.  The application files are found in `app/webroot/js/app`.

The front-end also leverages the Alloy library, which takes care of handling requests to the backend and adds a view manager to handle some of backbone's deficiencies.

The templates are all javascript jsRender templates.  Though they are stored as separate files in `app/webroot/js/app/tmpl`, they are loaded into the chrome as `<script>` blocks upon execution.


## Integrations

The application has one 3rd party integration required to make it run:

### Contentful:
The Contentful CMS is used to store question and question category info. We query the service from our custom API using the Contentful PHP SDK whenever we need question info (i.e. the questions page).

### Colorado Data Sets:
Currently, we are only using the Colorado Business Entities dataset to prepopulate business info, but we hope to use more in the future as our presentation will demonstrate. Info on the dataset can be found [here](https://data.colorado.gov/Business/Colorado-Business-Entities/4ykn-tg5h).

## Code Walkthrough

This application matches businesses up with potential employees and employees with businesses. You should be able to do several things with the application:

* Sign up for a new account as either a business or user (businesses should be able to select from a pre-populated list).
* Answer questions about your personality or company culture
* Be matched with potential businesses or employees and view their match percentage
* Swipe left or right through a pool of matches
* View saved matches in a connection pool page where each individual profile can be viewed for later contact
* Edit your bio
* View your own profile
* Logout
* Login
* The matching algorithm will attempt to match a user with other employees or businesses when they login again
