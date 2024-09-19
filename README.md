<p align="center">
  <img src="art/logo-small.webp" alt="Logo" />
</p>

# BlueSky SDK

![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/shahmal1yev/blueskysdk?label=latest&style=flat)
![Packagist Downloads](https://img.shields.io/packagist/dt/shahmal1yev/blueskysdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
![GitHub last commit](https://img.shields.io/github/last-commit/shahmal1yev/blueskysdk)
![GitHub issues](https://img.shields.io/github/issues/shahmal1yev/blueskysdk)
![GitHub stars](https://img.shields.io/github/stars/shahmal1yev/blueskysdk)
![GitHub forks](https://img.shields.io/github/forks/shahmal1yev/blueskysdk)
![GitHub contributors](https://img.shields.io/github/contributors/shahmal1yev/blueskysdk)

## Project Description

BlueSky SDK is a PHP library used for interacting with the [BlueSky API](https://docs.bsky.app/docs/get-started). This library allows you to perform various
operations using the BlueSky API.

## Requirements

- **PHP**: 7.4 or newer
- **Composer**: [Dependency management tool](https://getcomposer.org/) for PHP

## Installation

To install BlueSky SDK via Composer, use the following command:

```bash
composer require shahmal1yev/blueskysdk
```

## Usage

Once installed, you can start using the SDK to interact with the BlueSky API. Below are examples of how to 
authenticate and perform various operations using the library.

### Authentication and Basic Usage

First, instantiate the `Client` class and authenticate using your BlueSky credentials:

```php
use Atproto\Client;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

$client = new Client();

// Authenticate using your identifier (e.g., email) and password
$client->authenticate($identifier, $password);

// Once authenticated, you can retrieve the user's session resource
/** @var CreateSessionResource $session */
$session = $client->authenticated();
```

### Making Requests

BlueSky SDK provides a fluent interface to construct API requests. Use chained method calls to navigate through the 
API lexicons and forge the request:

```php
use Atproto\Contracts\ResourceContract;

// Example: Fetching a profile
$profile = $client->app()
                  ->bsky()
                  ->actor()
                  ->getProfile()
                  ->forge()
                  ->actor('some-actor-handle') // Specify the actor handle
                  ->send();
```

### Handling Responses

BlueSky SDK supports both Resource and Castable interfaces, providing flexibility in handling API responses and 
enabling smooth data manipulation and casting for a more streamlined development experience.

Responses are returned as resource instances that implement the `ResourceContract`. These resources provide methods 
for accessing data returned by the API.

```php
// Retrieve properties from the profile
/** @var string $displayName */
$displayName = $profile->displayName();

/** @var Carbon\Carbon $createdAt */
$createdAt = $profile->createdAt();
```

### Working with Assets and Relationships

BlueSky SDK allows you to access complex assets like followers and labels directly through the resource instances.

```php
use Atproto\Resources\Assets\FollowersAsset;
use Atproto\Resources\Assets\FollowerAsset;

// Fetch the user's followers
/** @var FollowersAsset<FollowerAsset> $followers */
$followers = $profile->viewer()
                     ->knownFollowers()
                     ->followers();

foreach ($followers as $follower) {
    /** @var FollowerAsset $follower */
    echo $follower->displayName() . " - Created at: " . $follower->createdAt()->format(DATE_ATOM) . "\n";
}
```

### Example: Fetching Profile Information

Here is a more complete example of fetching and displaying profile information, including created dates and labels:

```php
use Atproto\Client;
use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;

$client->authenticate('user@example.com', 'password');

$client->app()
       ->bsky()
       ->actor()
       ->getProfile()
       ->forge();
       // ->actor($client->authenticated()->did());

/** @var GetProfileResource $user */
$user = $client->send();

// Output profile details
echo "Display Name: " . $user->displayName() . "\n";
echo "Created At: " . $user->createdAt()->toDateTimeString() . "\n";

// Accessing and iterating over followers
$followers = $user->viewer()->knownFollowers()->followers();

foreach ($followers as $follower) {
    echo $follower->displayName() . " followed on " . $follower->createdAt()->format(DATE_ATOM) . "\n";
}
```

### Extending the SDK

BlueSky SDK is built with extensibility in mind. You can add custom functionality by extending existing classes and 
creating your own request and resource types. Follow the structure used in the SDK to maintain consistency.

## Contribution

We welcome contributions from the community! If you find any bugs or would like to add new features, feel free to:

- **Open an issue**: Report bugs, request features, or suggest improvements.
- **Submit a pull request**: Contributions to the codebase are welcome. Please follow best practices and ensure that your code adheres to the existing architecture and coding standards.

## License

BlueSky SDK is licensed under the MIT License. See the LICENSE file for full details.
