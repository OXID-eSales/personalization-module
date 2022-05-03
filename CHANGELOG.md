# Change Log for OXID personalization module powered by Econda

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [3.0.0] - Not released

### Fixed
- Adapt module tests to removal of oxconfig encoding feature.
- Fixed compatibility issues regarding `setUp` and `tearDown` phpunit methods.

## [2.2.0] - 2021-07-02

### Added
- Compatibility with eShop compilation 6.3

### Fixed
- Tests fixed by newer phpunit versions

## [2.1.0] - 2020-12-03

### Changed
- Component oxid-esales/econda-tracking-component updated to 1.0.6

## [2.0.0] - 2020-06-17

### Added
- Ensure module works with PHP 7.2

### Changed
- Started using `oxid-esales/econda-tracking-component`
- Drop support of PHP 7.0.
- Replace privacy protection with mechanism provided by Econda
- Update econda-tracking-component version from 1.0.4 to 1.0.5

### Removed
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationHasVariants()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetSku()`
- `OxidEsales\PersonalizationModule\Application\Model\Product::oePersonalizationGetProductId()`

### Fixed
- Fix product feed to support multiple categories for one product [PR-6](https://github.com/OXID-eSales/personalization-module/pull/6).

## [1.3.2] - 2020-06-17

### Fixed
- Enclose the text values in quotes by RFC-4180 specification.
- Added timestamp to URL in jsFileLocator to allow caching [PR-4](https://github.com/OXID-eSales/personalization-module/pull/4)

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

[3.0.0]: https://github.com/OXID-eSales/personalization-module/compare/b-2.x...master
[2.2.0]: https://github.com/OXID-eSales/personalization-module/compare/v2.1.0...v2.2.0
[2.1.0]: https://github.com/OXID-eSales/personalization-module/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.4.0...v2.0.0
[1.4.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.3.2...v1.4.0
[1.3.2]: https://github.com/OXID-eSales/personalization-module/compare/v1.3.1...v1.3.2
[1.3.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.2...v1.3.0
[1.2.2]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.1...v1.2.2
[1.2.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/OXID-eSales/personalization-module/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/OXID-eSales/personalization-module/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/OXID-eSales/personalization-module/compare/df7baef7b886b1a983fe24e4f782b0954d076b1d...v1.0.0
