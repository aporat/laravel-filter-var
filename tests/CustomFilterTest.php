<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\Contracts\Filter;
use Aporat\FilterVar\FilterVar;
use PHPUnit\Framework\TestCase;

class MediaRealId implements Filter
{
    /**
     * @param mixed $value
     * @param array $options
     *
     * @return string
     */
    public function apply(mixed $value, array $options = []): string
    {
        if (strpos($value, '_')) {
            list($value) = explode('_', $value);
        }

        return $value;
    }
}

class CustomFilterTest extends TestCase
{
    public function testFilter(): void
    {
        $config = require __DIR__.'/../config/filter-var.php';

        $config['custom_filters'] = [
            'MediaRealId' => MediaRealId::class,
        ];

        $value = '11111_22222';
        $filter = new FilterVar($config);

        $result = $filter->filterValue('MediaRealId', $value);
        $this->assertEquals('11111', $result);
        $this->assertIsString($result);

        $result = $filter->filterValue('MediaRealId|cast:int', $value);
        $this->assertIsInt($result);
    }
}
