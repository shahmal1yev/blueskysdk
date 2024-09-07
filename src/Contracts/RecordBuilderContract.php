<?php

namespace Atproto\Contracts;

use DateTimeImmutable;
use InvalidArgumentException;
use stdClass;

/**
 * Interface RecordBuilderContract
 *
 * This interface defines the contract for record builders.
 */
interface RecordBuilderContract
{
    /**
     * Adds text to the record.
     *
     * @param string $text The text to be added.
     *
     * @return $this
     */
    public function addText($text);

    /**
     * Adds text to the record.
     *
     * @param string $text The text to be added.
     *
     * @return $this
     */
    public function text($text);

    /**
     * Adds type to the record.
     *
     * @param string $type The type to be added.
     *
     * @return $this
     */
    public function addType($type);

    /**
     * Adds type to the record.
     *
     * @param string $type The type to be added.
     *
     * @return $this
     */
    public function type($type);

    /**
     * Adds creation date to the record.
     *
     * @param string|null $createdAt The creation date to be added.
     *
     * @return $this
     */
    public function addCreatedAt($createdAt = null);

    /**
     * Adds creation date to the record.
     *
     * @param DateTimeImmutable|null $createdAt The creation date to be added.
     *
     * @return $this
     */
    public function createdAt(DateTimeImmutable $createdAt = null);

    /**
     * Adds image to the record.
     *
     * @param string $blob The image blob.
     * @param string $alt The alternative text for the image.
     *
     * @return $this
     */
    public function addImage($blob, $alt);

    /**
     * Adds image to the record.
     *
     * @param string $blob The image blob.
     * @param string $alt The alternative text for the image.
     *
     * @return $this
     */
    public function image($blob, $alt);

    /**
     * Builds the record.
     *
     * @return stdClass The built record.
     */
    public function buildRecord();

    /**
     * Builds the record.
     *
     * @return stdClass The built record.
     */
    public function build();
}
