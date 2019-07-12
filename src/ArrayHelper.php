<?php

namespace marsapp\helper\myarray;

/**
 * Array Helper
 * 
 * Array processing library, providing functions such as rebuilding indexes, grouping, getting content, recursive difference sets, recursive sorting, etc.
 * 
 * @version 0.3.2
 * @author Mars Hung <tfaredxj@gmail.com>
 * @see https://github.com/marshung24/ArrayHelper
 */
class ArrayHelper
{
    /**
     * Option set
     * @var array
     */
    protected static $_options = [
        // Default sort out
        'sortOut' => true,
    ];

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */

    /**
     * Data re-index by keys
     * 
     * - When $outputBy="return", $data will change after indexBy() is executed.  
     *   Since $data is a reference, the return is ArrayHelper. 
     * - When $outputBy="return", $data is a reference, but the function does not change it.  
     *   Instead, use the $data content as a reference for the output data content.
     *   ex.
     *   // Processing :
     *   $return  = ArrayHelper::indexBy($data, ['id', 'name']);
     *   // Reference status :
     *   $return[$id][$name] = & $row = & $data[$key];
     * 
     * @param array $data Array data for handling
     * @param string|array $keys keys for index key (Array/string)
     * @param string $outputBy reference(default)/return
     * @return mixed Result with indexBy Keys
     */
    public static function indexBy(array &$data, $keys, $outputBy = 'reference')
    {
        // Refactor Array $data structure by $keys
        $opt = self::_refactorBy($data, $keys, $type = 'indexBy');

        if ($outputBy === 'reference') {
            // Output by reference
            $data = $opt;
            return new static();
        } else {
            // Output by return
            return $opt;
        }
    }

    /**
     * Group by keys
     * 
     * Data re-index and Group by keys.
     * - When $outputBy="return", $data will change after groupBy() is executed.  
     *   Since $data is a reference, the return is ArrayHelper. 
     * - When $outputBy="return", $data is a reference, but the function does not change it.  
     *   Instead, use the $data content as a reference for the output data content.
     *   ex.
     *   // Processing :
     *   $return  = ArrayHelper::groupBy($data, ['id', 'name']);
     *   // Reference status :
     *   $return[$id][$name][$k1] = & $row = & $data[$key];
     * 
     * @param array $data Array data for handling
     * @param string|array $keys keys for index key (Array/string)
     * @param string $outputBy reference(default)/return
     * @return mixed Result with groupBy Keys
     */
    public static function groupBy(array &$data, $keys, $outputBy = 'reference')
    {
        // Refactor Array $data structure by $keys
        $opt = self::_refactorBy($data, $keys, $type = 'groupBy');

        if ($outputBy === 'reference') {
            // Output by reference
            $data = $opt;
            return new static();
        } else {
            // Output by return
            return $opt;
        }
    }

    /**
     * Data re-index by keys, No Data
     *
     * @param array $data Array data for handling
     * @param string|array $keyskeys for index key (Array/string)
     * @param string $outputBy reference(default)/return
     * @return mixed Result with indexOnly Keys
     */
    public static function indexOnly(array &$data, $keys, $outputBy = 'reference')
    {
        // Refactor Array $data structure by $keys
        $opt = self::_refactorBy($data, $keys, $type = 'indexOnly');

        if ($outputBy === 'reference') {
            // Output by reference
            $data = $opt;
            return new static();
        } else {
            // Output by return
            return $opt;
        }
    }

    /**
     * Get Data content by index
     * 
     * - Pay attention to the return value, 
     * - If no $indexTo target will return the empty array,
     * - When the target may be 0 or null, you need to pay attention to the judgment.
     * 
     * Usage:
     * - $data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];
     * - var_export(ArrayHelper::getContent($data)); // full $data content
     * - var_export(ArrayHelper::getContent($data, 'user')); // ['name' => 'Mars', 'birthday' => '2000-01-01']
     * - var_export(ArrayHelper::getContent($data, ['user', 'name'])); // Mars
     * - var_export(ArrayHelper::getContent($data, 'user, name')); // Mars
     * - var_export(ArrayHelper::getContent($data, ['user', 'name', 'aaa'])); // []
     * 
     * @param array $data
     * @param array|string $indexTo Content index of the data you want to get
     * @param bool $exception default false
     * @throws \Exception
     * @return array|mixed
     */
    public static function getContent(array $data, $indexTo = [], $exception = false)
    {
        /* Arguments prepare */
        if (is_string($indexTo)) {
            $indexTo = array_map('trim', explode(',', $indexTo));
        }
        $indexed = [];

        foreach ($indexTo as $idx) {
            // save runed index
            $indexed[] = $idx;

            if (is_array($data) && array_key_exists($idx, $data)) {
                // If exists, Get values by recursion
                $data = $data[$idx];
            } else {
                // Not exists, Exception or return []
                if ($exception) {
                    throw new \Exception('Error index: ' . implode(' => ', $indexed), 400);
                } else {
                    $data = [];
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Get fall point content
     * 
     * 1. Get the data in an ordered non-contiguous index array. Such as:
     * - $data = ['2019-05-01' => '20', '2019-06-01' => '30', '2019-06-15' => '50'];
     * - $value = ArrayHelper::getFallContent($data, '2019-06-11', false); // $value = 30;
     * 2. If there is no fall point, return null.
     * 3. Ensure performance by sorting $data ahead of time:
     * - a. Sorting $data (ASC)
     * - b. Close $sortOut
     * - c. Use function ArrayHelper::getFallContent()
     * 
     * @param array $data
     * @param string $referKey
     * @param string $sortOut Whether the input needs to be rearranged. Value: true, false, 'default'. If it is 'default', see getSortOut()
     * @return mixed
     */
    public static function getFallContent(array $data, $referKey, $sortOut = 'default')
    {
        /*** Arguments prepare ***/
        // Data sorting out
        $sortOut = $sortOut === 'default' ? self::getSortOut() : !!$sortOut;
        if ($sortOut) {
            ksort($data);
        }

        // Fall point content
        $opt = null;
        foreach ($data as $key => $value) {
            if ($key > $referKey) {
                break;
            }
            $opt = $value;
        }

        return $opt;
    }

    /**
     * Data gather by list
     * 
     * 依欄位清單，對目標資料收集資料並分類
     * Collect and classify target data according to the list of fields
     * 
     * 一般狀況，使用array_column()內建函式可完成資料搜集，但如需搜集多欄位資料則無法使用array_column()
     * 
     * 資料陣列，格式：array(stdClass|array usersInfo1, stdClass|array usersInfo2, stdClass|array usersInfo3, ............);
     * 使用範例：
     * - $data = $this->db->select('*')->from('users')->get()->result();
     * - 欄位 manager, sign_manager, create_user 值放在同一個一維陣列中
     * - $ssnList1 = ArrayHelper::gather($data, array('manager', 'sign_manager','create_user'), 1);
     * - 欄位 manager 值放一個陣列, 欄位 sign_manager, create_user 值放同一陣列中，形成2維陣列 $dataList2 = ['manager' => [], 'other' => []];
     * - $ssnList2 = ArrayHelper::gather($data, array('manager' => array('manager'), 'other' => array('sign_manager','create_user')), 1);
     *
     * 遞迴效率太差 - 改成遞迴到最後一層陣列後直接處理，不再往下遞迴
     *
     * @author Mars.Hung <tfaredxj@gmail.com>
     *
     * @param array $data
     *            資料陣列
     * @param array $colNameList
     *            資料陣列中，目標資料的Key名稱
     * @param number $objLv
     *            資料物件所在層數
     * @param array $dataList
     *            遞迴用
     */
    public static function gather($data, $colNameList, $objLv = 1, $dataList = array())
    {
        // 將物件轉成陣列
        $data = is_object($data) ? (array) $data : $data;

        // 遍歷陣列 - 只處理陣列
        if (is_array($data) && !empty($data)) {
            if ($objLv > 1) {
                // === 超過1層 ===
                foreach ($data as $k => $row) {
                    // 遞迴處理
                    $dataList = self::gather($row, $colNameList, $objLv - 1, $dataList);
                }
            } else {
                // === 1層 ===
                // 遍歷要處理的資料
                foreach ($data as $k => $row) {
                    $row = (array) $row;
                    // 遍歷目標欄位名稱
                    foreach ($colNameList as $tKey1 => $tCol) {
                        if (is_array($tCol)) {
                            // === 如果目標是二維陣列，輸出的資料也要依目標陣列的第一維度分類 ===
                            foreach ($tCol as $tKey2 => $tCol2) {
                                if (isset($row[$tCol2])) {
                                    $dataList[$tKey1][$row[$tCol2]] = $row[$tCol2];
                                }
                            }
                        } else {
                            // === 目標是一維陣列，不需分類 ===
                            if (isset($row[$tCol])) {
                                $dataList[$row[$tCol]] = $row[$tCol];
                            }
                        }
                    }
                }
            }
        }

        return $dataList;
    }

    /**
     * Array Deff Recursive
     * 
     * Compare $srcArray with $contrast and display it if something on $srcArray is not on $contrast.
     * 
     * @param array $srcArray            
     * @param array $contrast            
     * @return array
     */
    public static function diffRecursive(array $srcArray, $contrast)
    {
        $diffArray = [];

        // Loop $srcArray
        foreach ($srcArray as $key => $value) {
            if (is_array($contrast) && array_key_exists($key, $contrast)) {
                if (is_array($value)) {
                    // Recursive
                    $aRecursiveDiff = self::diffRecursive($value, $contrast[$key]);
                    if (!empty($aRecursiveDiff)) {
                        // Have Diff, replace origin data
                        $diffArray[$key] = $aRecursiveDiff;
                    }
                } elseif ($value !== $contrast[$key]) {
                    // Value(with data type) not the same
                    $diffArray[$key] = $value;
                }
            } else {
                // No key or $contrast not array
                $diffArray[$key] = $value;
            }
        }

        return $diffArray;
    }

    /**
     * Array Sort Recursive
     * 
     * - Support: ksort(default), krsort
     * 
     * @param array $srcArray
     * @param string $type ksort(default), krsort
     */
    public static function sortRecursive(array &$srcArray, $type = 'ksort')
    {
        // Run ksort(default), krsort, sort, rsort
        switch ($type) {
            case 'ksort':
            default:
                ksort($srcArray);
                break;
            case 'krsort':
                krsort($srcArray);
                break;
        }

        // If child element is array, recursive
        foreach ($srcArray as $key => &$value) {
            is_array($value) && self::sortRecursive($value, $type);
        }
    }

    /**
     * Filter array according to the allowed keys
     * 
     * $nArray = ArrayHelper::filterKey($array, ['id', 'firstName', 'lastName', 'gender']);
     * $nArray = ArrayHelper::filterKey($array, 'id, firstName, lastName, gender');
     * 
     * @param array $array The array to compare from
     * @param array|string $keys Key list to compare against
     * @param bool $fillKey Fill the key that does not exist in the array, default true
     * @return array
     */
    public static function filterKey(array $array, $keys, $fillKey = true)
    {
        /*** Arguments prepare ***/
        // keys prepare
        if (is_string($keys)) {
            $keys = array_map('trim', explode(',', $keys));
        }

        $keysFlip = array_fill_keys($keys, '');

        // Fill the key
        if ($fillKey) {
            $array = $array + $keysFlip;
        }

        return array_intersect_key($array, $keysFlip);
    }

    /**
     * **********************************************
     * ************** Options Function **************
     * **********************************************
     */

    /**
     * Auto sort out : Set option
     *
     * 1. Scope: Global
     *
     * @param bool $bool default true
     * @return $this
     */
    public static function setSortOut($bool = true)
    {
        self::$_options['sortOut'] = !!$bool;

        return new static();
    }

    /**
     * Auto sort out : Get option
     *
     * @return bool
     */
    public static function getSortOut()
    {
        return self::$_options['sortOut'];
    }

    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */

    /**
     * Refactor Array $data structure by $keys
     * 
     * - When $outputBy="return", $data will change after indexBy() is executed.  
     *   Since $data is a reference, the return is ArrayHelper. 
     * - When $outputBy="return", $data is a reference, but the function does not change it.  
     *   Instead, use the $data content as a reference for the output data content.
     *   ex.
     *   // Processing :
     *   $return  = ArrayHelper::indexBy($data, ['id', 'name']);
     *   // Reference status :
     *   $return[$id][$name] = & $row = & $data[$key];
     * 
     * @param array $data Array data for handling. Two-dimensional array
     * @param string|array $keys
     * @param string $type  indexBy/groupBy/indexOnly
     * @return array $result
     */
    protected static function _refactorBy(array &$data, $keys, $type = 'indexBy')
    {
        // 參數處理
        $keys = (array) $keys;

        $result = [];

        // 遍歷待處理陣列
        foreach ($data as &$row) {
            // 旗標，是否取得索引
            $getIndex = false;
            // 位置初始化 - 傳址
            $rRefer = &$result;
            // 可用的index清單
            $indexs = [];

            // 遍歷$keys陣列 - 建構索引位置
            foreach ($keys as $key) {
                $vKey = null;

                // 取得索引資料 - 從$key
                if (is_object($row) && isset($row->{$key})) {
                    $vKey = $row->{$key};
                } elseif (is_array($row) && isset($row[$key])) {
                    $vKey = $row[$key];
                }

                // 有無法取得索引資料，跳出
                if (is_null($vKey)) {
                    $getIndex = false;
                    break;
                }

                // 記錄可用的index
                $indexs[] = $vKey;

                // 本次索引完成
                $getIndex = true;
            }

            // 略過無法取得索引或索引不完整的資料
            if (!$getIndex) {
                continue;
            }

            // 變更位置 - 傳址 - 為避免傳址設定($rRefer = &$row;)無法設定，取出最後一個索引以免設定歧意
            $lastIdx = array_pop($indexs);
            foreach ($indexs as $idx) {
                $rRefer = &$rRefer[$idx];
            }

            // 將資料寫入索引位置
            switch ($type) {
                case 'indexBy':
                    $rRefer[$lastIdx] = &$row;
                    break;
                case 'groupBy':
                    $rRefer[$lastIdx][] = &$row;
                    break;
                case 'indexOnly':
                    $rRefer[$lastIdx] = '';
                    break;
            }
        }

        return $result;
    }
}
