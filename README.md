# ArrayHelper
Array processing library, providing functions such as rebuilding indexes, grouping, getting content, recursive difference sets, recursive sorting, etc.

> Continuation library marshung/helper, only keep and maintain ArrayHelper

[![Latest Stable Version](https://poser.pugx.org/marsapp/arrayhelper/v/stable)](https://packagist.org/packages/marsapp/arrayhelper) [![Total Downloads](https://poser.pugx.org/marsapp/arrayhelper/downloads)](https://packagist.org/packages/marsapp/arrayhelper) [![Latest Unstable Version](https://poser.pugx.org/marsapp/arrayhelper/v/unstable)](https://packagist.org/packages/marsapp/arrayhelper) [![License](https://poser.pugx.org/marsapp/arrayhelper/license)](https://packagist.org/packages/marsapp/arrayhelper)

# Outline
- [Installation](#Installation)
- [Usage](#Usage)
  - [ArrayHelper](#ArrayHelper)


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
## [ArrayHelper](#Outline)
Namespace use:
```php
use marsapp\helper\myarray\ArrayHelper;
```



### indexBy()
Data re-index by keys
```php
indexBy(Array & $data, Array|String $keys, Bool $obj2array = false) : array
```
> Since $data is a reference, $data will change after indexBy() is executed.  
> Since $data is a reference, the return is useless.  
> If you want to keep $data, you can clone it before using it.  

Example :
```php
$data = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];

ArrayHelper::indexBy($data, ['c_sn','u_sn','u_no']);
```

$data reqult:
```php
[
    'a110' => [
        'b1' => ['a001' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']],
        'b2' => ['b012' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']],
    ],
];

```


### groupBy()
Data re-index and Group by keys
```php
groupBy(Array & $data, Array|String $keys, Bool $obj2array = false) : array
```
> Since $data is a reference, $data will change after indexBy() is executed.  
> Since $data is a reference, the return is useless.  
> If you want to keep $data, you can clone it before using it.  

Example :
```php
$data = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3'],
];

ArrayHelper::groupBy($data, ['c_sn','u_sn','u_no']);
```

$data reqult:
```php
[
    'a110' => [
        'b1' => ['a001' => [
                0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
            ]
        ],
        'b2' => ['b012' => [
                0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
                1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
            ]
        ],
    ],
];
```

### indexOnly()
Data re-index by keys, No Data
```php
indexOnly(Array & $data, Array|String $keys, Bool $obj2array = false) : array
```
> Since $data is a reference, $data will change after indexBy() is executed.  
> Since $data is a reference, the return is useless.  
> If you want to keep $data, you can clone it before using it.  

Example :
```php
$data = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];

ArrayHelper::indexOnly($data, ['c_sn','u_sn','u_no']);
```

$data reqult:
```php
[
    'a110' => [
        'b1' => [
            'a001' => ''
        ],
        'b2' => [
            'b012' => ''
        ],
    ],
];
```


### getContent()
Get Data content by index
```php
getContent(Array $data, Array|String $indexTo = [], Bool $exception = false) : array|mixed
```

Example:
```php
$data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];

$output = ArrayHelper::getContent($data);
// $output: ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];

$output = ArrayHelper::getContent($data, 'user');
  // or
$output = ArrayHelper::getContent($data, ['user']);
// $output: ['name' => 'Mars', 'birthday' => '2000-01-01'];

$output = ArrayHelper::getContent($data, ['user', 'name']);
// $outpu: Mars

$output = ArrayHelper::getContent($data, ['user', 'name', 'aaa']);
// $outpu: []
```


### gather()
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


### diffRecursive()
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
];
$data2 = [
    0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2222'],
    2 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'c024', 'u_name' => 'user name 3'],
];

$diff = ArrayHelper::diffRecursive($data1, $data2);
```
$diff result :
```php
[
    1 => ['u_name' => 'name2'],
    2 => ['u_sn' => NULL,'u_name' => 'name3']
];
```


### sortRecursive()
Array Sort Recursive
```php
sortRecursive(Array & $srcArray, $type = 'ksort') : void
```
> $srcArray is a reference  
> $type : ksort(default), krsort, sort, rsort  

Example :
```php
$data1 = [
    'b1' => [
        0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
    ],
    'b2' => [
        0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
        1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
    ],
];

$data2 = $data1;

ArrayHelper::sortRecursive($data1, 'ksort');
ArrayHelper::sortRecursive($data2, 'krsort');
```

$data1 result:
```php
[
    'b1' => [
        0 => ['c_sn' => 'a110','u_name' => 'name1','u_no' => 'a001','u_sn' => 'b1']
    ],
    'b2' => [
        0 => ['c_sn' => 'a110','u_name' => 'name2','u_no' => 'b012','u_sn' => 'b2'],
        1 => ['c_sn' => 'a110','u_name' => 'user name 3','u_no' => 'b012','u_sn' => 'b2']
    ]
];
```

$data2 result:
```php
[
    'b2' => [
        1 => ['u_sn' => 'b2','u_no' => 'b012','u_name' => 'user name 3','c_sn' => 'a110'],
        0 => ['u_sn' => 'b2','u_no' => 'b012','u_name' => 'name2','c_sn' => 'a110']
    ],
    'b1' => [
        0 => ['u_sn' => 'b1','u_no' => 'a001','u_name' => 'name1','c_sn' => 'a110']
    ]
];
```
