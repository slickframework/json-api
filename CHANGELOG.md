# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v1.2.1] 2023-11-22
### Adds
- `HttpMessageParserInterface` for better framework (PHPUnit) test integrations

## [v1.2.0] 2023-11-21
### Adds
- Support for enums on attribute schema discovery
- `ResourceAttribute::$factory` can be used to set a factory method when decoding
  a JSON API request object
- `ResourceAttribute::$getter` can be used to specify a getter method on a non
  `Stringable` object
### Changes
- composer dependencies versions to be more compatible with other projects
### Removes
- Support for PHP < 8.1

## [v1.1.3] 2023-10-27
### Fixes
- Null value assignment to properties that uses classNames (such as value objects).

## [v1.1.2] 2023-04-26
### Fixes
- Resource schema document links where not used if there was links previously defined
- Resource schema document meta where not used if there was meta previously defined
- Document decoder fails on optional relationship parsing if relationship entry was not
  present on request JSONAPI object.

## [v1.1.0] 2023-04-23
### Adds
- Verify if parent class (if exists) has schema attributes solving issues with proxy classes
  like doctrine/orm proxies.

## [v1.0.1] 2023-04-21
### Fixes
- Links and meta not showing in includes of a compound document.

## [v1.0.0] 2023-04-17
### Adds
- First full/complete functionality version

### Fixes
- Resource links appearing duplicated in data and document sections.
- Resource meta appearing duplicated in data and document sections.
- Links can have names other then rel by passing a `LinkObject` to the collection.

## [v0.11.2] 2023-03-20
### Fixes
- psr/http-message composer require version

## [v0.11.1] 2023-03-20
### Fixes
- Bug when using not required resource identifier, and there was defined a class for 
  value object, the valur was generated passing null to the constructor argument.

## [v0.11.0] 2023-02-03
### Adds
- In `AsResourceObject` attribute meta and links can hold method names

## [v0.10.4] 2023-01-31
### Fixes
- `DafaultFactory` wasn't adding meta and links on collection location
- deprecation warning when using PHP8.1 or higher


## [v0.10.3] 2022-12-21
### Adds
- Optional required validation on `RelationshipIdentifier` attribute
- The ability do decode a `JsonSerializable` objects
### Fixes
- Composer removed `includeFile()` function witch breaks some tests.
  More at [composer site](https://getcomposer.org/changelog/2.5.0)

## [v0.10.0] 2022-12-19
### Added
- Attributes to generate schema from/to entities
### Fixes
- Removed the duplication on `meta` and `links` on resource objects and resource documents

## [v0.9.0] 2022-11-23
### Changed
- Slick `Message` and `Uri` usage changed to PSR-7 interfaces counterparts
### Removed
- `slick/http` dependency. Moved to composer `dev` section as it is used on tests
 
## [v0.8.0] 2022-04-04
### Added
- Full support for php 8.0.x && 8.0.x
- PHP 8.x interface implementations

## [v0.7.4] 2021-11-12
### Fixed
- sparse fields bug when no attributes or fields were sent

## [v0.7.3] 2021-10-27
### Fixed
- Missing `meta` and `links` data on `ResourceCollentio` creation from schema

## [v0.7.2] 2021-05-28
### Fixed
- Added `relationType` field in schema conversion to force relations type behavior

## [v0.7.0] 2021-05-16
### Added
- [Sparse fieldsets](https://jsonapi.org/format/1.1/#fetching-sparse-fieldsets) implementation

## [v0.6.3] 2021-05-15
### Fixed
- Recursive loop error when converting related resources

## [v0.6.2] 2021-05-14
### Added
- On `FailedValidation` exception, when adding an error if the error has no ID, one is created.

## [v0.6.1] 2021-05-03
### Fixed
- Error while encoding document to JSON

## [v0.6.0] 2021-05-03
### Added
- `DefaultEncoder::encode()` now adds the JSON:API version, links, link prefixes and
  metadata if present in the encoder.
- `SchemaDiscover::isConvertible()` to determine if a given object can be converted to a JSON:API document  

## [v0.5.0] 2021-05-02
### Added
- Document decoder validator
### Fixed
- `composer.json` warnings

## [v0.4.0] 2021-05-01
### Added
- Document decoder

## [v0.3.0] 2020-12-06
### Added
- Resource schema and mapped schema discovery
- Default document factory
- Default object encoder: Discover, create and convert data.

## [v0.2.0] 2020-11-30
### Added
- JsonApi object JSON serialization
- Member name validator
- Validators: Link relation validator and Link HREF language validator
- Links member and Link Object
- Relationships: ToOne and ToMany relationships
- Resource object interface: Resource identifier, Resource Object and Resource Collection
- Documents: ResourceDocument, MetaDocument, ErrorDocument and ResourceCompound
- JSON converter (convert Document to JSON string): PHPJsonConverter

## [v0.1.0] 2020-11-22
### Added
- JsonApi Object
- Meta's information object

[Unreleased]: https://github.com/slickframework/json-api/compare/v1.2.1...HEAD
[v1.2.1]: https://github.com/slickframework/json-api/compare/v1.2.0...v1.2.1
[v1.2.0]: https://github.com/slickframework/json-api/compare/v1.1.3...v1.2.0
[v1.1.3]: https://github.com/slickframework/json-api/compare/v1.1.2...v1.1.3
[v1.1.2]: https://github.com/slickframework/json-api/compare/v1.1.0...v1.1.2
[v1.1.0]: https://github.com/slickframework/json-api/compare/v1.0.1...v1.1.0
[v1.0.1]: https://github.com/slickframework/json-api/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/slickframework/json-api/compare/v0.11.2...v1.0.0
[v0.11.2]: https://github.com/slickframework/json-api/compare/v0.11.1...v0.11.2
[v0.11.1]: https://github.com/slickframework/json-api/compare/v0.11.0...v0.11.1
[v0.11.0]: https://github.com/slickframework/json-api/compare/v0.10.4...v0.11.0
[v0.10.4]: https://github.com/slickframework/json-api/compare/v0.10.3...v0.10.4
[v0.10.3]: https://github.com/slickframework/json-api/compare/v0.10.0...v0.10.3
[v0.10.0]: https://github.com/slickframework/json-api/compare/v0.9.0...v0.10.0
[v0.9.0]: https://github.com/slickframework/json-api/compare/v0.8.0...v0.9.0
[v0.8.0]: https://github.com/slickframework/json-api/compare/v0.7.4...v0.8.0
[v0.7.4]: https://github.com/slickframework/json-api/compare/v0.7.3...v0.7.4
[v0.7.3]: https://github.com/slickframework/json-api/compare/v0.7.2...v0.7.3
[v0.7.2]: https://github.com/slickframework/json-api/compare/v0.7.0...v0.7.2
[v0.7.0]: https://github.com/slickframework/json-api/compare/v0.6.3...v0.7.0
[v0.6.3]: https://github.com/slickframework/json-api/compare/v0.6.2...v0.6.3
[v0.6.2]: https://github.com/slickframework/json-api/compare/v0.6.1...v0.6.2
[v0.6.1]: https://github.com/slickframework/json-api/compare/v0.6.0...v0.6.1
[v0.6.0]: https://github.com/slickframework/json-api/compare/v0.5.0...v0.6.0
[v0.5.0]: https://github.com/slickframework/json-api/compare/v0.4.0...v0.5.0
[v0.4.0]: https://github.com/slickframework/json-api/compare/v0.3.0...v0.4.0
[v0.3.0]: https://github.com/slickframework/json-api/compare/v0.2.0...v0.3.0
[v0.2.0]: https://github.com/slickframework/json-api/compare/v0.1.0...v0.2.0
[v0.1.0]: https://github.com/slickframework/json-api/compare/51d2e9...v0.1.0