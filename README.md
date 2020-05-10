# ArrayHelper
Array processing library, providing functions such as rebuilding indexes, grouping, getting content, recursive difference sets, recursive sorting, etc.

> Continuation library marshung/helper, only keep and maintain ArrayHelper

[![Latest Stable Version](https://poser.pugx.org/marsapp/arrayhelper/v/stable)](https://packagist.org/packages/marsapp/arrayhelper) [![Total Downloads](https://poser.pugx.org/marsapp/arrayhelper/downloads)](https://packagist.org/packages/marsapp/arrayhelper) [![Latest Unstable Version](https://poser.pugx.org/marsapp/arrayhelper/v/unstable)](https://packagist.org/packages/marsapp/arrayhelper) [![License](https://poser.pugx.org/marsapp/arrayhelper/license)](https://packagist.org/packages/marsapp/arrayhelper)

# Outline
- [ArrayHelper](#arrayhelper)
- [Outline](#outline)
- [Installation](#installation)
  - [Composer Install](#composer-install)
  - [Include](#include)
- [Usage](#usage)
  - [Example](#example)
- [API Reference](#api-reference)
  - [indexBy()](#indexby)
  - [groupBy()](#groupby)
  - [indexOnly()](#indexonly)
  - [getContent()](#getcontent)
  - [getFallContent()](#getfallcontent)
  - [gather()](#gather)
  - [diffRecursive()](#diffrecursive)
  - [sortRecursive()](#sortrecursive)
  - [filterKey()](#filterkey)
  - [setSortOut()](#setsortout)
  - [getSortOut()](#getsortout)

# [Installation](#Outline)
## Composer Install
```
# composer require marsapp/arrayhelper
```

## Include
Include composer autoloader before use.
```php
require __PATH__ . "vendor/autoload.php";
```

# [Usage](#Outline)
## [Example](#Outline)
Namespace use:
```php
// Use namespace
use marsapp\helper\myarray\ArrayHelper;

// Data
$data = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];

// Index by
ArrayHelper::indexBy($data, ['c_sn', 'u_no']);

// Get name by a110 => a001 => u_name
$name = ArrayHelper::getContent($data, 'a110, a001, u_name');
// $name = name1;
```

# [API Reference](#outline)
## [indexBy()](#outline)
Data re-index by keys
```php
indexBy(Array & $data, Array|String $keys, String $outputBy = 'reference') : array
```
> - When $outputBy="return", $data will change after indexBy() is executed.  
>   Since $data is a reference, the return is ArrayHelper.  
> - When $outputBy="return", $data is a reference, but the function does not change it.  
>   Instead, use the $data content as a reference for the output data content.
>   ex.
>   // Processing :
>   $return  = ArrayHelper::indexBy($data, ['id', 'name']);
>   // Reference status :
>   $return[$id][$name] = & $row = & $data[$key];


Example :
```php
$sample = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];


/**
 * Output by reference
 */
$data1 = $sample;
ArrayHelper::indexBy($data1, ['c_sn','u_sn','u_no']);
// $data1 = [
//    'a110' => [
//        'b1' => ['a001' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']],
//        'b2' => ['b012' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']],
//    ],
// ];

/**
 * Output by return
 * 
 * - $data2 No changes here
 */
$data2 = $sample;
$result2 = ArrayHelper::indexBy($data2, ['c_sn','u_sn','u_no'], 'return');
// $result2 = [
//    'a110' => [
//        'b1' => ['a001' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']],
//        'b2' => ['b012' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']],
//    ],
// ];
```


## [groupBy()](#outline)
Data re-index and Group by keys
```php
groupBy(Array & $data, Array|String $keys, String $outputBy = 'reference') : array
```
> - When $outputBy="return", $data will change after groupBy() is executed.  
>   Since $data is a reference, the return is ArrayHelper.  
> - When $outputBy="return", $data is a reference, but the function does not change it.  
>   Instead, use the $data content as a reference for the output data content.
>   ex.
>   // Processing :
>   $return  = ArrayHelper::groupBy($data, ['id', 'name']);
>   // Reference status :
>   $return[$id][$name][$k1] = & $row = & $data[$key];

Example :
```php
$sample = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3'],
];

/**
 * Output by reference
 */
$data1 = $sample;
ArrayHelper::groupBy($data1, ['c_sn','u_sn','u_no']);
// $data1 = [
//     'a110' => [
//         'b1' => ['a001' => [
//                 0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
//             ]
//         ],
//         'b2' => ['b012' => [
//                 0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
//                 1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
//             ]
//         ],
//     ],
// ];

/**
 * Output by return
 * 
 * - $data2 No changes here
 */
$data2 = $sample;
$result2 = ArrayHelper::groupBy($data2, ['c_sn','u_sn','u_no'], 'return');
// $result2 = [
//     'a110' => [
//         'b1' => ['a001' => [
//                 0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
//             ]
//         ],
//         'b2' => ['b012' => [
//                 0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
//                 1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
//             ]
//         ],
//     ],
// ];
```

## [indexOnly()](#outline)
Data re-index by keys, No Data
```php
indexOnly(Array & $data, Array|String $keys, String $outputBy = 'reference') : array
```
> - When $outputBy="return", $data will change after indexOnly() is executed.  
>   Since $data is a reference, the return is ArrayHelper.  
> - When $outputBy="return", $data is a reference, but the function does not change it.  

Example :
```php
$sample = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];

/**
 * Output by reference
 */
$data1 = $sample;
ArrayHelper::indexOnly($data1, ['c_sn','u_sn','u_no']);
// $data1 = [
//     'a110' => [
//         'b1' => [
//             'a001' => ''
//         ],
//         'b2' => [
//             'b012' => ''
//         ],
//     ],
// ];

/**
 * Output by return
 * 
 * - $data2 No changes here
 */
$data2 = $sample;
$result2 = ArrayHelper::indexOnly($data2, ['c_sn','u_sn','u_no'], 'return');
// $result2 = [
//     'a110' => [
//         'b1' => [
//             'a001' => ''
//         ],
//         'b2' => [
//             'b012' => ''
//         ],
//     ],
// ];
```


## [getContent()](#outline)
Get Data content by index
```php
getContent(Array $data, Array|String $indexTo = [], Bool $exception = false) : array|mixed
```

Example:
```php
$data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];

// No indexTo, get all
$output = ArrayHelper::getContent($data);
// $output: ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];

// Target is array
$output = ArrayHelper::getContent($data, 'user');
$output = ArrayHelper::getContent($data, ['user']);
// $output: ['name' => 'Mars', 'birthday' => '2000-01-01'];

// Target is string
$output = ArrayHelper::getContent($data, 'user, name');
$output = ArrayHelper::getContent($data, ['user', 'name']);
// $outpu: Mars

// No target
$output = ArrayHelper::getContent($data, 'user, name, aaa');
$output = ArrayHelper::getContent($data, ['user', 'name', 'aaa']);
// $outpu: null
```

## [getFallContent()](#outline)
Get fall point content
> 1. Get the data in an ordered non-contiguous index array
> 2. If there is no fall point, return null.
> 3. Ensure performance by sorting $data ahead of time:
>    - a. Sorting $data (ASC)
>    - b. Close $sortOut
>    - c. Use function ArrayHelper::getFallContent()

```php
getFallContent(Array $data, $referKey, $sortOut = 'default') : mixed
```
> Parameters
> - $data: The array to compare from. array
> - $referKey: Refer key to compare against. string
> - $sortOut: Whether the input needs to be rearranged. Value: true, false, 'default'. If it is 'default', see getSortOut()
> 
> Return Values
> - Returns the resulting mixed.

Example :
```php
$data = ['2019-05-01' => '20', '2019-06-15' => '50', '2019-06-01' => '30'];

/**
 * No sortOut data
 */
$value = ArrayHelper::getFallContent($data, '2019-06-11', false);
// $value = 20;

/**
 * SortOut data before run
 */
$value = ArrayHelper::getFallContent($data, '2019-06-11', true);
// $value = 30;
```


## [gather()](#outline)
Data gather by list
> Collect and classify target data according to the list of fields

```php
gather(Array $data, Array $colNameList, Int $objLv = 1) : array
```

Example Data :
```php
$data = [
    0 => ['sn' => '1785','m_sn' => '40','d_sn' => '751','r_type' => 'staff','manager' => '1','s_manager' => '1','c_user' => '506'],
    1 => ['sn' => '1371','m_sn' => '40','d_sn' => '583','r_type' => 'staff','manager' => '61','s_manager' => '0','c_user' => '118'],
    2 => ['sn' => '1373','m_sn' => '40','d_sn' => '584','r_type' => 'staff','manager' => '61','s_manager' => '0','c_user' => '118'],
    3 => ['sn' => '7855','m_sn' => '40','d_sn' => '2303','r_type' => 'staff','manager' => '71','s_manager' => '0','c_user' => '61'],
    4 => ['sn' => '7856','m_sn' => '40','d_sn' => '2304','r_type' => 'staff','manager' => '75','s_manager' => '0','c_user' => '61']
];
```

Example 1 :
> Field `manager`, `s_manager`, `c_user values` are placed in the same one-dimensional array
```php
$ssnList1 = ArrayHelper::gather($data, array('manager', 's_manager','c_user'), 1);
```
$ssnList1 reqult:
```php
[1 => '1',506 => '506',61 => '61',0 => '0',118 => '118',71 => '71',75 => '75'];
```

Example 2 :
> The field `manager` is placed in an array, the fields `s_manager`, and the `c_user` values are placed in the same array. Form a 2-dimensional array
```php
$ssnList2 = ArrayHelper::gather($data, array('manager' => array('manager'), 'other' => array('s_manager','c_user')), 1);
```
$ssnList2 reqult:
```php
[
    'manager' => [1 => '1',61 => '61',71 => '71',75 => '75'],
    'other' => [1 => '1',506 => '506',0 => '0',118 => '118',61 => '61']
];
```


## [diffRecursive()](#outline)
Array Deff Recursive
> Compare $srcArray with $contrast and display it if something on $srcArray is not on $contrast.
```php
diffRecursive(Array $srcArray, $contrast) : array
```

Example :
```php
$data1 = [
    0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
    2 => ['c_sn' => 'a110', 'u_sn' => null, 'u_no' => 'c024', 'u_name' => 'name3'],
    3 => ['c_sn' => '0'],
    4 => ['c_sn' => '1'],
    5 => ['c_sn' => 0],
    6 => ['c_sn' => 1],
    7 => ['0' => 'a110'],
    8 => ['1' => 'a110'],
    9 => [0 => 'a110'],
    10 => [1 => 'a110'],
    11 => ['c_sn' => 110],
    12 => ['c_sn' => '110'],
    13 => [110 => '110'], // Unable to compare key data type
    14 => ['110' => '110'],
    15 => ['110' => '110', 110 => 'a110'], // Unable to compare key data type
];
$data2 = [
    0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2222'],
    2 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'c024', 'u_name' => 'user name 3'],
    3 => ['c_sn' => 'a110'],
    4 => ['c_sn' => 'a110'],
    5 => ['c_sn' => 'a110'],
    6 => ['c_sn' => 'a110'],
    7 => ['c_sn' => 'a110'],
    8 => ['c_sn' => 'a110'],
    9 => ['c_sn' => 'a110'],
    10 => ['c_sn' => 'a110'],
    11 => ['c_sn' => '110'],
    12 => ['c_sn' => '110'],
    13 => ['110' => '110'], // Unable to compare key data type
    14 => ['110' => '110'],
    15 => ['110' => 'a110'], // Unable to compare key data type
];

$diff = ArrayHelper::diffRecursive($data1, $data2);
// $diff = [
//     1 => ['u_name' => 'name2'],
//     2 => ['u_sn' => NULL,'u_name' => 'name3']
//     3 => ['c_sn' => '0'],
//     4 => ['c_sn' => '1'],
//     5 => ['c_sn' => 0],
//     6 => ['c_sn' => 1],
//     7 => ['0' => 'a110'],
//     8 => ['1' => 'a110'],
//     9 => [0 => 'a110'],
//     10 => [1 => 'a110'],
//     11 => ['c_sn' => 110],
// ];
```


## [sortRecursive()](#outline)
Array Sort Recursive
```php
sortRecursive(Array & $srcArray, $type = 'ksort') : void
```
> $srcArray is a reference  
> $type : ksort(default), krsort, sort, rsort  

Example :
```php
$sample = [
    'b1' => [
        0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
    ],
    'b2' => [
        0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
        1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
    ],
];

/**
 * Sort Recursive by ksort
 */
$data1 = $sample;
ArrayHelper::sortRecursive($data1, 'ksort');
// $data1 = [
//     'b1' => [
//         0 => ['c_sn' => 'a110','u_name' => 'name1','u_no' => 'a001','u_sn' => 'b1']
//     ],
//     'b2' => [
//         0 => ['c_sn' => 'a110','u_name' => 'name2','u_no' => 'b012','u_sn' => 'b2'],
//         1 => ['c_sn' => 'a110','u_name' => 'user name 3','u_no' => 'b012','u_sn' => 'b2']
//     ]
// ];

/**
 * Sort Recursive by krsort
 */
$data2 = $sample;
ArrayHelper::sortRecursive($data2, 'krsort');
// $data2 = [
//     'b2' => [
//         1 => ['u_sn' => 'b2','u_no' => 'b012','u_name' => 'user name 3','c_sn' => 'a110'],
//         0 => ['u_sn' => 'b2','u_no' => 'b012','u_name' => 'name2','c_sn' => 'a110']
//     ],
//     'b1' => [
//         0 => ['u_sn' => 'b1','u_no' => 'a001','u_name' => 'name1','c_sn' => 'a110']
//     ]
// ];
```

## [filterKey()](#outline)
Filter array according to the allowed keys

```php
filterKey(Array $array, $keys, $fillKey = true) : array
```
> Parameters
> - $array: The array to compare from. array
> - $keys: Key list to compare against. array|string
> - $fillKey: Fill the key that does not exist in the array, default true. bool
> 
> Return Values
> - Returns the resulting array.

Example :
```php
$array = ['sn' => '1785','m_sn' => '40','d_sn' => '751','r_type' => 'staff','manager' => '1','s_manager' => '1','c_user' => '506'];

// fill key
$result = ArrayHelper::filterKey($array, ['sn', 'd_sn', 'r_type', 'manager', 'nooooooooo']);
$result = ArrayHelper::filterKey($array, 'sn,d_sn, r_type, manager, nooooooooo');
// $result = ['sn' => '1785','d_sn' => '751','r_type' => 'staff','manager' => '1', 'nooooooooo' => ''];

// No fill key
$result = ArrayHelper::filterKey($array, ['sn', 'd_sn', 'r_type', 'manager', 'nooooooooo'], false);
$result = ArrayHelper::filterKey($array, 'sn,d_sn, r_type, manager, nooooooooo', false);
// $result = ['sn' => '1785','d_sn' => '751','r_type' => 'staff','manager' => '1'];
```

## [setSortOut()](#outline)
Auto sort out : Set option

```php
setSortOut($bool = true) : self
```

## [getSortOut()](#outline)
Auto sort out : Get option

```php
getSortOut() : bool
```
