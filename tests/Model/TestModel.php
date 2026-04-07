<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Model;

use Flynn314\DataField\DataFieldSetGet;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use DataFieldSetGet;

    protected $guarded = [];

    public $timestamps = false;
}
