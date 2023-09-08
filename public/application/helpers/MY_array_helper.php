<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * These are the array helper functions that are used in the ShareBOX application.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

if (defined('ARRAY_HELPERS')) {
    die();
}
define('ARRAY_HELPERS', 1);

// Return true if all elements of $array are true (or if $array is empty).
// Conceptually, this reduces an array with `and`.
// $predicate is an optional predicate for indicating a true value.
function array_all($array, $predicate=null)
{
    if ($predicate) {
        return (count($array) == count(array_filter($array, $predicate)));
    }
    return (count($array) == count(array_filter($array)));
}

// Return true if any elements of $array are true (or if $array is empty).
// Conceptually, this reduces an array with `or`.
// $predicate is an optional predicate for indicating a true value.
function array_any($array, $predicate=null)
{
    if ($predicate) {
        return (empty($array) || count(array_filter($array, $predicate)) > 0);
    }
    return (empty($array) || count(array_filter($array)) > 0);
}

// The only way I know to determine whether or not an array is numerically
// indexed is to check each of its keys.
function array_is_numeric($array = array())
{
    return array_all(array_keys($array), 'is_int');
}


/**
 * Partition an array into 2 arrays: the first containing elements that satisfy
 * the predicate, the second containing those that do not.
 *
 * @param callback $predicate function to call
 * @param array $array
 * @return array a pair (array) of arrays
 */
function array_partition($predicate, $array = array())
{
    $a = array(array(), array());
    foreach ($array as $value) {
        if (call_user_func($predicate, $value)) {
            $a[0][] = $value;
        } else {
            $a[1][] = $value;
        }
    }
    return $a;
}


/**
 * Takes an array and extracts specified keys only
 *
 * @param $keys  array  the keys to keep from $array
 * @param $array array  the array to extract data from
 * @return array
 */
function array_extract_keys($keys, $array = array())
{
    $res = array();
    foreach ($keys as $key) {
        if (isset($array[$key])) {
            $res[$key] = $array[$key];
        }
    }
    return $res;
}


/**
 * Take an array of associative arrays, and return an array of the key value from each array.
 *
 * @param mixed $key The key whose value should be returned for each array.
 * @param array $arrays The arrays to extract values from.
 * @return array
 */
function arrays_extract_key($key, $arrays = array())
{
    if (count($arrays)) {
        return array_map(function ($array) use ($key) {
            return $array[$key];
        }, $arrays);
    }
    return array();
}

function arrays_extract_keys($keys, $arrays = array())
{
    if (count($arrays)) {
        return array_map(function ($array) use ($keys) {
            return array_extract_keys($keys, $array);
        }, $arrays);
    }
    return array();
}

function arrays_remove_keys($keys, $array = null)
{
    if (is_array($array)) {
        // Remove these keys from all the array arrays
        $remove = array_flip($keys);
        if (is_array($array[0])) {
            foreach ($array as $key => $row) {
                if (is_array($row)) {
                    $array[$key] = array_diff_key($row, $remove);
                }
            }
        } else {
            $array = array_diff_key($array, $remove);
        }
    }
    return $array;
}

function arrays_add_key($key, $value, $arrays = array())
{
    if (count($arrays)) {
        if (is_callable($value)) {
            foreach ($arrays as $id => $array) {
                $array[$key] = call_user_func($value, $array);
                $arrays[$id] = $array;
            }
        } else {
            foreach ($arrays as $id => $array) {
                $array[$key] = $value;
                $arrays[$id] = $array;
            }
        }
        return $arrays;
    }
    return array();
}


/**
 * Take an array of associative arrays, and set the index of each array to the
 * value within that array for the given key.
 *
 * Note: in the case if duplicate key values in the result, the final duplicate will be
 * the one to take precedence.
 *
 * @param mixed $key The key to use on each array to find the value to index it under.
 * @param array $arrays The source arrays to transform.
 * @return array
 */
function arrays_index_by_key($key, $arrays = array())
{
    $ret = array();
    foreach ($arrays as $array) {
        $ret[$array[$key]] = $array;
    }
    return $ret;
}

function arrays_extract_key_value($key, $value, $arrays = array())
{
    if (count($arrays)) {
        return array_combine(arrays_extract_key($key, $arrays), arrays_extract_key($value, $arrays));
    }
    return array();
}
