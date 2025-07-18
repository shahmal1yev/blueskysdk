<p align="center">
  <img src="art/logo-small.webp" alt="Logo" />
</p>

# BlueSky SDK for PHP

[![PHP Workflow](https://github.com/shahmal1yev/blueskysdk/actions/workflows/php.yml/badge.svg)](https://github.com/shahmal1yev/blueskysdk/actions/workflows/php.yml)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/shahmal1yev/blueskysdk?label=latest&style=flat)
![GitHub last commit](https://img.shields.io/github/last-commit/shahmal1yev/blueskysdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
![Packagist Downloads](https://img.shields.io/packagist/dt/shahmal1yev/blueskysdk)
[![Discord](https://img.shields.io/badge/Discord-join%20server-5865F2?style=flat&logo=discord&logoColor=white)](https://discord.gg/tDajgYtBsZ)

## 🌟 Overview

BlueSky SDK is a comprehensive PHP library designed to seamlessly integrate with the BlueSky social network.

## 📝 Documentation

Explore the [Wiki](https://github.com/shahmal1yev/blueskysdk/wiki) for documentation.

## 🚀 Quick Start

### Installation

```bash
composer require shahmal1yev/blueskysdk
```

### Basic Usage

```php
<?php

use Atproto\Client;

// Create a client instance
$client = new Client();

// Authenticate with BlueSky
$client->authenticate('your-handle', 'your-password');

// Get your profile
$profile = bskyFacade($client)->getProfile()
    ->actor($client->authenticated()->handle())
    ->send();

// Get the date you joined
$createdAt = $profile->createdAt();
```

## 📋 Requirements

- PHP 7.4 or higher
- Extensions: `json`, `curl`, `fileinfo`
- Composer

## 🧪 Testing

```bash
# Run all tests
composer test

# Run unit tests only
composer test-unit

# Run feature tests only  
composer test-feature

# Static analysis
composer analyse
```

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details on:

- Code standards (PSR, SOLID principles)
- Development setup and workflow
- Testing requirements
- Pull request process

Before contributing, please:
1. Read the [Contributing Guide](CONTRIBUTING.md)
2. Check existing issues and pull requests
3. Join our [Discord community](https://discord.gg/tDajgYtBsZ) for discussions

## 📝 License

Released under the MIT License. See [LICENSE](LICENSE) for details.

## 🙋‍♂️ Support

- **Issues**: [GitHub Issues](https://github.com/shahmal1yev/blueskysdk/issues)
- **Discord**: [Join Community](https://discord.gg/tDajgYtBsZ)

---
Built with ❤️ by [Eldar Shahmaliyev](https://shahmal1yev.dev/about).
