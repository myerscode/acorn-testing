<?php

namespace Myerscode\Acorn\Testing\Support;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\Constraint\DirectoryExists;
use PHPUnit\Framework\Constraint\FileExists;
use PHPUnit\Framework\Constraint\LogicalNot;

class Assertions extends PHPUnit
{
    /**
     * Asserts that a file does not exist.
     *
     * @param  string  $filename
     * @param  string  $message
     *
     * @return void
     */
    public static function assertFileDoesNotExist(string $filename, string $message = ''): void
    {
        static::assertThat($filename, new LogicalNot(new FileExists), $message);
    }

    /**
     * Asserts that a directory does not exist.
     *
     * @param  string  $directory
     * @param  string  $message
     *
     * @return void
     */
    public static function assertDirectoryDoesNotExist(string $directory, string $message = ''): void
    {
        static::assertThat($directory, new LogicalNot(new DirectoryExists), $message);
    }
}
