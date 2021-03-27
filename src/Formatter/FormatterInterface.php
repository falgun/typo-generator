<?php

namespace Falgun\Typo\Generator\Formatter;

use Falgun\Typo\Generator\Meta\Database;

interface FormatterInterface
{

    public function format(Database $meta): \Generator;
}
