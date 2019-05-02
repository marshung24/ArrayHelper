<?php
namespace marsapp\helper\myarray;

/**
 * Array Helper
 * 
 * Array processing library, providing functions such as rebuilding indexes, grouping, getting content, recursive difference sets, recursive sorting, etc.
 * 
 * @version 0.2.0
 * @author Mars Hung <tfaredxj@gmail.com>
 * @see https://github.com/marshung24/ArrayHelper
 */
class ArrayHelper
{
    
    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */
    
    /**
     * Data re-index by keys
     *
     * @param mixed $data Array/stdClass data for handling
     * @param mixed $keys keys for index key (Array/string)
     * @param boolean $obj2array stdClass convert to array
     * @return mixed Result with indexBy Keys
     */
    public static function indexBy(& $data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'indexBy');
    }
    
    /**
     * Group by keys
     * 
     * Data re-index and Group by keys
     * 
     * @param array|stdClass $data Array/stdClass data for handling
     * @param string|array $keys
     * @param boolean $obj2array Array content convert to array (when object)
     */
    public static function groupBy(& $data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'groupBy');
    }
    
    /**
     * Data re-index by keys, No Data
     *
     * @param array|stdClass $data Array/stdClass data for handling
     * @param string|array $keys
     * @param boolean $obj2array Array content convert to array (when object)
     */
    public static function indexOnly(& $data, $keys, $obj2array = false)
    {
        // Refactor Array $data structure by $keys
        return self::_refactorBy($data, $keys, $obj2array, $type = 'indexOnly');
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
    public static function getContent(Array $data, $indexTo = [], $exception = false)
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
        $data = is_object($data) ? (array)$data : $data;
        
        // 遍歷陣列 - 只處理陣列
        if (is_array($data) && ! empty($data)) {
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
    public static function diffRecursive(Array $srcArray, $contrast)
    {
        $diffArray = [];
        
        foreach ($srcArray as $key => $value) {
            if (is_array($contrast) && array_key_exists($key, $contrast)) {
                if (is_array($value)) {
                    $aRecursiveDiff = self::diffRecursive($value, $contrast[$key]);
                    if (! empty($aRecursiveDiff)) {
                        $diffArray[$key] = $aRecursiveDiff;
                    }
                } elseif ($value != $contrast[$key]) {
                    $diffArray[$key] = $value;
                }
            } else {
                $diffArray[$key] = $value;
            }
        }
        
        return $diffArray;
    }

    /**
     * Array Sort Recursive
     * 
     * @param array $srcArray
     * @param string $type ksort(default), krsort, sort, rsort
     */
    public static function sortRecursive(Array & $srcArray, $type = 'ksort')
    {
        // Run ksort(default), krsort, sort, rsort
        switch($type) {
            case 'ksort':
            default:
                ksort($srcArray);
                break;
            case 'krsort':
                krsort($srcArray);
                break;
            case 'sort':
                sort($srcArray);
                break;
            case 'rsort':
                rsort($srcArray);
                break;
        }
        
        // If child element is array, recursive
        foreach ($srcArray as $key => & $value) {
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
    public static function filterKey(Array $array, $keys, $fillKey = true)
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
     * ************** Private Function **************
     * **********************************************
     */
    
    /**
     * Refactor Array $data structure by $keys
     *
     * @param array|stdClass $data
     *            Array/stdClass data for handling
     * @param string|array $keys
     * @param boolean $obj2array
     *            Array content convert to array (when object)
     * @param string $type
     *            indexBy(index)/groupBy(group)/only index,no data(indexOnly/noData)
     */
    protected static function _refactorBy(& $data, $keys, $obj2array = false, $type = 'index')
    {
        // 參數處理
        $keys = (array) $keys;
        
        $result = [];
        
        // 遍歷待處理陣列
        foreach ($data as $row) {
            // 旗標，是否取得索引
            $getIndex = false;
            // 位置初炲化 - 傳址
            $rRefer = & $result;
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
            if (! $getIndex) {
                continue;
            }
            
            // 變更位置 - 傳址
            foreach ($indexs as $idx) {
                $rRefer = & $rRefer[$idx];
            }
            
            // 將資料寫入索引位置
            switch ($type) {
                case 'index':
                case 'indexBy':
                default:
                    $rRefer = $obj2array ? (array) $row : $row;
                    break;
                case 'group':
                case 'groupBy':
                    $rRefer[] = $obj2array ? (array) $row : $row;
                    break;
                case 'indexOnly':
                case 'noData':
                    $rRefer = '';
                    break;
            }
        }
        
        return $data = $result;
    }
}