<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Meta;

final class Column
{

    /** @psalm-suppress PropertyNotSetInConstructor */
    private string $name;

    private function __construct()
    {
        
    }

    public static function fromDescribe(array $columnMeta): static
    {
        $column = new static;

        $column->name = $columnMeta['Field'];

        return $column;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
