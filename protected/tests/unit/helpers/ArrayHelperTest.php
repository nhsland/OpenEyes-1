<?php

class ArrayHelperTest extends CTestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function test_array_values_multi_flat()
    {
        $flat_arr = array(7,5,6,8,4,3);
        $expected_arr = array(7,5,6,8,4,3);

        $this->assertSame(ArrayHelper::array_values_multi($flat_arr), $expected_arr
            , 'Flat array should have returned with the same values, it did not');
    }

    public function test_array_values_multi_2_level()
    {
        $flat_arr1 = array(7,5,6,8,4,3);
        $flat_arr2 = array(1,2,11,3);
        $leveled_arr = array();
        $leveled_arr[] = $flat_arr1;
        $leveled_arr[] = $flat_arr2;

        $expected_arr = array(7,5,6,8,4,3, 1,2,11,3);

        $this->assertSame(ArrayHelper::array_values_multi($leveled_arr), $expected_arr,
            'Array constructed of 2 arrays did not produce expected result');
    }

    public function test_array_values_multi_mixed()
    {
        $leveled_arr = array(7,5,6,8,4,3, array(1,2,11,3));
        $expected_arr = array(7,5,6,8,4,3, 1,2,11,3);
        $this->assertSame(ArrayHelper::array_values_multi($leveled_arr), $expected_arr,
            'Array constructed of an array and individual elements did not produce expected result');
    }
}