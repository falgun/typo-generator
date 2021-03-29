<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Formatter;

use Falgun\Typo\Generator\Meta\Database;

final class MetaFormatter implements FormatterInterface
{

    public function format(Database $meta): \Generator
    {
        foreach ($meta->getTables() as $table) {

            $tableNameInUpper = $this->prepareTableName($table->getName());
            $path = 'Metas/' . $tableNameInUpper . 'Meta.php';

            $code = <<<CODE
<?php\ndeclare(strict_types=1);

namespace App\DB\Metas;

use Falgun\Typo\Query\Parts\Table;
use Falgun\Typo\Query\Parts\Column;
use Falgun\Typo\Query\Parts\Asterisk;

final class {$tableNameInUpper}Meta
{

    private const NAME = '{$table->getName()}';

    private string \$alias;

    private function __construct(string \$alias = '')
    {
        \$this->alias = \$alias;
    }

    public static function new()
    {
        return new static();
    }

    public static function as(string \$alias)
    {
        return new static(\$alias);
    }

    public function table(): Table
    {
        \$table = Table::fromName(self::NAME);

        if (\$this->alias !== '') {
            \$table->as(\$this->alias);
        }

        return \$table;
    }

    private function getNameOrAlias(): string
    {
        return (\$this->alias ? \$this->alias : self::NAME);
    }

    public function asterisk(): Asterisk
    {
        return Asterisk::fromTable(\$this->getNameOrAlias());
    }

CODE;

            foreach ($table->getColumns() as $column) {
                $columnMethodName = $this->prepareColumnName($column->getName());

                $code .= <<<CODE

    public function {$columnMethodName}(): Column
    {
        return Column::fromSchema(\$this->getNameOrAlias() . '.{$column->getName()}');
    }

CODE;
            }
            $code .= '}';

            yield compact('path', 'code');
        }
    }

    private function prepareTableName(string $name): string
    {
        $asWords = ucwords(str_replace(['-', '_'], ' ', $name));

        return str_replace(' ', '', $asWords);
    }

    private function prepareColumnName(string $name): string
    {
        $asWords = ucwords(str_replace(['-', '_'], ' ', $name));

        return lcfirst(str_replace(' ', '', $asWords));
    }
}
