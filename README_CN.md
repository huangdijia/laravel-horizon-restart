# Laravel Horizon Restart

[![Latest Test](https://github.com/huangdijia/laravel-horizon-restart/workflows/tests/badge.svg)](https://github.com/huangdijia/laravel-horizon-restart/actions)
[![Latest Stable Version](https://poser.pugx.org/huangdijia/laravel-horizon-restart/v)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Total Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/downloads)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Monthly Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/d/monthly)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![Daily Downloads](https://poser.pugx.org/huangdijia/laravel-horizon-restart/d/daily)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![License](https://poser.pugx.org/huangdijia/laravel-horizon-restart/license)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
[![PHP Version Require](https://poser.pugx.org/huangdijia/laravel-horizon-restart/require/php)](https://packagist.org/packages/huangdijia/laravel-horizon-restart)

[English Documentation](README.md)

一个 Laravel 扩展包，为 Laravel Horizon 添加跨多服务器重启监控器的能力，类似于 `php artisan queue:restart`，但专门为 Horizon 工作进程设计。

## 特性

- 🚀 通过单个命令重启所有服务器上的 Horizon 监控器
- 🔄 类似 `queue:restart` 的优雅终止方式
- 📦 自动注册服务提供者
- 🎯 与 Horizon 的队列分发无缝协作
- ⚡ Horizon 工作进程零停机部署

## 系统要求

- PHP 8.2 或更高版本
- Laravel 11.x 或 12.x
- Laravel Horizon 5.x 或 6.x

## 安装

通过 Composer 安装扩展包：

```bash
composer require huangdijia/laravel-horizon-restart
```

服务提供者会自动注册。

## 使用

### 基本用法

重启所有服务器上的所有 Horizon 监控器：

```bash
php artisan horizon:restart
```

此命令将：
1. 发现所有运行中的 Horizon 监控器
2. 向每个监控器的队列分发一个重启任务
3. 优雅地终止每个监控器（类似于 `horizon:terminate`）
4. Horizon 将自动重启这些监控器

### 工作原理

当你运行 `horizon:restart` 时，扩展包会：

1. **发现监控器**：查询 Horizon 主监控器仓库，找到所有活动的监控器
2. **分发任务**：为每个监控器创建一个 `HorizonRestartJob` 并将其分发到该监控器的队列
3. **执行终止**：每个任务在各自的服务器上运行并在本地调用 `horizon:terminate`
4. **自动重启**：Horizon 的进程监控会自动重启被终止的监控器

这种方式确保：
- 所有服务器都收到重启信号
- 工作进程在终止前完成当前任务
- 重启过程中不会丢失任何任务
- 每个服务器独立重启

## 使用场景

此扩展包特别适用于：

- **多服务器部署**：当 Horizon 在多个服务器上运行，需要重启所有服务器时
- **代码部署**：部署新代码后，重启所有服务器上的所有工作进程
- **配置变更**：当 Horizon 配置发生变化，所有工作进程需要重新加载时
- **内存管理**：定期重启以清理内存泄漏或重置工作进程状态

## 配置

扩展包会自动配置一个专门用于处理重启任务的特殊监控器。无需手动配置。

重启监控器会自动配置为：
- 连接：使用与第一个 Horizon 环境相同的连接
- 队列：使用 Horizon 的内部队列名称
- 进程：1 个工作进程处理重启任务
- 重试：每个任务 3 次尝试

## 与其他方法的比较

| 方法 | 单服务器 | 多服务器 | 优雅终止 |
|------|---------|---------|---------|
| `horizon:terminate` | ✅ | ❌ | ✅ |
| `queue:restart` | ✅ | ✅ | ✅ |
| `horizon:restart`（本扩展包）| ✅ | ✅ | ✅ |

## 开发

### 测试

运行测试套件：

```bash
composer test
```

### 代码风格

修复代码风格问题：

```bash
composer cs-fix
```

### 静态分析

运行静态分析：

```bash
composer analyse
```

## 贡献

欢迎贡献！请随时提交 Pull Request。

## 安全

如果你发现任何安全相关的问题，请发送邮件至 huangdijia@gmail.com，而不是使用问题跟踪器。

## 许可证

MIT 许可证（MIT）。详情请参阅 [许可证文件](LICENSE)。

## 致谢

- [huangdijia](https://github.com/huangdijia)
- [所有贡献者](https://github.com/huangdijia/laravel-horizon-restart/contributors)

## 链接

- [文档](https://github.com/huangdijia/laravel-horizon-restart/blob/main/README.md)
- [Packagist](https://packagist.org/packages/huangdijia/laravel-horizon-restart)
- [GitHub](https://github.com/huangdijia/laravel-horizon-restart)
