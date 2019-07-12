<?php

namespace marsapp\helper\test\timeperiod;

use PHPUnit\Framework\TestCase;
use marsapp\helper\myarray\ArrayHelper;
use \Exception;

/**
 * Test for PHP Unit - ArrayHelper
 * 
 * @author Mars.Hung <tfaredexj@gmail.com>
 */
class ArrayHelperTest extends TestCase
{

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */

    /**
     * Test indexBy()
     */
    public function testIndexBy()
    {
        /**
         * Data templete and excepted
         */
        $templete = [
            [
                ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']
            ],
            ['c_sn', 'u_sn', 'u_no']
        ];
        $expected = [
            'a110' => [
                'b1' => ['a001' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']],
                'b2' => ['b012' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']],
            ],
        ];

        /**
         * Output by reference
         */
        $data0 = $templete[0];
        $keys0 = $templete[1];
        $result2 = ArrayHelper::indexBy($data0, $keys0, 'reference');
        // check data
        $this->assertEquals($expected, $data0);
        // check return - is return self object
        $this->assertInstanceOf(ArrayHelper::class, $result2);

        /**
         * Test Output by return
         */
        $data1 = $templete[0];
        $keys1 = $templete[1];
        $result1 = ArrayHelper::indexBy($data1, $keys1, 'return');
        // check data - no change
        $this->assertEquals($templete[0], $data1);
        // check return
        $this->assertEquals($expected, $result1);

        // Test Content is reference
        $c_sn = $data1[0]['c_sn'];
        $u_sn = $data1[0]['u_sn'];
        $u_no = $data1[0]['u_no'];
        $data1[0]['c_sn'] = 'aaaaa';
        $this->assertEquals('aaaaa', $result1[$c_sn][$u_sn][$u_no]['c_sn']);

        /**
         * Test Jagged array
         */
        $templete = [
            [
                ['u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_no' => 'b012', 'u_name' => 'name2'], ['c_sn' => 'a110', 'u_sn' => 'b3', 'u_no' => 'b012', 'u_name' => 'name3']
            ],
            ['c_sn', 'u_sn', 'u_no']
        ];
        $expected = [
            'a110' => [
                'b3' => ['b012' => ['c_sn' => 'a110', 'u_sn' => 'b3', 'u_no' => 'b012', 'u_name' => 'name3']],
            ],
        ];
        $data3 = $templete[0];
        $keys3 = $templete[1];
        $result3 = ArrayHelper::indexBy($data3, $keys3, 'return');
        // check data - no change
        $this->assertEquals($templete[0], $data3);
        $this->assertEquals($expected, $result3);

        /**
         * Test: Object
         */
        $templete = [
            [
                (object)['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], (object)['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']
            ],
            ['c_sn', 'u_sn', 'u_no']
        ];
        $expected = [
            'a110' => [
                'b1' => ['a001' => (object)['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']],
                'b2' => ['b012' => (object)['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']],
            ],
        ];
        $data4 = $templete[0];
        $keys4 = $templete[1];
        $result4 = ArrayHelper::indexBy($data4, $keys4, 'return');
        // check data - no change
        $this->assertEquals($templete[0], $data4);
        $this->assertEquals($expected, $result4);
    }

    /**
     * Test indexBy() 2
     */
    public function testIndexBy2()
    {
        $data = [
            ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
            ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
        ];

        $expected = [
            'b1' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
            'b2' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
        ];

        /**
         * Output by return
         */
        $data1 = $data;
        $rData1 = ArrayHelper::indexBy($data1, 'u_sn', 'return');
        $this->assertEquals($expected, $rData1);
        $this->assertEquals($data, $data1);

        /**
         * Output by reference
         */
        // Define $outputBy
        $data2 = $data;
        $rData2 = ArrayHelper::indexBy($data2, 'u_sn', 'reference');
        $this->assertInstanceOf(ArrayHelper::class, $rData2);
        $this->assertEquals($expected, $data2);
        // Ignore $outputBy
        $data3 = $data;
        $rData3 = ArrayHelper::indexBy($data3, 'u_sn');
        $this->assertInstanceOf(ArrayHelper::class, $rData3);
        $this->assertEquals($expected, $data3);

        // Test Content is reference
        $u_sn = $data1[0]['u_sn'];
        $data1[0]['c_sn'] = 'aaaaa';
        $this->assertEquals('aaaaa', $rData1[$u_sn]['c_sn']);
    }

    /**
     * Test groupBy()
     *
     * @return void
     */
    public function testGroupBy()
    {
        $data = [
            ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
            ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
            ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3'],
        ];

        $expected = [
            'a110' => [
                'b1' => [
                    'a001' => [
                        0 => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']
                    ]
                ],
                'b2' => [
                    'b012' => [
                        0 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
                        1 => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3']
                    ]
                ],
            ],
        ];

        /**
         * Output by return
         */
        $data1 = $data;
        $rData1 = ArrayHelper::groupBy($data1, ['c_sn', 'u_sn', 'u_no'], 'return');
        $this->assertEquals($rData1, $expected);
        $this->assertEquals($data1, $data);

        // Test Content is reference
        $c_sn = $data1[0]['c_sn'];
        $u_sn = $data1[0]['u_sn'];
        $u_no = $data1[0]['u_no'];
        $data1[0]['c_sn'] = 'aaaaa';
        $this->assertEquals('aaaaa', $rData1[$c_sn][$u_sn][$u_no][0]['c_sn']);

        /**
         * Output by reference
         */
        // Define $outputBy
        $data2 = $data;
        $rData2 = ArrayHelper::groupBy($data2, ['c_sn', 'u_sn', 'u_no'], 'reference');
        $this->assertInstanceOf(ArrayHelper::class, $rData2);
        $this->assertEquals($data2, $expected);
        // Ignore $outputBy
        $data2 = $data;
        $rData2 = ArrayHelper::groupBy($data2, ['c_sn', 'u_sn', 'u_no']);
        $this->assertInstanceOf(ArrayHelper::class, $rData2);
        $this->assertEquals($data2, $expected);
    }

    /**
     * Test indexOnly()
     */
    public function testIndexOnly()
    {
        $data = [
            ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
            ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
        ];

        $expected = [
            'a110' => [
                'b1' => [
                    'a001' => ''
                ],
                'b2' => [
                    'b012' => ''
                ],
            ],
        ];

        /**
         * Output by return
         */
        $data1 = $data;
        $rData1 = ArrayHelper::indexOnly($data1, ['c_sn', 'u_sn', 'u_no'], 'return');
        $this->assertEquals($rData1, $expected);
        $this->assertEquals($data1, $data);

        /**
         * Output by reference
         */
        // Define $outputBy
        $data2 = $data;
        $rData2 = ArrayHelper::indexOnly($data2, ['c_sn', 'u_sn', 'u_no'], 'reference');
        $this->assertInstanceOf(ArrayHelper::class, $rData2);
        $this->assertEquals($data2, $expected);
        // Ignore $outputBy
        $data2 = $data;
        $rData2 = ArrayHelper::indexOnly($data2, ['c_sn', 'u_sn', 'u_no']);
        $this->assertInstanceOf(ArrayHelper::class, $rData2);
        $this->assertEquals($data2, $expected);
    }

    /**
     * Test getContent()
     */
    public function testGetContent()
    {
        $data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];

        $output = ArrayHelper::getContent($data);
        // $output: ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];
        $this->assertEquals($output, ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']]);


        /**
         * Test: get by Single layer key
         */
        // By array
        $output = ArrayHelper::getContent($data, ['user']);
        // $output: ['name' => 'Mars', 'birthday' => '2000-01-01'];
        $this->assertEquals($output, ['name' => 'Mars', 'birthday' => '2000-01-01']);
        // By string
        $output = ArrayHelper::getContent($data, 'user');
        $this->assertEquals($output, ['name' => 'Mars', 'birthday' => '2000-01-01']);

        /**
         * Test: get by Multi-layer key
         */
        // By array
        $output = ArrayHelper::getContent($data, ['user', 'name']);
        // $outpu: Mars
        $this->assertEquals($output, 'Mars');
        // By string
        $output = ArrayHelper::getContent($data, 'user, name');
        // $outpu: Mars
        $this->assertEquals($output, 'Mars');

        /**
         * Test: get nothing
         */
        // Return empty array
        $output = ArrayHelper::getContent($data, ['user', 'name', 'aaa']);
        // $outpu: []
        $this->assertEquals($output, []);
        // Throw exception
        try {
            $output = 'NoException';
            ArrayHelper::getContent($data, ['user', 'name', 'aaa'], true);
        } catch (Exception $e) {
            $output = 'NoException';
        }
        $this->assertEquals($output, 'NoException');
    }

    /**
     * Test getFallContent()
     */
    public function testGetFallContent()
    {
        $data = ['2019-05-01' => '20', '2019-06-15' => '50', '2019-06-01' => '30'];
        $expected = '30';

        /**
         * By default
         */
        $value = ArrayHelper::getFallContent($data, '2019-06-11');
        $this->assertEquals($value, $expected);

        $value = ArrayHelper::getFallContent($data, '2019-06-11', false);
        $this->assertEquals($value, 20);

        $value = ArrayHelper::getFallContent($data, '2019-06-11', true);
        $this->assertEquals($value, $expected);

        /**
         * By setSortOut()
         */
        $value = ArrayHelper::setSortOut(true)->getFallContent($data, '2019-06-11');
        $this->assertEquals($value, $expected);
        $value = ArrayHelper::setSortOut(false)->getFallContent($data, '2019-06-11');
        $this->assertEquals($value, 20);
    }

    /**
     * Test gather()
     */
    public function testGather()
    {
        /**
         * One Layer
         */
        $data = [
            0 => ['sn' => '1785', 'm_sn' => '40', 'd_sn' => '751', 'r_type' => 'staff', 'manager' => '1', 's_manager' => '1', 'c_user' => '506'],
            1 => ['sn' => '1371', 'm_sn' => '40', 'd_sn' => '583', 'r_type' => 'staff', 'manager' => '61', 's_manager' => '0', 'c_user' => '118'],
            2 => ['sn' => '1373', 'm_sn' => '40', 'd_sn' => '584', 'r_type' => 'staff', 'manager' => '61', 's_manager' => '0', 'c_user' => '118'],
            3 => ['sn' => '7855', 'm_sn' => '40', 'd_sn' => '2303', 'r_type' => 'staff', 'manager' => '71', 's_manager' => '0', 'c_user' => '61'],
            4 => ['sn' => '7856', 'm_sn' => '40', 'd_sn' => '2304', 'r_type' => 'staff', 'manager' => '75', 's_manager' => '0', 'c_user' => '61']
        ];

        $ssnList1 = ArrayHelper::gather($data, array('manager', 's_manager', 'c_user'), 1);
        $ssnList2 = ArrayHelper::gather($data, array('manager' => array('manager'), 'other' => array('s_manager', 'c_user')), 1);

        $this->assertEquals($ssnList1, [1 => '1', 506 => '506', 61 => '61', 0 => '0', 118 => '118', 71 => '71', 75 => '75']);
        $this->assertEquals($ssnList2, [
            'manager' => [1 => '1', 61 => '61', 71 => '71', 75 => '75'],
            'other' => [1 => '1', 506 => '506', 0 => '0', 118 => '118', 61 => '61']
        ]);

        /**
         * Two Layer
         */
        $data = [
            [
                0 => ['sn' => '1785', 'm_sn' => '40', 'd_sn' => '751', 'r_type' => 'staff', 'manager' => '1', 's_manager' => '1', 'c_user' => '506'],
                1 => ['sn' => '1371', 'm_sn' => '40', 'd_sn' => '583', 'r_type' => 'staff', 'manager' => '61', 's_manager' => '0', 'c_user' => '118'],
                2 => ['sn' => '1373', 'm_sn' => '40', 'd_sn' => '584', 'r_type' => 'staff', 'manager' => '61', 's_manager' => '0', 'c_user' => '118'],
                3 => ['sn' => '7855', 'm_sn' => '40', 'd_sn' => '2303', 'r_type' => 'staff', 'manager' => '71', 's_manager' => '0', 'c_user' => '61'],
                4 => ['sn' => '7856', 'm_sn' => '40', 'd_sn' => '2304', 'r_type' => 'staff', 'manager' => '75', 's_manager' => '0', 'c_user' => '61']
            ]
        ];

        $ssnList1 = ArrayHelper::gather($data, array('manager', 's_manager', 'c_user'), 2);
        $ssnList2 = ArrayHelper::gather($data, array('manager' => array('manager'), 'other' => array('s_manager', 'c_user')), 2);

        $this->assertEquals($ssnList1, [1 => '1', 506 => '506', 61 => '61', 0 => '0', 118 => '118', 71 => '71', 75 => '75']);
        $this->assertEquals($ssnList2, [
            'manager' => [1 => '1', 61 => '61', 71 => '71', 75 => '75'],
            'other' => [1 => '1', 506 => '506', 0 => '0', 118 => '118', 61 => '61']
        ]);
    }

    /**
     * Test diffRecursive()
     */
    public function testDiffRecursive()
    {
        /**
         * Test with Data Type
         */
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

        $expected = [
            1 => ['u_name' => 'name2'],
            2 => ['u_sn' => NULL, 'u_name' => 'name3'],
            3 => ['c_sn' => '0'],
            4 => ['c_sn' => '1'],
            5 => ['c_sn' => 0],
            6 => ['c_sn' => 1],
            7 => ['0' => 'a110'],
            8 => ['1' => 'a110'],
            9 => [0 => 'a110'],
            10 => [1 => 'a110'],
            11 => ['c_sn' => 110],
        ];
        $arrayDiff = ArrayHelper::diffRecursive($data1, $data2);
        $this->assertEquals($arrayDiff, $expected);
    }

    /**
     * Test sortRecursive()
     */
    public function testSortRecursive()
    {
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

        $expected1 = [
            'b1' => [
                0 => ['c_sn' => 'a110', 'u_name' => 'name1', 'u_no' => 'a001', 'u_sn' => 'b1']
            ],
            'b2' => [
                0 => ['c_sn' => 'a110', 'u_name' => 'name2', 'u_no' => 'b012', 'u_sn' => 'b2'],
                1 => ['c_sn' => 'a110', 'u_name' => 'user name 3', 'u_no' => 'b012', 'u_sn' => 'b2']
            ]
        ];

        $expected2 = [
            'b2' => [
                1 => ['u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'user name 3', 'c_sn' => 'a110'],
                0 => ['u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2', 'c_sn' => 'a110']
            ],
            'b1' => [
                0 => ['u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1', 'c_sn' => 'a110']
            ]
        ];

        /**
         * Test: ksort
         */
        ArrayHelper::sortRecursive($data1, 'ksort');
        $this->assertEquals($data1, $expected1);

        /**
         * Test: krsort
         */
        ArrayHelper::sortRecursive($data2, 'krsort');
        $this->assertEquals($data2, $expected2);
    }

    /**
     * Test filterKey()
     */
    public function testFilterKey()
    {
        $array = ['sn' => '1785', 'm_sn' => '40', 'd_sn' => '751', 'r_type' => 'staff', 'manager' => '1', 's_manager' => '1', 'c_user' => '506'];
        $expected1 = ['sn' => '1785', 'd_sn' => '751', 'r_type' => 'staff', 'manager' => '1', 'nooooooooo' => ''];
        $expected2 = ['sn' => '1785', 'd_sn' => '751', 'r_type' => 'staff', 'manager' => '1'];

        // fill key
        $nArray1 = ArrayHelper::filterKey($array, ['sn', 'd_sn', 'r_type', 'manager', 'nooooooooo']);
        $nArray2 = ArrayHelper::filterKey($array, 'sn,d_sn, r_type, manager, nooooooooo');

        // No fill key
        $nArray3 = ArrayHelper::filterKey($array, ['sn', 'd_sn', 'r_type', 'manager', 'nooooooooo'], false);
        $nArray4 = ArrayHelper::filterKey($array, 'sn,d_sn, r_type, manager, nooooooooo', false);

        $this->assertEquals($nArray1, $expected1);
        $this->assertEquals($nArray2, $expected1);
        $this->assertEquals($nArray3, $expected2);
        $this->assertEquals($nArray4, $expected2);
    }

    /**
     * Test setSortOut(), getSortOut()
     */
    public function testSortout()
    {
        /**
         * Test set false
         */
        $res = ArrayHelper::setSortOut(false)->getSortOut();
        $this->assertEquals(false, $res);

        /**
         * Test set true
         */
        $res = ArrayHelper::setSortOut(true)->getSortOut();
        $this->assertEquals(true, $res);
    }
}
