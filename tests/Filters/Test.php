<?php

namespace Aporat\FilterVar\Tests\Filters;

use Aporat\FilterVar\Filters\Capitalize;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testFilter(): void
    {
        $filter = new Capitalize();
        $value = $filter->apply('LOWer');

        $this->assertEquals('Lower', $value);
    }
}
