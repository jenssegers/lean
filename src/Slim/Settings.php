<?php

namespace Slim;

use RuntimeException;

class Settings extends Collection
{
    public function get($key, $default = null)
    {
        $array = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (!isset($array[$segment])) {
                return $default;
            }
            if (!is_array($array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    public function set($key, $value)
    {
        $array = &$this->data;
        $keyPath = explode('.', $key);
        $endKey = array_pop($keyPath);

        foreach ($keyPath as $segment) {
            if (!isset($array[$segment])) {
                $array[$segment] = [];
            }
            if (!is_array($array[$segment])) {
                throw new RuntimeException("The value at $segment of $key is not an array");
            }

            $array = &$array[$segment];
        }

        $array[$endKey] = $value;
    }

    public function has($key)
    {
        $array = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }

        return true;
    }

    public function remove($key)
    {
        $array = &$this->data;
        $keyPath = explode('.', $key);
        $endKey = array_pop($keyPath);

        foreach ($keyPath as $segment) {
            if (!isset($array[$segment])) {
                return;
            }

            $array = &$array[$segment];
        }

        unset($array[$endKey]);
    }
}
