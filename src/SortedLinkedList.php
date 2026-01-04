<?php

/**
 * @template T of int|string
 * @implements IteratorAggregate<int, T>
*/
final class SortedLinkedList implements IteratorAggregate, Countable
{
    /** @var callable(T, T): int */
    private $comparator;

    /** @var Node<T>|null */
    private ?Node $head = null;

    private int $size = 0;

    /**
     * @param callable(T, T): int $comparator
     */
    public function __construct(callable $comparator)
    {
        $this->comparator = $comparator;
    }

    public static function forInts(): self
    {
        return new self(fn(int $a, int $b): int => $a <=> $b);
    }

    public static function forStrings(): self
    {
        return new self(fn(string $a, string $b): int => strcmp($a, $b));
    }

    /**
     * @param T $value
     */
    public function add($value): void
    {
        $node = new Node($value);

        if ($this->head === null || ($this->comparator)($value, $this->head->value) < 0) {
            $node->next = $this->head;
            $this->head = $node;
            $this->size++;
            return;
        }

        $current = $this->head;

        while (
            $current->next !== null &&
            ($this->comparator)($value, $current->next->value) >= 0
        ) {
            $current = $current->next;
        }

        $node->next = $current->next;
        $current->next = $node;
        $this->size++;
    }

    /**
     * Removes first occurrence of value
     *
     * @param T $value
     */
    public function remove($value): bool
    {
        if ($this->head === null) {
            return false;
        }

        if (($this->comparator)($value, $this->head->value) === 0) {
            $this->head = $this->head->next;
            $this->size--;
            return true;
        }

        $current = $this->head;

        while (
            $current->next !== null &&
            ($this->comparator)($value, $current->next->value) !== 0
        ) {
            $current = $current->next;
        }

        if ($current->next === null) {
            return false;
        }

        $current->next = $current->next->next;
        $this->size--;
        return true;
    }

    /**
     * @param T $value
     */
    public function contains($value): bool
    {
        $current = $this->head;

        while ($current !== null) {
            $cmp = ($this->comparator)($value, $current->value);

            if ($cmp === 0) {
                return true;
            }

            if ($cmp < 0) {
                return false; // early exit thanks to sorting
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    public function count(): int
    {
        return $this->size;
    }

    /**
     * @return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        $current = $this->head;

        while ($current !== null) {
            yield $current->value;
            $current = $current->next;
        }
    }
}

/**
 * @template T
 * @internal
 */
final class Node
{
    /** @var T */
    public mixed $value;

    /** @var Node<T>|null */
    public ?self $next = null;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
