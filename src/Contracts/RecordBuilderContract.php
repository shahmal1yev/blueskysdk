<?php

namespace Atproto\Contracts;

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
     * Adds type to the record.
     *
     * @param string $type The type to be added.
     *
     * @return $this
     */
    public function addType($type);

    /**
     * Adds creation date to the record.
     *
     * @param string|null $createdAt The creation date to be added.
     *
     * @return $this
     */
    public function addCreatedAt($createdAt = null);

    /**
     * Builds the record.
     *
     * @return \stdClass The built record.
     */
    public function buildRecord();
}
