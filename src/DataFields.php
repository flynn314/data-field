<?php
declare(strict_types=1);

namespace Flynn\DataField;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class DataFields
{
    /**
     * Create a new attribute instance.
     *
     * @param  array<int, string>  $columns
     */
    public function __construct(public array $columns)
    {
    }
}
