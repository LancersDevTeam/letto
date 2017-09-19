<?php
namespace Letto\Config;

use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Config\Loader\DelegatingLoader;
use \Symfony\Component\Config\Loader\LoaderResolver;
use \Letto\Config\Loader\IniFile;
use \Letto\Config\Loader\PhpFile;
use \Letto\Config\Loader\YmlFile;

class Config
{
    const DEFAULT_DIR = '/../../config';

    protected $paths;
    protected $loader;
    public $config = array();

    public function __construct($path = null)
    {
        $this->paths = array(realpath(__DIR__ . self::DEFAULT_DIR));
        $this->addPath($path);
    }

    /**
     * path to get setting
     *
     * @param   string  $path
     */
    public function addPath($path)
    {
        if (!is_null($path)) {
            $path = realpath($path);
            $path = (!is_array($path)) ? array($path) : $path;
            $this->paths = array_merge($this->paths, $path);
        }

        $locator  = new FileLocator($this->paths);
        $resolver = new LoaderResolver(array(
            new IniFile($locator),
            new PhpFile($locator),
            new YmlFile($locator),
        ));
        $this->loader = new DelegatingLoader($resolver);
    }

    /**
     * path to get setting
     *
     * @param   string  $resource
     * @param   string  $ext        - extension
     * @return  array
     */
    public function load($resource, $ext = 'php')
    {
        $file = sprintf('%s.%s', $resource, $ext);
        $this->config[$resource] = $this->loader->load($file, $ext);
        return $this->config;
    }

    /**
     * can get array data with dot separator.
     * with a default value if it does not exist.
     *
     * @param   string  $key - dot separator
     * @param   mixed   $default
     * @return  mixed
     */
    public function get($key, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        $config = $this->config;
        foreach (explode('.', $key) as $keyPart) {
            if (
                (is_array($config) && isset($config[$keyPart])) === false
            ) {
                if (!is_array($config) || !array_key_exists($keyPart, $config)) {
                    return $this->value($default);
                }
            }

            $config = $config[$keyPart];
        }

        return $config;
    }

    protected function value($var)
    {
        return ($var instanceof \Closure) ? $var() : $var;
    }
}
