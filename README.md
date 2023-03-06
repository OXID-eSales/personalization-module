Econda Personalization & Analytics module for OXID eShop
========================================================

Module adds Econda service functionality.

Full documentation can be found on: https://docs.oxid-esales.com/add-ons/personalizationoption/de/2.0/

## Compatibility

* 1.x version of the module works with OXID eShop compilation 6.1.x
* 2.1.0 version of the module works with OXID eShop compilation 6.2.x
* 2.2 and 3.0 module versions (b-6.3.x branch) works with OXID eShop compilations 6.3.x, 6.4.x and 6.5.x

## Installation

Run the following commands to install OXID personalization module:

```bash
composer config repositories.oxid-esales/personalization-module composer https://personalization.packages.oxid-esales.com/
composer require oxid-esales/personalization-module ^3.0.0-rc.1
```

### Avoid conflicts with existing OXID eShop functionality

Personalization module displays widgets which conflicts with OXID eShop default promotions.
To display products nicely it is needed to disable some of OXID eShop functionality:

* Login to admin
* Go to: *Customer info* -> *Promotions* and deactivate listed promotions:
  * *Week's Special*;
  * *Top seller*.
* Go to "Master settings" -> "Core settings" -> "Perform." and deactivate some functionality:
  * In section *Enhanced Performance Settings* uncheck *Load Crossselling*;
  * In section *Enhanced Performance Settings* uncheck *Load "Customers who bought this product also purchased ..."*;
  * Click *Save*.
  
### Privacy protection setup

To comply with the privacy protection laws, the personalization module provides functionality.
For the tracking, example texts are provided for the opt-in/opt-out notices.
Be sure to review, update and activate them before using the tracking functionality.
You can find the texts in "Customer Info" -> "CMS Pages", search for the idents "oeecondaanalyticsoptin",
"oeecondaanalyticsoptout" and "oeecondaanalyticsupdate".

### Econda Analytics/Tag Manager with OXID eShop on multiple servers

In case module is being used in application on multiple servers, it is not enough just to upload `emos.js`
or `tagmanager.js` files via OXID eShop admin panel.It's also necessary to replicate them through all application servers.
If file was uploaded, it can be found in `out/oepersonalization` directory.

## Features

Module provides functionality which allows:
* Add widgets
* Provide widget for Visual CMS
* Track visitors behaviour
* Use Econda Tag Manager
* Export data for Econda

### Visual CMS Widget

A widget is provided for the Visual CMS module.
After installation of the personalization module, the widget will be available
in the Visual CMS editor. You can find it by the name "AI Content".
To add it, just fill in the required settings for widget id and widget template (options described bellow).
There is an optionally setting to limit the number of results; if it is left blank,
no limit will be applied.

There are 2 template files prepared for Visual CMS widget:
* `Component/views/vcms_banner.ejs.html` - To display banner images with links.
* `Component/views/vcms_recommendations.ejs.html` - To display products within widget.

### Export data for Econda

There are 2 ways of exporting data:
* Administration panel
* CLI

To export data via CLI execute command:
```bash
vendor/bin/oe-personalization-data-feed
```
or if there is a need to customize configuration parameters:
```bash
vendor/bin/oe-personalization-data-feed --config /path/to/your/config/file.php
```

## Bugs and Issues

If you experience any bugs or issues, please report them in the section **Module OXID Personalization** of https://bugs.oxid-esales.com.

## License

See LICENSE file for license details.
