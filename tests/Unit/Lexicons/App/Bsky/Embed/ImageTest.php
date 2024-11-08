<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Image;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Supports\Reflection;

class ImageTest extends TestCase
{
    use FileMock;
    use Reflection;

    private Image $image;
    private array $dependencies = ['alt' => 'alt'];

    /**
     * @return array
     */
    public function randAspectRatio(): array
    {
        return ['width' => rand(1, 50), 'height' => rand(1, 50)];
    }

    /**
     * @return Image
     * @throws InvalidArgumentException
     */
    public function createImage(): Image
    {
        return new Image(...array_values($this->dependencies));
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        $this->type = 'image/png';

        $this->dependencies = array_merge([
            'file' => $this->createMockFile()
        ], $this->dependencies);

        $this->image = $this->createImage();
    }

    /**
     * @throws ReflectionException
     */
    public function test__construct()
    {
        $this->assertSame($this->dependencies['file'], $this->getPropertyValue('file', $this->image));
        $this->assertSame($this->dependencies['alt'], $this->image->alt());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAspectRatio()
    {
        $this->assertNull($this->image->aspectRatio());

        $expected = $this->randAspectRatio();

        $this->image->aspectRatio(...array_values($expected));

        $this->assertSame($expected, $this->image->aspectRatio());
    }

    /** @dataProvider aspectRatioInvalidArguments */
    public function testAspectRatioThrowsInvalidArgumentException(...$arguments): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->image->aspectRatio(...$arguments);
    }

    /**
     * @return array
     */
    public function aspectRatioInvalidArguments(): array
    {
        return [
            [0, 12],
            [null, 0],
            [12, null],
            [0, 0],
            [0, 54],
            [76, 0],
            [1]
        ];
    }

    public function testAlt()
    {
        $expected = $this->dependencies['alt'];

        $this->assertSame($expected, $this->image->alt());

        $new = uniqid();
        $this->image->alt($new);

        $this->assertSame($new, $this->image->alt());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerialize()
    {
        $expected = [
            'alt' => $this->dependencies['alt'],
            'image' => $this->dependencies['file'],
        ];

        $image = $this->createImage();

        $this->assertSame($expected, $image->jsonSerialize());

        $aspectRatio = $this->randAspectRatio();

        $expected['aspectRatio'] = $aspectRatio;

        $image->aspectRatio(...array_values($aspectRatio));

        $this->assertSame($expected, $image->jsonSerialize());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testMethodChaining()
    {
        $result = $this->image
            ->alt('new alt')
            ->aspectRatio(16, 9);

        $this->assertSame($this->image, $result);
    }

    public function testSize(): void
    {
        $this->assertSame($this->size, $this->image->size());
    }
}
