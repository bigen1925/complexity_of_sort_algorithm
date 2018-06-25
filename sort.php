<?php
//////////////
// Usage
// Call from command line
// $ php sort.php <number_of_data> <max_range_of_data> <kind_of_sort_method>
// Sample: $ php sort.php 1000 100 bubble
//////////////

// データの数
$length_array = (int)$argv[1] ?: 1000;
// データのとりうる値の上限
$max_range = (int)$argv[2] ?: 100;
// ソートアルゴリズム
$sort_method = $argv[3] ?: "all";

// 計測
main($length_array, $max_range, $sort_method);

function main($length_array, $max_range, $sort_method)
{
    // 1~max_rangeまでの数字から成る、ランダムな順序の数列を生成
    $array = array();
    for ($i=0; $i < $length_array; $i++) {
        $array[] = rand(1, $max_range);
    }

    $initial_memory_usage = memory_get_usage();

    if ($sort_method === "all" || $sort_method === "bubble") {
        $time_start = microtime(true);
        bubbleSort($array);
        $time = microtime(true) - $time_start;
        echo "bubbleSort:: {$time}s\n";
    }

    if ($sort_method === "all" || $sort_method === "buckets") {
        $time_start = microtime(true);
        bucketsSort($array, $max_range);
        $time = microtime(true) - $time_start;
        echo "bucketsSort:: {$time}s\n";
    }

    if ($sort_method === "all" || $sort_method === "merge") {
        $time_start = microtime(true);
        mergeSort($array);
        $time = microtime(true) - $time_start;
        echo "mergeSort:: {$time}s\n";
    }

    $used_memory = memory_get_peak_usage() - $initial_memory_usage;
    echo "used_memory:: {$used_memory}\n";
}

// バブルソート
// @param array @array ソートしたい自然数配列
// @return array ソート済みの配列
function bubbleSort(array $array)
{
    $length = count($array);
    for ($i=0; $i < $length; $i++) {
        for ($j=0; $j < $length - $i - 1; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
    return $array;
}

// バケツソート
// @param array $array ソートしたい自然数配列
// @param integer $max_range データのとりうる最大値
// @return array ソート済みの配列
function bucketsSort(array $array, $max_range)
{
    $length = count($array);
    $buckets = array_fill(1, $max_range, 0);
    $sorted_array = array();

    foreach ($array as $value) {
        $buckets[$value]++;
    }

    foreach ($buckets as $value => $count) {
        for ($i = 0; $i < $count; $i++) {
            $sorted_array[] = $value;
        }
    }

    return $sorted_array;
}

// マージソート
// @param array $array ソートしたい自然数配列
// @return array ソート済み配列
function mergeSort(array $array)
{
    $length = count($array);
    $sorted_array = array();

    if ($length > 1) {
        $mid_index = floor(($length + 0.5) / 2);
        $left_array = array_slice($array, 0, $mid_index);
        $right_array = array_slice($array, $mid_index);

        $left_array = mergeSort($left_array);
        $right_array = mergeSort($right_array);
        while (count($left_array) || count($right_array)) {
            if (count($left_array) == 0) {
                $sorted_array[] = array_shift($right_array);
            } elseif (count($right_array) == 0) {
                $sorted_array[] = array_shift($left_array);
            } elseif ($left_array[0] > $right_array[0]) {
                $sorted_array[] = array_shift($right_array);
            } else {
                $sorted_array[] = array_shift($left_array);
            }
        }
    } else {
        $sorted_array = $array;
    }

    return $sorted_array;
}
