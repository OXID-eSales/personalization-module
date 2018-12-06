# Change Log for OXID personalization module powered by Econda

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - Not released

### Changed
- Started using `oxid-esales/econda-tracking-component`

### Removed
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationHasVariants()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetSku()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetProductId()`

## [1.2.1] - 2018-12-06

### Fixed
- Mall URL to javascript files [PR-1](https://github.com/OXID-eSales/econda-analytics-module/pull/1)

## [1.2.0] - 2018-11-27

### Added
- `OxidEsales\PersonalizationModule\Application\Core\ViewConfig::oePersonalizationIsTrackingEnabled`

### Deprecated
- `OxidEsales\PersonalizationModule\Application\Model::oePersonalizationHasVariants`
- `OxidEsales\PersonalizationModule\Application\Model::oePersonalizationGetSku`
- `OxidEsales\PersonalizationModule\Application\Model::oePersonalizationGetProductId`

### Fixed
- Tracking issue when sometimes empty SiteId and PageId is being sent.

## [1.1.1] - 2018-11-20

### Fixed
- Wrong shop data loaded in admin when clicking on "Econda" menu element.

## [1.1.0] - 2018-11-12

### Changed
- Tag Manager scripts are uploaded per sub-shop:
  - Main shop case: out/oepersonalization/tagmanager.js
  - Sub-shop 2 case: out/oepersonalization/2/tagmanager.js

## [1.0.0] - 2018-08-23
