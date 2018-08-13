<?php

namespace Slim;

class Settings extends Collection
{
    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        $array = $this->data;

        foreach (explode('.', $key) as $sub) {
            if (isset($array[$sub])) {
                $array = $array[$sub];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $array = &$this->data;

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * @inheritdoc
     */
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
}
