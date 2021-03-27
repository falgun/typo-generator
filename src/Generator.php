<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator;

use Falgun\Kuery\Kuery;
use Falgun\Kuery\Configuration;
use Falgun\Typo\Generator\Meta\Table;
use Falgun\Typo\Generator\Meta\Database;
use Falgun\Typo\Generator\Writer\Writer;
use Falgun\Typo\Generator\Formatter\MetaFormatter;
use Falgun\Typo\Generator\Formatter\EntityFormatter;

final class Generator
{

    private Kuery $kuery;
    private Database $meta;
    private Writer $writer;
    private MetaFormatter $metaFormatter;
    private EntityFormatter $entityFormatter;

    public function __construct(Kuery $kuery, Configuration $configuration)
    {
        $this->kuery = $kuery;
        $this->meta = Database::fromName($configuration->database);
        $this->metaFormatter = new MetaFormatter();
        $this->entityFormatter = new EntityFormatter();
        $this->writer = new Writer();
    }

    public function generate(string $targetDir): void
    {
        $this->fetchMetaData();

        foreach ($this->metaFormatter->format($this->meta) as $writable) {
            $this->writer->write($targetDir . '/' . $writable['path'], $writable['code']);
        }

        foreach ($this->entityFormatter->format($this->meta) as $writable) {
            $this->writer->write($targetDir . '/' . $writable['path'], $writable['code']);
        }

        echo 'Generated files at "' . $targetDir . '"' . PHP_EOL;
    }

    private function fetchMetaData(): void
    {
        $tables = $this->fetchDBMetaData();
        $this->fetchTableMetaData($tables);
    }

    private function fetchDBMetaData(): array
    {
        $stmt = $this->kuery->run('SHOW TABLES');

        return $this->kuery->fetchAllAsArray($stmt);
    }

    private function fetchTableMetaData(array $tables): void
    {
        foreach ($tables as $table) {
            $tableName = current($table);

            $stmt = $this->kuery->run('DESCRIBE ' . $tableName);

            $columns = $this->kuery->fetchAllAsArray($stmt);

            $this->meta->addTable(Table::fromDescribe($tableName, $columns));
        }
    }
}
