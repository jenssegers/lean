<?php

namespace Slim;

use RuntimeException;

class Settings extends Collection
{
    public function get($key, $default = null)
    {
        $array = $this->data;

        foreach (explode('.', $key) as $sub) {
            if (!isset($array[$sub])) {
                return $default;
            }
            if (!is_array($array)) {
                return $default;
            }

            $array = $array[$sub];
        }

        return $array;
    }

    public function set($key, $value)
    {
        $array = &$this->data;
        $keyPath = explode('.', $key);
        $endKey = array_pop($keyPath);

        foreach ($keyPath as $sub) {
            if (!isset($array[$sub])) {
                $array[$sub] = [];
            }
            if (!is_array($array[$sub])) {
                throw new RuntimeException("The value at $sub of $key is not an array");
            }

            $array = &$array[$sub];
        }

        $array[$endKey] = $value;
    }

    public function has($key)
    {
        if (array_key_exists($key, $this->data)) {
            return true;
        }

        $subKeyArray = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $subKeyArray)) {
                $subKeyArray = $subKeyArray[$segment];
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

        foreach ($keyPath as $sub) {
            if (!isset($array[$sub])) {
                return;
            }

            $array = &$array[$sub];
        }

        unset($array[$endKey]);
    }
}
