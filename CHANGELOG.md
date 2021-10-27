# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
### Fixes
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

[Unreleased]: https://github.com/slickframework/json-api/compare/v0.7.3...HEAD
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