<?php
namespace Letto\Config\Loader;

use \Symfony\Component\Config\Loader\FileLoader;

class IniFile extends FileLoader
{
    public function load($file, $ext = null)
    {
        $path = $this->locator->locate($file);
        $result = parse_ini_file($path, true);
        if (false === $result || array() === $result) {
            throw new \Exception(sprintf('The "%s" file is not valid.', $resource));
        }

        if (isset($result['parameters']) && is_array($result['parameters'])) {
            return $result['parameters'];
        }
        return false;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'ini' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
