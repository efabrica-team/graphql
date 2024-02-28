# Changelog

## [Unreleased]

## [0.2.3] - 2024-02-28
### Added
- Allow non-nullable field argument

## [0.2.2] - 2024-01-26
### Added
- Having types and fields
- GroupType instead of anonymous declaration in GroupField
- UnionType

### Changed
- Depracated GroupField::FIELD_COLUMN constant, use GroupType::FIELD_COLUMN instead

## [0.2.1] - 2023-07-03
### Added
- Null and NotNull where comparators

## [0.2.0] - 2023-04-06
### Added
- Multi schema loader
- Ability to add additional data to response
- GraphQL exception that can contain both safe message and debug message
- Debug mode to WebonyxDriver

### Changed
- Type with fields will combine fields from callback and predefined fields
- Webonyx schema transformer clears cache after transforming schema 
- OrderArgument key changed to 'column' and order to 'sort_order'

## [0.1.0] - 2022-10-24
### Added
- Schema definition
- Definition schema loader
- Webonyx driver
- Webonyx schema transformer

[Unreleased]: https://github.com/efabrica-team/graphql/compare/0.2.3...main
[0.2.3]: https://github.com/efabrica-team/graphql/compare/0.2.2...0.2.3
[0.2.2]: https://github.com/efabrica-team/graphql/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/efabrica-team/graphql/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/efabrica-team/graphql/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/efabrica-team/graphql/compare/0.0.0...0.1.0
