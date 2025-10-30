# Laravel Horizon Restart

[![Latest Test](https://github.com/huangdijia/laravel-horizon-restart/workflows/tests/badge.svg)](https://github.com/huangdijia/laravel-horizon-restart/actions)
[![Latest Stable Version](https://poser.pugx.org/huangdijia/laravel-horizon-restart/v)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Total Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/downloads)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Monthly Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/d/monthly)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Daily Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/d/daily)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![License](https://poser.pugx.org/huangdijia/laravel-horizon-restart/license)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![PHP Version Require](https://poser.pugx.org/huangdijia/laravel-horizon-restart/require/php)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)

[中文文档](README_CN.md)

A Laravel package that extends Laravel Horizon with the ability to restart supervisors across multiple servers, similar to `php artisan queue:restart` but specifically designed for Horizon workers.

## Features

- 🚀 Restart all Horizon supervisors across multiple servers with a single command
- 🔄 Graceful termination of workers similar to `queue:restart`
- 📦 Automatic service provider registration
- 🎯 Works seamlessly with Horizon's queue distribution
- ⚡ Zero downtime deployments for Horizon workers

## Requirements

- PHP 8.2 or higher
- Laravel 11.x or 12.x
- Laravel Horizon 5.x or 6.x

## Installation

Install the package via Composer:

```bash
composer require huangdijia/laravel-horizon-restart
```

The service provider will be automatically registered.

## Usage

### Basic Usage

To restart all Horizon supervisors across all servers:

```bash
php artisan horizon:restart
```

This command will:
1. Discover all running Horizon supervisors
2. Dispatch a restart job to each supervisor's queue
3. Gracefully terminate each supervisor (similar to `horizon:terminate`)
4. Horizon will automatically restart the supervisors

### How It Works

When you run `horizon:restart`, the package:

1. **Discovers Supervisors**: Queries the Horizon master supervisor repository to find all active supervisors
2. **Dispatches Jobs**: Creates a `HorizonRestartJob` for each supervisor and dispatches it to that supervisor's queue
3. **Executes Termination**: Each job runs on its respective server and calls `horizon:terminate` locally
4. **Auto Restart**: Horizon's process monitoring automatically restarts terminated supervisors

This approach ensures that:
- All servers receive the restart signal
- Workers finish their current jobs before terminating
- No jobs are lost during the restart process
- Each server restarts independently

## Use Cases

This package is particularly useful for:

- **Multi-Server Deployments**: When you have Horizon running on multiple servers and need to restart all of them
- **Code Deployments**: After deploying new code, restart all workers across all servers
- **Configuration Changes**: When Horizon configuration changes and all workers need to reload
- **Memory Management**: Periodic restarts to clear memory leaks or reset worker state

## Configuration

The package automatically configures a special supervisor for handling restart jobs. No manual configuration is required.

The restart supervisor is automatically configured with:
- Connection: Uses the same connection as your first Horizon environment
- Queue: Uses Horizon's internal queue name
- Processes: 1 worker to handle restart jobs
- Tries: 3 attempts per job

## Comparison with Other Methods

| Method | Single Server | Multiple Servers | Graceful |
|--------|--------------|------------------|----------|
| `horizon:terminate` | ✅ | ❌ | ✅ |
| `queue:restart` | ✅ | ✅ | ✅ |
| `horizon:restart` (this package) | ✅ | ✅ | ✅ |

## Development

### Testing

Run the test suite:

```bash
composer test
```

### Code Style

Fix code style issues:

```bash
composer cs-fix
```

### Static Analysis

Run static analysis:

```bash
composer analyse
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security related issues, please email huangdijia@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [huangdijia](https://github.com/huangdijia)
- [All Contributors](https://github.com/huangdijia/laravel-horizon-restart/contributors)

## Links

- [Documentation](https://github.com/huangdijia/laravel-horizon-restart/blob/main/README.md)
- [Packagist](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
- [GitHub](https://github.com/huangdijia/laravel-horizon-restart)
