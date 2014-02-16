<?php
namespace Tofu;
class Dictionary
{
    const INT = 'int';
    const UINT = 'uint';
    const ID = 'id';
    const DATE = 'date';
    const STRING = 'string';

    protected $_arrDictionary;

    public function __construct($arrDictionary = array())
    {
        //检查输入
        if (!is_array($arrDictionary)) {
            throw new \InvalidArgumentException('__construct() expects parameter 1 to be array');
        }
        foreach ($arrDictionary as $strKey => $mixVal) {
            if (!in_array(strval($mixVal['type']), array(self::INT, self::UINT, self::ID, self::DATE, self::STRING))) {
                throw new \InvalidArgumentException('undefined dictionary type');
            }
        }
        $this->_arrDictionary = $arrDictionary;
    }

    public function checkParams($arrParams = array())
    {
        //检查输入
        if (!is_array($arrParams)) {
            throw new \InvalidArgumentException('checkParams() expects parameter 1 to be array');
        }
        //返回参数
        $arrReturn = array();

        //过滤字典外的参数
        $arrParams = array_intersect_key($arrParams, $this->_arrDictionary);
        //检查参数
        foreach ($this->_arrDictionary as $strKey => $arrDictionary) {
            $strType = $arrDictionary['type'];
            $bolOptional = $arrDictionary['optional'];
            $mixParam = $arrParams[$strKey];
            if (!$bolOptional && !strlen($arrParams[$strKey])) {
                throw new \UnexpectedValueException("$strKey is not optional");
            }
            //参数未传，字典定义可选，跳过检查
            if ($bolOptional && !strlen($arrParams[$strKey])) {
                if (isset($arrDictionary['default']) && strlen($arrDictionary['default'])) {
                    $mixParam = $arrDictionary['default'];
                } else {
                    continue;
                }
            }
            //检查类型
            try {
                if (self::INT === $strType) {
                    $mixParam = $this->checkInt($strKey, $mixParam);
                } else if (self::UINT === $strType) {
                    $mixParam = $this->checkUint($strKey, $mixParam);
                } else if (self::ID === $strType) {
                    $mixParam = $this->checkId($strKey, $mixParam);
                } else if (self::STRING === $strType) {
                    $mixParam = strval($mixParam);
                } else if (self::DATE === $strType) {
                    $mixParam = $this->checkDate($strKey, $mixParam);
                }
            } catch (\RangeException $e) {
                throw new \UnexpectedValueException($e->getMessage());
            }
            $arrReturn[$strKey] = $mixParam;
        }
        return $arrReturn;
    }

    /**
     * checkInt 
     * 检查整型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkInt($strKey, $mixParam)
    {
        if (!is_string($strKey)) {
            throw new \InvalidArgumentException("checkInt() expects parameter 1 to be string");
        }
        if (!$strKey) {
            throw new \InvalidArgumentException("checkInt() expects parameter 1 not to be empty");
        }
        if (!ctype_digit(strval($mixParam))) {
            throw new \RangeException("$strKey is not a int");
        }
        return intval($mixParam);
    }

    /**
     * checkUint 
     * 检查无符号整型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkUint($strKey, $mixParam)
    {
        if (!is_string($strKey)) {
            throw new \InvalidArgumentException("checkUint() expects parameter 1 to be string");
        }
        if (!$strKey) {
            throw new \InvalidArgumentException("checkUint() expects parameter 1 not to be empty");
        }
        try {
            $intParam = self::checkInt($strKey, $mixParam);
        } catch (\RangeException $e) {
            throw new \RangeException("$strKey is not a uint");
        }
        if ($intParam < 0) {
            throw new \RangeException("$strKey is not a uint and less than 0");
        }
        return $intParam;
    }

    /**
     * checkId 
     * 检查id类型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkId($strKey, $mixParam)
    {
        if (!is_string($strKey)) {
            throw new \InvalidArgumentException("checkId() expects parameter 1 to be string");
        }
        if (!$strKey) {
            throw new \InvalidArgumentException("checkId() expects parameter 1 not to be empty");
        }
        try {
            $mixParam = new \MongoId($mixParam);
        } catch (MongoException $e) {
            throw new \RangeException("$strKey is not a id");
        }
        return $mixParam;
    }

    /**
     * checkDate 
     * 检查日期类型
     * @access public
     * @return void
     */
    static public function checkDate($strKey, $mixParam)
    {
        if (!is_string($strKey)) {
            throw new \InvalidArgumentException("checkDate() expects parameter 1 to be string");
        }
        if (!$strKey) {
            throw new \InvalidArgumentException("checkDate() expects parameter 1 not to be empty");
        }
        if (ctype_digit(strval($mixParam))) {
            $mixParam = intval($mixParam);
        } else {
            $mixParam = strtotime($mixParam);
        }
        if (false === $mixParam) {
            throw new \RangeException("$strKey is not a date");
        }
        return $mixParam;
    }
}
