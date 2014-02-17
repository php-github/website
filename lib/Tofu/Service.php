<?php
namespace Tofu;
class Service
{
    protected $strServiceName = '';

    protected $objDictionary = array();

    public function __construct($strModuleName, $strModelName, $strDictionaryPath)
    {
        if (!is_string($strModuleName)) {
            throw new \InvalidArgumentException("__construct() expects parameter 1 to be string");
        }
        if (!$strModuleName) {
            throw new \InvalidArgumentException("__construct() expects parameter 1 not to be empty");
        }
        if (!is_string($strModelName)) {
            throw new \InvalidArgumentException("__construct() expects parameter 2 to be string");
        }
        if (!$strModelName) {
            throw new \InvalidArgumentException("__construct() expects parameter 2 not to be empty");
        }
        $this->strModuleName = $strModuleName;
        $this->strModelName = $strModelName;
        if (!is_readable($strDictionaryPath)) {
            throw new \UnexpectedValueException("__construct() $strDictionaryPath is not readable");
        }
        if (!$this->arrDictionary = parse_ini_file($strDictionaryPath, true)) {
            throw new \UnexpectedValueException("__construct() $strDictionaryPath can not parse");
        }
    }

    public function execute($strMethodName, $arrArguments = array(), $intExpiration = 0)
    {
        if (!is_array($this->arrDictionary[$strMethodName])) {
            $strClass = get_class($this);
            throw new \BadMethodCallException("undefined method $strMethodName in class $strClass");
        }
        $objDictionary = new Dictionary($this->arrDictionary[$strMethodName]);
        $arrArguments = $objDictionary->checkParams($arrArguments);

        if ($intExpiration) {
            try {
                return $this->getFromCache($strMethodName, $arrArguments);
            } catch (\RuntimeException $e) {
                user_error("{$strMethodName} cache not hit");
            }
        }

        if ($strMethodName === "findById") {
            //查
            $arrReturn = $this->findById($arrArguments['id']);
        } else {
            $arrReturn = call_user_func(array($this, $strMethodName), $arrArguments);
        }
        if ($intExpiration) {
            try {
                $this->setToCache($strMethodName, $arrArguments, $arrReturn, $intExpiration);
            } catch (\RuntimeException $e) {
                user_error("{$strMethodName} cache not set");
            }
        }
        return $arrReturn;
    }

    protected function getFromCache($strMethodName, $arrArguments)
    {
        $strCacheKey = $this->getCacheKey($strMethodName, $arrArguments);
        $objMemcached = new \Memcached();
        $objMemcached->addServer('localhost', 11211);
        $arrReturn = $objMemcached->get($strCacheKey);
        if ($objMemcached->getResultCode() == \Memcached::RES_NOTFOUND) {
            throw new \RuntimeException();
        }
        return $arrReturn;
    }

    protected function setToCache($strMethodName, $arrArguments, $arrData, $intExpiration)
    {
        $strCacheKey = $this->getCacheKey($strMethodName, $arrArguments);
        $objMemcached = new \Memcached();
        $objMemcached->addServer('localhost', 11211);
        $objMemcached->set($strCacheKey, $arrData, $intExpiration);
        if ($objMemcached->getResultCode() !== \Memcached::RES_SUCCESS) {
            throw new \RuntimeException();
        }
        return $arrReturn;
    }

    protected function getCacheKey($strMethodName, $arrArguments)
    {
        $strClassName = get_class($this);
        return sprintf("%s-%s-%s", $strClassName, $strMethodName, json_encode($arrArguments));
    }

    public function find($arrQuery)
    {
        $objModel = new Model($this->strModuleName, $this->strModelName);
        return $objModel->find($arrQuery);
    }

    protected function findById($strId)
    {
        $objModel = new Model($this->strModuleName, $this->strModelName);
        $objModel = $objModel->findById($strId);
        if ($objModel->isEmpty()) {
            throw new \RuntimeException("can not find by id={$strId}");
        }
        return $objModel->toArray();
    }

    protected function mfindById($strId)
    {
    }

    protected function add($arrArguments)
    {
        $objModel = new Model($this->strModuleName, $this->strModelName);
        return $objModel->save($arrArguments);
    }

    protected function updateById($arrArguments)
    {
        $objModel = new Model($this->strModuleName, $this->strModelName);
        return $objModel->save($arrArguments);
    }
}
