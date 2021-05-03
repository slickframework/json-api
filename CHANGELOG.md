# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
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

[Unreleased]: https://github.com/slickframework/json-api/compare/v0.3.0...HEAD
[v0.3.0]: https://github.com/slickframework/json-api/compare/v0.2.0...v0.3.0
[v0.2.0]: https://github.com/slickframework/json-api/compare/v0.1.0...v0.2.0
[v0.1.0]: https://github.com/slickframework/json-api/compare/51d2e9...v0.1.0