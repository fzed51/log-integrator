<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection ReturnTypeCanBeDeclaredInspection
 */

namespace Integrator;

use Iterator;
use RuntimeException;

class LogDirectory implements Iterator
{
    /** @var int index de position */
    protected int $index;
    /** @var array<string> liste de fichier */
    protected array $files;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw new RuntimeException("$directory n'est pas un dossier valide");
        }
        $this->files = $this->scanDir($directory);
        var_dump($this->files);
        foreach ($this->files as $file) {
            echo basename($file) . PHP_EOL;
        }
        $this->rewind();
    }

    /**
     * @param string $directory
     * @return array
     */
    protected function scanDir(string $directory): array
    {
        $items = scandir($directory);
        $items = array_map(static function ($item) use ($directory) {
            return $directory . '/' . $item;
        }, $items);
        $files = array_filter($items, static function ($item) {
            return is_file($item) && basename($item) !== '.empty';
        });
        return $files !== false ? $files : [];
    }

    public function current()
    {
        return $this->files[$this->index];
    }

    public function next()
    {
        ++$this->index;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->files[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }
}