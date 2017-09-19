<?php
namespace Letto\Core;

class LettoAbstract
{
    private $__vars = array();

    public function __construct($isDevelopment = false)
    {
        $this->isDevelopment = $isDevelopment;
    }

    /**
     * Letto Classes Lazy Load.
     *
     * @param   string  $className
     * @return  mixed
     */
    protected function lazyLoad($className)
    {
        $fullyQualifiedName = sprintf('\\Letto\\%s\\%s', $className, $className);
        if (class_exists($fullyQualifiedName)) {
            $this->__set($className, new $fullyQualifiedName($this->isDevelopment));
            return $this->__get($className);
        }
        return null;
    }

    public function __set($key, $value)
    {
        $key = ucfirst(strtolower($key));
        if (!array_key_exists($key, $this->__vars)) {
            $this->__vars[$key] = $value;
        } else {
            throw new \Exception("[{$key}] is ReadOnly.");
        }
    }

    public function __get($key)
    {
        $key = ucfirst(strtolower($key));
        if (array_key_exists($key, $this->__vars)) {
            return $this->__vars[$key];
        }
        return $this->lazyLoad($key);
    }
}
