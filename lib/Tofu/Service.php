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

    public function execute($strMethodName, $arrArguments = array())
    {
        if (!is_array($this->arrDictionary[$strMethodName])) {
            $strClass = get_class($this);
            throw new \BadMethodCallException("undefined method $strMethodName in class $strClass");
        }
        $objDictionary = new Dictionary($this->arrDictionary[$strMethodName]);
        $arrArguments = $objDictionary->checkParams($arrArguments);
        if ($strMethodName === "findById") {
            //查
            return $this->findById($arrArguments['id']);
        } else if ($strMethodName === "mfindById") {
            //批量查
            return $this->mfindById($arrArguments);
        } else if ($strMethodName === "add") {
            //增
            return $this->add($arrArguments);
        } else if ($strMethodName === "delById") {
            //删
            return $this->delById($arrArguments);
        } else if ($strMethodName === "updateById") {
            //改
            return $this->updateById($arrArguments);
        } else {
            return call_user_func(array($this, $strMethodName), $arrArguments);
        }
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
