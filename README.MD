# SortedLinkedList

This repository contains a simple implementation of a **sorted linked list** in PHP.

## Overview

`SortedLinkedList` is a generic linked list that keeps elements **sorted** automatically.  
It can hold **either integers or strings**, but not both at the same time.  
This implementation is meant as a take-home exercise and demonstrates:

- API design
- handling of type constraints
- basic library ergonomics
- iterator and countable integration

---

## Design Decisions

1. **Comparator-based type enforcement**
   - Rather than checking types at runtime (`gettype`), a comparator is provided.
   - Named constructors make it easy to create `int` or `string` lists:
     ```php
     $intList = SortedLinkedList::forInts();
     $stringList = SortedLinkedList::forStrings();
     ```

2. **Multiset behavior**
   - Duplicates are allowed.
   - `remove($value)` removes the **first occurrence**.

3. **Iterator**
   - `getIterator()` returns a forward-only generator.
   - This allows easy `foreach` usage:
     ```php
     foreach ($list as $item) {
         echo $item;
     }
     ```

4. **Countable**
   - Implements `Countable` via `count()` for convenience.

5. **Early exit optimization**
   - `contains($value)` exits early when it passes the possible insertion point.
   - This slightly improves performance for sorted lists.

6. **Node visibility**
   - `Node` is an internal implementation detail. Users interact only with `SortedLinkedList`.

---

## Usage Example

```php
$list = SortedLinkedList::forInts();
$list->add(5);
$list->add(2);
$list->add(8);

echo $list->first() . " is added to list." . PHP_EOL; // 2
echo $list->last() . " is added to list." . PHP_EOL;  // 8
$list->remove(5);

echo "List contains(" . count($list) . " elements" . "): " . PHP_EOL;
foreach ($list as $value) {
    echo $value, PHP_EOL;
}
```

## Notes
* This is a **take-home exercise implementation**, optimized for clarity and demonstration.
* Decisions regarding duplicates, comparator usage, and API ergonomics are intentional.
