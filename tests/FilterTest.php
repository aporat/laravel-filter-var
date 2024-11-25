<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\FilterVar;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testCapitalizeFilter(): void
    {
        $value = 'ValUe INPUT';

        $filter = new FilterVar();
        $result = $filter->filterValue('capitalize', $value);

        $this->assertEquals('Value Input', $result);
    }

    public function testCastFilter(): void
    {
        $value = '23';

        $filter = new FilterVar();
        $result = $filter->filterValue('cast:int', $value);

        $this->assertIsInt($result);
    }

    public function testLowercaseFilter(): void
    {
        $value = 'ValUe INPUT';

        $filter = new FilterVar();
        $result = $filter->filterValue('lowercase', $value);

        $this->assertEquals('value input', $result);
    }

    public function testStripTagsFilter(): void
    {
        $value = '<html>ValUe INPUT</html';

        $filter = new FilterVar();
        $result = $filter->filterValue('strip-tags', $value);

        $this->assertEquals('ValUe INPUT', $result);
    }

    public function testTrimFilter(): void
    {
        $value = ' ValUe INPUT';

        $filter = new FilterVar();
        $result = $filter->filterValue('trim', $value);

        $this->assertEquals('ValUe INPUT', $result);
    }

    public function testUppercaseFilter(): void
    {
        $value = 'ValUe INPUT';

        $filter = new FilterVar();
        $result = $filter->filterValue('uppercase', $value);

        $this->assertEquals('VALUE INPUT', $result);
    }
}
