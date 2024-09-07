# BlueskySDK

![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/shahmal1yev/blueskysdk?label=latest&style=flat)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
![GitHub last commit](https://img.shields.io/github/last-commit/shahmal1yev/blueskysdk)
![GitHub issues](https://img.shields.io/github/issues/shahmal1yev/blueskysdk)
![GitHub stars](https://img.shields.io/github/stars/shahmal1yev/blueskysdk)
![GitHub forks](https://img.shields.io/github/forks/shahmal1yev/blueskysdk)
![GitHub contributors](https://img.shields.io/github/contributors/shahmal1yev/blueskysdk)

## Project Description

Bluesky SDK is a PHP library used for interacting with the Bluesky API. This library allows you to perform various operations using the Bluesky API.

## Requirements

```json
"require-dev": {
    "phpunit/phpunit": "9.6.20",
    "fakerphp/faker": "^1.23"
},
"require": {
    "ext-json": "*",
    "ext-curl": "*",
    "ext-fileinfo": "*",
    "php": ">=7.4",
    "nesbot/carbon": "2.x",
    "shahmal1yev/gcollection": "^1.0"
}
```

## Installation

```shell
composer require shahmal1yev/blueskysdk
```

## Usage

After including the library in your project, you can refer to the following examples:

### Get Profile

```php
use Atproto\Clients\BlueskyClient;
use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;
use Atproto\Resources\Assets\LabelsAsset;
use Atproto\Resources\Assets\LabelAsset;
use Atproto\Resources\Assets\FollowersAsset;
use Atproto\Resources\Assets\FollowerAsset;

$client = new BlueskyClient(new GetProfile());

$client->authenticate([
    'identifier' => 'user@example.com',
    'password' => 'password'
]);

/** @var GetProfileResource $user */
$user = $client->send();

/** @var Carbon\Carbon $created */
$created = $user->createdAt();

/** @var LabelsAsset<LabelAsset> $labels */
$labels = $user->labels();

/** @var FollowersAsset $knownFollowers */
$knownFollowers = $user->viewer()
                    ->knownFollowers()
                    ->followers();

foreach($knownFollowers as $follower) {
    /** @var FollowerAsset $follower */
    
    $name = $follower->displayName();
    $createdAt = $follower->createdAt()->format(DATE_ATOM);
    
    echo "$name's account created at $createdAt";
}
```

## Contribution
- If you find any bug or issue, please open an issue.
- If you want to contribute to the code, feel free to submit a pull request.

## License

This project is licensed under the MIT License. For more information, see the LICENSE file.