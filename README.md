OXID personalization module powered by Econda
=============================================

Module adds Econda service functionality.

## Requirements

* OXID eShop compilation ^v6.1.0

## Installation

Run the following commands to install OXID personalization module:

```bash
composer config repositories.oxid-esales/personalization-module composer https://personalization.packages.oxid-esales.com/
composer require oxid-esales/personalization-module
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
You can find the texts in "Customer Info" -> "CMS Pages", search for the idents "oepersonalizationoptin" and "oepersonalizationoptout".

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
* Export products for Econda

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

## Bugs and Issues

If you experience any bugs or issues, please report them in the section **Module OXID Personalization** of https://bugs.oxid-esales.com.

## License

See LICENSE file for license details.
