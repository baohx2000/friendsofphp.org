<?php declare(strict_types=1);

namespace Fop\FileSystem;

use Nette\Utils\FileSystem;
use Symfony\Component\Yaml\Yaml;

final class YamlFileSystem
{
    /**
     * @param mixed[] $data
     */
    public function saveArrayToFile(array $data, string $file): void
    {
        $yamlDump = Yaml::dump($data, 10, 4);
        $yamlDump = '# this file was generated, do not edit it manually' . PHP_EOL . $yamlDump;

        FileSystem::write($file, $yamlDump);
    }
}
