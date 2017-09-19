<?php
namespace Letto\Config\Loader;

use \Symfony\Component\Config\Loader\FileLoader;

class PhpFile extends FileLoader
{
    public function load($file, $ext = null)
    {
        $path = $this->locator->locate($file);
        return include $path;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
