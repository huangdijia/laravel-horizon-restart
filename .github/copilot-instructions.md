# Laravel Horizon Restart Package

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

Laravel Horizon Restart is a Laravel package that extends Laravel Horizon with the ability to restart supervisors across multiple servers, similar to `php artisan queue:restart` but specifically for Horizon workers.

## Working Effectively

### Bootstrap and Install Dependencies
- Install PHP 8.2+ (required minimum version)
- Run `composer install` to install all dependencies
- **TIMEOUT WARNING**: Composer install can take 5-10 minutes due to GitHub rate limits. NEVER CANCEL. Set timeout to 15+ minutes.
- **GITHUB TOKEN REQUIRED**: Composer will prompt for GitHub token due to API rate limits:
  - Use: https://github.com/settings/tokens/new?scopes=&description=Composer
  - This is NORMAL and required for development
  - Token only needs public repo read access
- Alternative: `composer install --no-dev` installs only production dependencies (faster)

### Code Quality and Analysis
- **REQUIREMENT**: Full dev dependencies must be installed first (see Bootstrap section)
- Run `composer cs-fix` to fix code style issues using PHP CS Fixer
- Run `composer analyse` to run PHPStan static analysis (level 0, memory limit 300M)
- **TIMEOUT WARNING**: Analysis can take 2-3 minutes. NEVER CANCEL. Set timeout to 5+ minutes.
- Both commands MUST pass before committing or CI will fail
- **NOTE**: Without GitHub token, dev tools won't be available locally

### Testing
- **REQUIREMENT**: Full dev dependencies must be installed first (see Bootstrap section)
- Run `composer test` to execute the Pest test suite
- **TIMEOUT WARNING**: Tests typically take 30-60 seconds. NEVER CANCEL. Set timeout to 3+ minutes.
- Tests are minimal but ensure basic functionality works
- Integration testing happens in CI with real Laravel applications
- **NOTE**: Without GitHub token, test tools won't be available locally

### Development Workflow
- This package has NO runtime dependencies on a Laravel application for development
- The package provides a service provider, console command, and background job
- All development work can be done by editing the source files directly
- Changes should be tested against the existing test suite
- Use `php -l <filename>` to syntax check individual files
- Use `composer dump-autoload` to regenerate autoloader without full install

## Validation

### Manual Testing Scenarios
Since this package integrates with Laravel Horizon, manual testing requires a Laravel application:
- **CANNOT be validated in isolation** - requires full Laravel app with Horizon installed
- CI workflows demonstrate the complete validation process
- Review `.github/workflows/testing.yaml` to see integration test approach

### Required Validation Steps
- ALWAYS run `composer cs-fix` before committing
- ALWAYS run `composer analyse` before committing  
- ALWAYS run `composer test` before committing
- All three commands MUST pass for CI to succeed

## File Structure and Important Locations

### Source Code (src/)
- `Console/RestartCommand.php` - Main artisan command `horizon:restart`
- `Jobs/HorizonRestartJob.php` - Background job that calls `horizon:terminate`
- `RestartServiceProvider.php` - Laravel service provider for package registration

### Configuration
- `composer.json` - Package definition, dependencies, and scripts
- `phpstan.neon` - PHPStan static analysis configuration  
- `.php-cs-fixer.php` - PHP CS Fixer code style configuration
- `phpunit.xml` - PHPUnit/Pest test configuration

### Testing
- `tests/Pest.php` - Pest framework configuration
- `tests/Feature/CommandTest.php` - Basic feature tests
- `tests/TestCase.php` - Base test case class

### CI/CD
- `.github/workflows/tests.yaml` - Main CI pipeline
- `.github/workflows/testing.yaml` - Integration testing with Laravel
- `.github/workflows/release.yaml` - Release automation

## Common Tasks

### Repository Structure
```
.
├── .github/            # GitHub workflows and configuration
├── .php-cs-fixer.php  # Code style configuration
├── composer.json      # Package definition and scripts
├── phpstan.neon       # Static analysis configuration
├── phpunit.xml        # Test configuration
├── src/               # Package source code
│   ├── Console/       # Artisan commands
│   ├── Jobs/          # Queue jobs
│   └── RestartServiceProvider.php
└── tests/             # Test suite
    ├── Feature/       # Feature tests
    └── Pest.php       # Test configuration
```

### Package Functionality
This package provides:
1. `php artisan horizon:restart` command
2. Automatically dispatches `HorizonRestartJob` to each supervisor queue
3. Each job calls `horizon:terminate` on its respective server
4. Enables coordinated restart across multiple Horizon servers

### Key Dependencies
- PHP 8.2+
- Laravel 11+ or 12+
- Laravel Horizon 5+ or 6+
- Pest 3+ for testing
- PHPStan 2+ for analysis
- PHP CS Fixer for code style

### Composer Scripts Reference
- `composer cs-fix` - Fix code style with PHP CS Fixer (command: `@php vendor/bin/php-cs-fixer fix`)
- `composer analyse` - Run PHPStan static analysis (command: `@php vendor/bin/phpstan analyse --memory-limit 300M -l 0 -c phpstan.neon ./src`)
- `composer test` - Run Pest test suite (command: `@php vendor/bin/pest`)

### Basic Validation Without Full Install
- `composer dump-autoload` - Regenerate autoloader (30 seconds)
- `php -l <file>` - Syntax check individual PHP files (5 seconds per file)
- `composer run-script --list` - Show available scripts (5 seconds)

### Integration Testing Notes
The package includes comprehensive integration testing that:
- Creates a fresh Laravel application
- Installs Laravel Horizon
- Installs this package
- Starts Horizon workers
- Tests the restart functionality
- Verifies supervisors are properly terminated

**CRITICAL**: Always check CI workflows pass before merging changes, as they perform the complete integration validation that cannot be done in development environment alone.

## Troubleshooting

### GitHub Token Issues
If `composer install` fails with authentication errors:
1. Generate token at: https://github.com/settings/tokens/new?scopes=&description=Composer
2. Select only "Public repositories (read)" permission
3. Copy the token when prompted by composer
4. Token will be saved to `~/.composer/auth.json` for future use

### Alternative Development Approach
If unable to install dev dependencies:
1. Use `composer install --no-dev` for production dependencies only
2. Use `php -l` for syntax checking individual files
3. Rely on CI for full validation (code style, analysis, tests)
4. Push to feature branch to trigger CI validation