# CakeJetbrainsTest

This plugin allows to launch CakePHP 2 application unit tests using JetBrains IDEs (IntelliJ IDEA and PhpStorm). Inspired by [this](https://gist.github.com/maartenba/4529548) gist.

##Installation

###Composer
Add the plugin to your project's `composer.json` - something like this:

        {
            "require": {
                "xtntx/cake-jetbrains-test": "2.*"
            }
        }

###Manual
Clone or download repository into your plugin directory:

        cd app/Plugin
        git clone git@github.com:xTNTx/CakeJetbrainsTest.git

##Configuration

* Load the plugin in your `app/Config/bootstrap.php` file:

        CakePlugin::load('CakeJetbrainsTest');

* Configure IDE. Go to `Run > Edit Configurations..`, select `Defaults > PHPUnit` and put your path to plugin's shell into `Interpreter options`

        -f {path to project}\app\Console\cake.php CakeJetbrainsTest.jetbrains_test

##Compatibility

Tested on CakePHP 2.5, 2.6

##Known issues

Directory test scope is not supported at this moment.