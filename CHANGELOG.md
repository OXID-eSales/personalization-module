# Change Log for OXID personalization module powered by Econda

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.3.1] - Not released yet

### Fixed
- Fix empty userId in thank you page for order with guest user.

## [1.3.0] - 2019-04-30

### Added
- New block `oepersonalization_cookienote` in `Application/views/widget/header/cookienote.tpl`

### Changed
- In `scripts.tpl` removed `oxidBlock_pageHead` capture usages since scripts already in HTML head.
- Declared types in `Factory` class.

### Removed
- Not working method `Factory::makeCliErrorDisplayer()`

### Fixed
- Widget rendering call after jQuery inclusion, as some times it was not working.
- JS files wrong inclusion order.

## [1.2.2] - 2019-01-24

### Fixed
- Escape quotes for JS variables.

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
