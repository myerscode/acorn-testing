# Acorn Ecosystem

## Local Development Setup

The acorn ecosystem consists of four interdependent repositories:

- **acorn-framework** (`~/Code/acorn-framework`) — Core framework library. Everything depends on this.
- **acorn-testing** (`~/Code/acorn-testing`) — Testing helpers built on PHPUnit. Depends on acorn-framework.
- **acorn** (`~/Code/acorn`) — Project skeleton/template. Depends on acorn-framework, acorn-cli, and acorn-testing.
- **acorn-cli** (`~/Code/acorn-cli`) — CLI tool for scaffolding. Depends on acorn-framework and acorn-testing.

## Rules

- When updating, testing, or making changes to acorn, acorn-cli, or acorn-testing, always use the **local version** of acorn-framework (resolved via path repositories as `dev-main`).
- All three consumer packages have path repositories configured pointing to `~/Code/acorn-framework`. Do not remove or change these.
- Run tests against the local framework checkout to ensure changes work in harmony across the ecosystem.
- Non-framework dependencies should use the latest stable version where a compatible release exists. Only use `dev-main` when no stable release satisfies the version constraints.
- **Never commit code that references local paths.** The path repository configuration lives in each package's `composer.json` under `repositories` and must NOT be committed. If it is already committed (legacy), do not modify it — but never add new local path references to tracked files. Composer.lock files are gitignored for this reason.
