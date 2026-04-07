<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Model;

use Flynn314\DataField\DataFields;
use Flynn314\DataField\DataFieldSetGet;

#[DataFields([
    'name',
    'test',
    'array1',
    'array2',
])]
final class TestModelWithAttribute extends TestModel
{
    use DataFieldSetGet;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge($this->dataFieldCasts(), [
            'array1' => 'array',
        ]);
    }
}
