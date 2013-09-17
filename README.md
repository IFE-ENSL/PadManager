PadManager
==========

Symfony2 application in order to manager etherpad via email and token

Installation
============

1) Clone the project from github
--------------------------------

```sh
$ git clone https://github.com/IFE-ENSL/PadManager.git
```

2) Installing the Standard Edition
----------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

```sh
    curl -s http://getcomposer.org/installer | php
```

Then, use the `create-project` command to generate a new Symfony application:

```sh
    php composer.phar create-project symfony/framework-standard-edition path/to/install
```

Composer will install Symfony and all its dependencies under the
`path/to/install` directory.

### Download an Archive File

To quickly test Symfony, you can also download an [archive][3] of the Standard
Edition and unpack it somewhere under your web server root directory.

If you downloaded an archive "without vendors", you also need to install all
the necessary dependencies. Download composer (see above) and run the
following command:

```sh
    php composer.phar install
```

### Install application vendors

Go inside the cloned folder and use Composer to install all the application dependencies

```sh
$ php composer.phar update
```

3) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

```sh
    php app/check.php
```

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.
