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
  
### Econda tracking with OXID eShop on multiple servers

In case module is being used in application on multiple servers, it is not enough just to upload `emos.js` file via
OXID eShop admin panel. It's also necessary to replicate it through all application servers.
If file was uploaded, it can be found in `out/oeeconda` directory.

## Features

Module provides functionality which allows:
* Add widgets
* ...

## License

See LICENSE file for license details.
