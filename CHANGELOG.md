# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Calendar Versioning](https://calver.org/).

## [Unreleased]

### Added
- `CommandAssertions` (extends `PHPUnit\Framework\Assert`): PHPUnit-style assertions for a command's name, class, description, arguments, options, visibility and exit code.
- `TestableCommand` fluent wrapper, reached via `$this->command(name|class|instance)`, with `has*`/`is*` predicates and `assert*`/`run` methods.
- `InteractsWithEnvironment` for overriding environment variables with automatic restoration.
- `runCommand()` returning a `CommandResult`, and `runCommandCapturingStreams()` capturing stdout and stderr separately (with `assertErrorOutput*` assertions).
- Documentation: real README quickstart, new `command-assertions` and `environment` pages.

### Changed
- `AcornTestCase` composes the interaction traits (commands, config, container, dispatcher, environment, exceptions, prompts, progress) and auto-restores config and environment in `tearDown()`.
- `AcornTestCase` loads the app under test's own `app/Config` directory alongside the framework core config, mirroring the runtime `Kernel`.
- `CommandResult` is `Stringable` and carries `errorOutput`.

### Fixed
- `FakeDispatcher` records each emitted event exactly once (it previously double-counted).
- `myerscode/acorn-framework` is a runtime `require` (it was dev-only, so a direct consumer without the framework could fatal).

### Removed
- The orphaned, unused `CommandResponse` class (`CommandResult` is the single result type).
