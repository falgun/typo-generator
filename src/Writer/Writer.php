<?php
declare(strict_types=1);

namespace Falgun\Typo\Generator\Writer;

final class Writer
{

    public function write(string $path, string $content): int
    {
        if (is_dir(dirname($path)) === false) {
            mkdir(dirname($path), 0755, true);
        }

        return file_put_contents($path, $content);
    }
}
