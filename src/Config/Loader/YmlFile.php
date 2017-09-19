<?php
namespace Letto\Config\Loader;

use \Symfony\Component\Config\Loader\FileLoader;
use \Symfony\Component\Yaml\Yaml;

class YmlFile extends FileLoader
{
    public function load($file, $ext = null)
    {
        $path = $this->locator->locate($file);
        return Yaml::parse(file_get_contents($path));
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
