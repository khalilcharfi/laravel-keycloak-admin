<?php

namespace App\Keycloak\Admin\Representations;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface RepresentationCollectionInterface
 * @package Keycloak\Admin\Representations
 * @template <T> The type of the individual elements
 */
interface RepresentationCollectionInterface extends Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @param callable $filter
     * @return RepresentationCollectionInterface
     */
    public function filter(callable $filter): RepresentationCollectionInterface;

    /**
     * @param callable $callback
     * @return RepresentationCollectionInterface
     */
    public function map(callable $callback): RepresentationCollectionInterface;

    /**
     * @return RepresentationInterface[]
     */
    public function getIterator(): iterable;

    /**
     * @return RepresentationInterface[]
     */
    public function toArray(): array;

    public function first(?callable $callback = null): ?RepresentationInterface;
}
