<p align="center">
  <img src="art/logo-small.webp" alt="Logo" />
</p>

# BlueSky SDK for PHP
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/shahmal1yev/blueskysdk?label=latest&style=flat)
[![Discord](https://img.shields.io/badge/Discord-join%20server-5865F2?style=flat&logo=discord&logoColor=white)](https://discord.gg/tDajgYtBsZ)
![Packagist Downloads](https://img.shields.io/packagist/dt/shahmal1yev/blueskysdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
![GitHub last commit](https://img.shields.io/github/last-commit/shahmal1yev/blueskysdk)
![GitHub issues](https://img.shields.io/github/issues/shahmal1yev/blueskysdk)
![GitHub stars](https://img.shields.io/github/stars/shahmal1yev/blueskysdk)
![GitHub forks](https://img.shields.io/github/forks/shahmal1yev/blueskysdk)
![GitHub contributors](https://img.shields.io/github/contributors/shahmal1yev/blueskysdk)

## 🌟 Overview

BlueSky SDK is a comprehensive PHP library designed to seamlessly integrate with the BlueSky social network. Built with developers in mind, it provides an intuitive and powerful interface for interacting with BlueSky's features. Whether you're building a social media management tool, content automation system, or just want to integrate BlueSky features into your existing application, this SDK offers all the tools you need.

### Key Features
- Rich text post creation with links, mentions, and hashtags
- Media management (image uploads and attachments)
- Profile and follower management
- Robust error handling
- Type-safe collections and responses
- Comprehensive testing suite
- Modern PHP practices and standards

## 📦 Installation & Requirements

### System Requirements
- PHP 7.4 or newer
- Composer package manager
- Required PHP extensions: json, curl, fileinfo

### Installation Steps

```bash
composer require shahmal1yev/blueskysdk
```

After installation, make sure to configure your environment variables for authentication:
```env
BLUESKY_IDENTIFIER=your.identifier
BLUESKY_PASSWORD=your-secure-password
```

## 🚀 Getting Started

## 🏗️ Architecture Overview

### Lexicon Structure
The SDK uses a well-organized namespace structure that mirrors the AT Protocol's lexicon hierarchy:

```php
use Atproto\Lexicons\{
    App\Bsky\Feed\Post,           // app.bsky.feed.post
    App\Bsky\Actor\Profile,       // app.bsky.actor.profile
    Com\Atproto\Repo\CreateRecord // com.atproto.repo.createRecord
};
```

### NSID (Namespaced Identifier)

Each operation in the SDK corresponds to a specific NSID (Namespaced Identifier) in the AT Protocol. For example:

```text
- app.bsky.feed.post            -> Atproto\Lexicons\App\Bsky\Feed\Post
- app.bsky.actor.getProfile     -> Atproto\Lexicons\App\Bsky\Actor\GetProfile
```

### Smart Builder Pattern

The SDK implements a smart builder pattern using method chaining. This provides an intuitive way to construct API 
requests:

```php
$response = $client->app()      // Navigate to 'app' namespace
    ->bsky()            // Navigate to 'bsky' subspace
    ->feed()            // Navigate to 'feed' operations
    ->post()            // Select 'post' operation
    ->forge()           // Initialize the request builder
    ->text("Hello!")    // Add content
    ->send();           // Execute the request
```

### Response Handling

The SDK uses type-safe response objects that automatically cast API responses into convenient PHP objects:

```php
// Getting a profile
$profile = $client->app()
    ->bsky()
    ->actor()
    ->getProfile()
    ->forge()
    ->actor('user.bsky.social')
    ->send();

// Access data through typed methods
echo $profile->displayName();    // Returns string
echo $profile->followersCount(); // Returns int

/** @var \Carbon\Carbon $createdAt */
$createdAt = $profile->createdAt();      // Returns Carbon instance

// Response objects are iterable when representing collections
/** @var \Atproto\Responses\Objects\FollowersObject $followers */
$followers = $client->app()
    ->bsky()
    ->graph()
    ->getFollowers()
    ->forge()
    ->actor('user.bsky.social')
    ->send();

foreach ($followers->followers() as $follower) {
    // Each $follower is a typed object with guaranteed methods
    /** @var \Atproto\Responses\Objects\FollowerObject $follower */
    
    echo sprintf(
        "%s joined on %s\n",
        $follower->handle(),
        $follower->createdAt()->format('Y-m-d')
    );
}
```

### Authentication
The first step to using the SDK is establishing a connection with BlueSky's servers. The SDK provides a straightforward authentication process:

```php
use Atproto\Client;

$client = new Client();

// Basic authentication
$client->authenticate('your.identifier', 'your-password');

// Or using environment variables (recommended)
$client->authenticate(
    getenv('BLUESKY_IDENTIFIER'),
    getenv('BLUESKY_PASSWORD')
);
```

## 📝 Content Creation

### Rich Text Posts
The SDK excels at creating engaging social media content with rich text features. Here's a comprehensive guide to creating various types of posts:

### Marketing Campaign Example
Perfect for social media managers running promotional campaigns:

```php
// Announcing a special promotion
$client->app()
    ->bsky()
    ->feed()
    ->post()
    ->forge()
    ->text(
        "🎉 Summer Sale Spectacular! 🌞\n\n",
        "Get ready for amazing deals on all our premium products! ",
        RichText::link('https://yourstore.com/summer-sale', 'Shop Now'),
        "\n\n✨ Highlights:\n",
        "• Up to 50% off on selected items\n",
        "• Free shipping worldwide\n",
        "• Limited time offers\n\n",
        "Questions? Ask ",
        RichText::mention('did:plc:support', 'our support team'),
        "!\n\n",
        RichText::tag('SummerSale', 'SummerSale'),
        " ",
        RichText::tag('ShopNow', 'ShopNow')
    )
    ->send();
```

### Tech Tutorial Series
Ideal for educational content and technical blogs:

```php
// Upload tutorial screenshot
$tutorialImage = $client->com()
    ->atproto()
    ->repo()
    ->uploadBlob()
    ->forge()
    ->token($client->authenticated()->accessJwt())
    ->blob('path/to/tutorial-screenshot.jpg')
    ->send()
    ->blob();

// Create in-depth tutorial post
$client->app()
    ->bsky()
    ->feed()
    ->post()
    ->forge()
    ->text(
        "📘 PHP Best Practices Guide: Part 1\n\n",
        "Today we're diving into modern PHP development. ",
        "First in our series about building robust applications.\n\n",
        "Key topics covered:\n",
        "1. Dependency Injection\n",
        "2. Service Containers\n",
        "3. Repository Pattern\n\n",
        "Full tutorial: ",
        RichText::link('https://blog.dev/php-best-practices', 'Read More'),
        "\n\nSpecial thanks to ",
        RichText::mention('did:plc:reviewer', 'our technical reviewer'),
        " for the insights!\n\n",
        RichText::tag('PHP', 'PHP'),
        " ",
        RichText::tag('WebDev', 'WebDev'),
        " ",
        RichText::tag('Coding', 'Coding')
    )
    ->embed(new ImageCollection([
        new Image($tutorialImage, "Code example showing dependency injection in PHP")
    ]))
    ->send();
```

## 👥 Community Management

### Profile Analytics
The SDK provides powerful tools for managing and analyzing user profiles. Here's how to build a simple analytics system:

```php
use GenericCollection\Collection;
use GenericCollection\Types\Primitive\StringType;

class CommunityAnalytics {
    private $client;
    
    public function __construct(Client $client) {
        $this->client = $client;
    }
    
    public function analyzeTeamProfiles() {
        // Get team members' profiles
        $teamHandles = new Collection(StringType::class, [
            'lead.dev.bsky.social',
            'frontend.dev.bsky.social',
            'backend.dev.bsky.social',
            'design.bsky.social'
        ]);
        
        $profiles = $this->client->app()
            ->bsky()
            ->actor()
            ->getProfiles()
            ->forge()
            ->actors($teamHandles)
            ->send();
            
        // Analyze profile data
        $analytics = [];
        foreach ($profiles as $profile) {
            $analytics[] = [
                'name' => $profile->displayName(),
                'followers' => $profile->followersCount(),
                'posts' => $profile->postsCount(),
                'engagement_rate' => $this->calculateEngagement($profile)
            ];
        }
        
        return $analytics;
    }
    
    private function calculateEngagement($profile) {
        // Custom engagement calculation logic
        return ($profile->followersCount() * $profile->postsCount()) / 100;
    }
}
```

### Follower Engagement System
Create meaningful interactions with your community:

```php
class FollowerEngagement {
    private $client;
    
    public function __construct(Client $client) {
        $this->client = $client;
    }
    
    public function welcomeNewFollowers() {
        $followers = $this->client->app()
            ->bsky()
            ->graph()
            ->getFollowers()
            ->forge()
            ->actor($this->client->authenticated()->did())
            ->send();
            
        $newFollowers = $this->filterTodaysFollowers($followers);
        
        foreach ($newFollowers->followers() as $follower) {
            $this->sendWelcomeMessage($follower);
        }
    }
    
    private function sendWelcomeMessage($follower) {
        $this->client->app()
            ->bsky()
            ->feed()
            ->post()
            ->forge()
            ->text(
                "👋 Welcome to our community ",
                RichText::mention($follower->did(), $follower->handle()),
                "!\n\n",
                "We're excited to have you here. ",
                "Check out our pinned posts for community guidelines ",
                "and ongoing discussions.\n\n",
                "Feel free to introduce yourself in the comments! 🌟"
            )
            ->send();
    }
}
```

## 🎨 Advanced Content Strategies

### Content Calendar Integration
Example of how to integrate the SDK with a content calendar system:

```php
class ContentScheduler {
    private $client;
    
    public function __construct(Client $client) {
        $this->client = $client;
    }
    
    public function schedulePost($content, $images = [], $scheduledTime) {
        // Prepare image uploads if any
        $uploadedImages = [];
        foreach ($images as $image) {
            $uploadedBlob = $this->client->com()
                ->atproto()
                ->repo()
                ->uploadBlob()
                ->forge()
                ->token($this->client->authenticated()->accessJwt())
                ->blob($image['path'])
                ->send()
                ->blob();
                
            $uploadedImages[] = new Image($uploadedBlob, $image['description']);
        }
        
        // Create the post
        $post = $this->client->app()
            ->bsky()
            ->feed()
            ->post()
            ->forge()
            ->text(...$this->formatContent($content));
            
        // Add images if any
        if (!empty($uploadedImages)) {
            $post->embed(new ImageCollection($uploadedImages));
        }
        
        return $post->send();
    }
    
    private function formatContent($content) {
        // Transform content into rich text components
        // Implementation depends on your content structure
    }
}
```

## 🛠️ Error Handling & Best Practices

The SDK provides comprehensive error handling to ensure your application gracefully handles any issues:

```php
use Atproto\Exceptions\{
    InvalidArgumentException,
    Auth\AuthRequired,
    Http\MissingFieldProvidedException
};

class PostManager {
    private $client;
    
    public function __construct(Client $client) {
        $this->client = $client;
    }
    
    public function createSafePost($content) {
        try {
            return $this->client->app()
                ->bsky()
                ->feed()
                ->post()
                ->forge()
                ->text($content)
                ->send();
                
        } catch (AuthRequired $e) {
            // Handle authentication issues
            $this->logError('Authentication failed', $e);
            $this->refreshAuthentication();
            
        } catch (MissingFieldProvidedException $e) {
            // Handle missing required fields
            $this->logError('Missing required field', $e);
            throw new ValidationException("Post creation failed: {$e->getMessage()}");
            
        } catch (InvalidArgumentException $e) {
            // Handle invalid input
            $this->logError('Invalid input provided', $e);
            throw new ValidationException("Invalid post content: {$e->getMessage()}");
        }
    }
}
```

## 🧪 Testing

The SDK comes with comprehensive testing tools to ensure your integration works flawlessly:

```bash
# Run complete test suite
composer test

# Run specific test suites
composer test-unit          # Unit tests
composer test-feature       # Feature tests

# Run code analysis
composer analyse
```

### Writing Tests for Your Integration

```php
class YourIntegrationTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = new Client();
        static::$client->authenticate(
            getenv('BLUESKY_TEST_IDENTIFIER'),
            getenv('BLUESKY_TEST_PASSWORD')
        );
    }

    public function testPostCreation()
    {
        // Your test implementation
    }
}
```

## 📈 Performance Tips

1. **Batch Operations**: When possible, use bulk endpoints
2. **Image Optimization**: Compress images before upload
3. **Cache Responses**: Implement caching for frequently accessed data
4. **Rate Limiting**: Respect API limits using built-in tools

## 🤝 Contributing

We love your input! We want to make contributing to BlueSky SDK as easy and transparent as possible. Here's how you can help:

1. Fork the repo
2. Clone your fork
3. Create your feature branch
4. Commit your changes
5. Push to your branch
6. Create a pull request

### Development Guidelines

- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation
- Use meaningful commit messages

## 📝 License

BlueSky SDK is released under the MIT License. See [LICENSE](LICENSE) for more information.

## 🙋‍♂️ Support

- **Official Documentation**: [Official Docs](https://docs.bsky.app)
- **SDK Documentation**: [SDK Docs](https://blueskysdk.shahmal1yev.dev)
- **Issues**: [GitHub Issues](https://github.com/shahmal1yev/blueskysdk/issues)
- **Discussions**: Start a discussion in [GitHub Discussions](https://github.com/shahmal1yev/blueskysdk/discussions)
- **Community**: Join Discord server via [invite link](https://discord.gg/tDajgYtBsZ)

---
Built with ❤️ by [Eldar Shahmaliyev](https://shahmal1yev.dev/about) for the PHP community.
