<p align="center">
  <img src="art/logo-small.webp" alt="Logo" />
</p>

# BlueSky SDK for PHP

![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/shahmal1yev/blueskysdk?label=latest&style=flat)
![GitHub last commit](https://img.shields.io/github/last-commit/shahmal1yev/blueskysdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
![Packagist Downloads](https://img.shields.io/packagist/dt/shahmal1yev/blueskysdk)
[![Discord](https://img.shields.io/badge/Discord-join%20server-5865F2?style=flat&logo=discord&logoColor=white)](https://discord.gg/tDajgYtBsZ)

## 🌟 Overview

BlueSky SDK is a comprehensive PHP library designed to seamlessly integrate with the BlueSky social network.

### Key Features
- Create posts with text, links, and media
- Retrieve and manage user profiles
- Search posts with keywords and filters
- Lightweight and intuitive API design
- Follows modern PHP standards

## 📦 Installation

### Requirements
- PHP 7.4 or newer
- Composer

### Install via Composer

```bash
composer require shahmal1yev/blueskysdk:"^1@beta"
```

Set up your credentials in `.env` file:

```env
BLUESKY_IDENTIFIER=your.identifier
BLUESKY_PASSWORD=your-secure-password
```

## 🏗️ Architecture Overview

### Lexicon Structure

The SDK uses a well-organized namespace structure that mirrors the AT Protocol's lexicon hierarchy

```php
use Atproto\Lexicons\{
    App\Bsky\Feed\Post,           // app.bsky.feed.post
    App\Bsky\Actor\Profile,       // app.bsky.actor.profile
    Com\Atproto\Repo\CreateRecord // com.atproto.repo.createRecord
};
```

### NSID (Namespaced Identifier)

Each operation in the SDK corresponds to a specific NSID (Namespaced Identifier) in the AT Protocol. For example

```text
- app.bsky.feed.post            -> Atproto\Lexicons\App\Bsky\Feed\Post
- app.bsky.actor.getProfile     -> Atproto\Lexicons\App\Bsky\Actor\GetProfile
```

### Smart Builder Pattern

The SDK implements a smart builder pattern using method chaining. This provides an intuitive way to construct API   
requests

```php
$response = $client->com()      // Navigate to 'com' namespace
    ->atproto()                 // Navigate to 'atproto' subspace
    ->repo()                    // Navigate to 'repo' operations
    ->createRecord()            // Select 'createRecord' operation
    ->forge()                   // Initialize the request builder
    ->record($post)             // Add content
    ->send();                   // Execute the request
```

### Response Handling

The SDK uses type-safe response objects that automatically cast API responses into convenient PHP objects

```php
// Getting a profile
$profile = $client->app()->bsky()->actor()->getProfile()->forge()
    ->actor($client->authenticated()->handle())
    ->send();

// Access data through typed methods
echo $profile->displayName();    // Returns string
echo $profile->followersCount(); // Returns int

/** @var \Carbon\Carbon $createdAt */
$createdAt = $profile->createdAt();      // Returns Carbon instance

// Response objects are iterable when representing collections
/** @var \Atproto\Responses\Objects\FollowersObject $response */
$response = $client->app()->bsky()->graph()->getFollowers()->forge()
    ->actor($profile->handle())
    ->send();

foreach ($response->followers() as $follower) {
    // Each $follower is a typed object with guaranteed methods
    /** @var \Atproto\Responses\Objects\FollowerObject $follower */
    
    echo sprintf(
        "%s joined on %s\n",
        $follower->handle(),
        $follower->createdAt()->format('Y-m-d')
    );
}
```

## 🚀 Quick Start

### Authentication

Authentication is the process of connecting to BlueSky's servers with your credentials.

```php
use Atproto\Client;

// Initialize the client
$client = new Client();

// Authenticate using environment variables
$client->authenticate(getenv('BLUESKY_IDENTIFIER'), getenv('BLUESKY_PASSWORD'));

echo $client->authenticated()->handle();
```

### Create a Simple Post

Easily create and send a text-based post to BlueSky.

```php
// Forge a new post with simple text
$post = $client->app()->bsky()->feed()->post()->forge()
    ->text("Hello, BlueSky!");

// Send the post to the server
$createdRecord = $client->com()->atproto()->repo()->createRecord()->forge()
    ->record($post) // Include the forged post
    ->repo($client->authenticated()->did()) // Specify the authenticated user's DID
    ->collection($post->nsid()) // Use the appropriate collection namespace
    ->send();

// Output the URI of the created post
echo $createdRecord->uri();
```

### Create a Post with an Image

Embed images into your posts for enhanced visual appeal.

```php
use \Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use \Atproto\Lexicons\App\Bsky\Embed\Image;

// Upload an image to the server
$uploadedBlob = $client->com()->atproto()->repo()->uploadBlob()->forge()
    ->blob('/path/to/image.jpg') // Specify the image path
    ->send()
    ->blob(); // Retrieve the uploaded blob metadata

// Forge a post embedding the uploaded image
$post = $client->app()->bsky()->feed()->post()->forge()
    ->text("Hello, BlueSky with an image!")
    ->embed(new ImageCollection([
        new Image($uploadedBlob, 'Image description') // Attach the image with alt text
    ]));

// Send the post and log the URI
$createdRecord = $client->com()->atproto()->repo()->createRecord()->forge()
    ->record($post)
    ->repo($client->authenticated()->did())
    ->collection($post->nsid())
    ->send();

echo $createdRecord->uri();
```

### Create a Post with External Links

Add external links with thumbnails for informative posts.

```php
// Upload an image for the link preview
$uploadedBlob = $client->com()->atproto()->repo()->uploadBlob()->forge()
    ->blob('/path/to/image.jpg')
    ->send()
    ->blob();

// Forge external link details
$external = $client->app()->bsky()->embed()->external()->forge(
    'https://example.com', // Link URL
    'Example Website',    // Link title
    'A description of the website.' // Link description
)->thumb($uploadedBlob); // Add the uploaded image as a thumbnail

// Forge a post including the external link
$post = $client->app()->bsky()->feed()->post()->forge()
    ->text("Check out this website!")
    ->embed($external);

// Send the post and retrieve the URI
$createdRecord = $client->com()->atproto()->repo()->createRecord()->forge()
    ->record($post)
    ->repo($client->authenticated()->did())
    ->collection($post->nsid())
    ->send();

echo $createdRecord->uri();
```

### Search Posts

Search for posts on BlueSky using keywords or filters.

```php
// Perform a keyword search on posts
$response = $client->app()->bsky()->feed()->searchPosts()->forge('keyword')
    ->send();

// Loop through and display the search results
foreach ($response->posts() as $post) {
    echo $post->record()->text() . "\n";
}
```

### Serialization

Any lexicon can be serialized

```php
use \Atproto\Client as LexiconSmith;
use \Atproto\DataModel\Blob\Blob;
use \Atproto\Lexicons\App\Bsky\RichText\RichText;

$smith = new LexiconSmith();

$post = $smith->app()->bsky()->feed()->post()->forge()
    ->text("Hello, BlueSky!\n\n")
    ->text("This post was sent via ")
    ->link("https://blueskysdk.shahmal1yev.dev", "Bluesky PHP SDK")
    ->text(". It was built by ")
    ->mention("did:plc:bdkw6ic5ugy6ni4pqvljcpva", "shahmal1yev")
    ->text("\n\n")
    ->text(
        RichText::tag("php", "PHP"),
        " ",
        RichText::tag("bsky_sdk", "Bsky SDK")
    )
    ->embed(
        $smith->app()->bsky()->embed()->external()->forge(
            'https://blueskysdk.shahmal1yev.dev',
            'Bluesky PHP SDK',
            'Official documentation of the BlueSky PHP SDK'
        )
        ->thumb(Blob::viaArray([
            'size' => 1000,
            'ref' => [
                '$link' => '...'
            ],
            'mimeType' => 'image/png',
        ]))
    );

echo json_encode($post, JSON_PRETTY_PRINT);
```

<details>
<summary>Result</summary>

```json
{
    "$type": "app.bsky.feed.post",
    "createdAt": "2024-12-13T10:43:33+00:00",
    "text": "Hello, BlueSky!\n\nThis post was sent via Bluesky PHP SDK. It was built by @shahmal1yev\n\n#PHP #Bsky SDK",
    "facets": [
        {
            "index": {
                "byteStart": 40,
                "byteEnd": 55
            },
            "features": [
                {
                    "$type": "app.bsky.richtext.facet#link",
                    "label": "Bluesky PHP SDK",
                    "uri": "https:\/\/blueskysdk.shahmal1yev.dev"
                }
            ]
        },
        {
            "index": {
                "byteStart": 73,
                "byteEnd": 85
            },
            "features": [
                {
                    "$type": "app.bsky.richtext.facet#mention",
                    "label": "@shahmal1yev",
                    "did": "did:plc:bdkw6ic5ugy6ni4pqvljcpva"
                }
            ]
        },
        {
            "index": {
                "byteStart": 87,
                "byteEnd": 91
            },
            "features": [
                {
                    "$type": "app.bsky.richtext.facet#tag",
                    "label": "#PHP",
                    "tag": "php"
                }
            ]
        },
        {
            "index": {
                "byteStart": 92,
                "byteEnd": 101
            },
            "features": [
                {
                    "$type": "app.bsky.richtext.facet#tag",
                    "label": "#Bsky SDK",
                    "tag": "bsky_sdk"
                }
            ]
        }
    ],
    "embed": {
        "$type": "app.bsky.embed.external",
        "external": {
            "uri": "https:\/\/blueskysdk.shahmal1yev.dev",
            "title": "Bluesky PHP SDK",
            "description": "Official documentation of the BlueSky PHP SDK",
            "thumb": {
                "$type": "blob",
                "ref": {
                    "$link": "..."
                },
                "mimeType": "image\/png",
                "size": 1000
            }
        }
    }
}
```

</details>

## 📝 Documentation

For more examples and detailed usage, visit the [official SDK documentation](https://blueskysdk.shahmal1yev.dev).

## 🤝 Contributing

--

## 📝 License

Released under the MIT License. See [LICENSE](LICENSE) for details.

## 🙋‍♂️ Support

- **Docs**: [SDK Documentation](https://blueskysdk.shahmal1yev.dev)
- **Issues**: [GitHub Issues](https://github.com/shahmal1yev/blueskysdk/issues)
- **Discord**: [Join Community](https://discord.gg/tDajgYtBsZ)

---
Built with ❤️ by [Eldar Shahmaliyev](https://shahmal1yev.dev/about).
