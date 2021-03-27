<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Meta;

final class Database
{
/** @psalm-suppress PropertyNotSetInConstructor */
    private string $name;

    /**
     * Table metas
     *
     * @psalm-suppress PropertyNotSetInConstructor
     * @var array<int, Table>
     */
    private array $tables;

    private function __construct()
    {
        
    }

    public static function fromName(string $name): static
    {
        $database = new static;

        $database->name = $name;

        return $database;
    }

    public function addTable(Table $table): void
    {
        $this->tables[] = $table;
    }

    /**
     * 
     * @return array<int, Table>
     */
    public function getTables(): array
    {
        return $this->tables;
    }
}
