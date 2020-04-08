# Change Log for OXID personalization module powered by Econda

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - Not released

### Added
- Ensure module works with PHP 7.2

### Changed
- Started using `oxid-esales/econda-tracking-component`
- Drop support of PHP 7.0.

### Removed
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationHasVariants()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetSku()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetProductId()`

## [1.3.2] - Not released

### Fixed
- Enclose the text values in quotes by RFC-4180 specification.

## [1.3.1] - 2019-07-26

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

[2.0.0]: https://github.com/OXID-eSales/personalization-module/compare/b-1.x...b-2.x
[1.3.2]: https://github.com/OXID-eSales/personalization-module/compare/v1.3.1...b-1.x
[1.3.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.2...v1.3.0
[1.2.2]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.1...v1.2.2
[1.2.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/OXID-eSales/personalization-module/compare/df7baef7b886b1a983fe24e4f782b0954d076b1d...v1.0.0
