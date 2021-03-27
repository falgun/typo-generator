<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Formatter;

use Falgun\Typo\Generator\Meta\Database;

final class EntityFormatter implements FormatterInterface
{

    public function format(Database $meta): \Generator
    {

        foreach ($meta->getTables() as $table) {

            $tableNameInUpper = $this->prepareTableName($table->getName());
            $path = 'Entities/' . $tableNameInUpper . '.php';

            $code = <<<CODE
<?php\ndeclare(strict_types=1);

namespace App\DB\Entities;

class {$tableNameInUpper}
{

CODE;
            foreach ($table->getColumns() as $column) {
                $code .= <<<CODE
                    
    private \${$column->getName()};

CODE;
            }

            foreach ($table->getColumns() as $column) {
                $nameForMethod = ucfirst($column->getName());

                $code .= <<<CODE

    public function get{$nameForMethod}()
    {
        return \$this->{$column->getName()};
    }

    public function set{$nameForMethod}(\$value): void
    {
        \$this->{$column->getName()} = \$value;
    }

CODE;
            }
            $code .= '}';

            yield compact('path', 'code');
        }
    }

    private function prepareTableName(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}
