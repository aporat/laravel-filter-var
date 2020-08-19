<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\FilterVar;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testFilter(): void
    {
        $value = ' ValUe INPUT';

        $filter = new FilterVar();
        $result = $filter->filterValue('trim', $value);

        $this->assertEquals('ValUe INPUT', $result);
    }
}
