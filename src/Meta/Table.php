<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Meta;

final class Table
{
/** @psalm-suppress PropertyNotSetInConstructor */
    private string $name;

    /**
     * Column metas
     *
     * @psalm-suppress PropertyNotSetInConstructor
     * @var array<int, Column>
     */
    private array $columns;

    private function __construct()
    {
        
    }

    public static function fromDescribe(string $tableName, array $columnMetas): static
    {
        $table = new static;

        $table->name = $tableName;

        foreach ($columnMetas as $columnMeta) {
            $table->columns[] = Column::fromDescribe($columnMeta);
        }

        return $table;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
