<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Model;

use Flynn314\DataField\DataFields;
use Flynn314\DataField\DataFieldSetGet;

#[DataFields([
    'name',
    'test',
])]
final class TestModelWithAttribute extends TestModel
{
    use DataFieldSetGet;
}
