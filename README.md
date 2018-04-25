Econda module
=============

Module adds Econda service functionality.

## Requirements

* OXID eShop compilation ^v6.1.0

## Installation

* Register module repository in project root `composer.json` file.
* Execute command: `composer require oxid-esales/econda-module:^1.0.0`.

### Avoid conflicts with existing OXID eShop functionality

Econda personalization module displays widgets which conflicts with OXID eShop default promotions.
To display products nicely it is needed to disable some of OXID eShop functionality:

* Login to admin
* Go to: *Customer info* -> *Promotions* and deactivate listed promotions:
  * *Week's Special*;
  * *Top seller*.
* Go to "Master settings" -> "Core settings" -> "Perform." and deactivate some functionality:
  * In section *Enhanced Performance Settings* uncheck *Load Crossselling*;
  * In section *Enhanced Performance Settings* uncheck *Load "Customers who bought this product also purchased ..."*;
  * Click *Save*.

## Features

Module provides functionality which allows:
* Add widgets
* ...

## License

See LICENSE file for license details.
