<?php

namespace Aporat\FilterVar;

return
    [
        'filters' => [
            'Capitalize'  => Filters\Capitalize::class,
            'Cast'        => Filters\Cast::class,
            'Escape'      => Filters\EscapeHTML::class,
            'FormatDate'  => Filters\FormatDate::class,
            'Lowercase'   => Filters\Lowercase::class,
            'Uppercase'   => Filters\Uppercase::class,
            'Trim'        => Filters\Trim::class,
            'StripTags'   => Filters\StripTags::class,
            'Digit'       => Filters\Digit::class,
            'FilterIf'    => Filters\FilterIf::class,
        ],
    ];
