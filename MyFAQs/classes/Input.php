<?php
/**
 * Created by PhpStorm.
 * User: huimingdeng
 * Date: 2019/3/12
 * Time: 15:04
 */

namespace MyFAQs\Classes;


class Input
{
    /**
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = '')
    {
        if (isset($_GET[$name]))
            return sanitize_text_field($_GET[$name]);
        return $default;
    }


    /**
     * @param $name
     * @param int $default
     * @return int
     */
    public function get_int($name, $default = 0)
    {
        $get = $this->get($name, $default);
        return intval($get);
    }

    /**
     * @param $name
     * @return bool
     */
    public function get_exists($name)
    {
        if (isset($_GET[$name]))
            return TRUE;
        return FALSE;
    }


    /**
     * @param $name
     * @param string $default
     * @return array|string
     */
    public function post($name, $default = '')
    {
        if (isset($_POST[$name])) {
            if (is_array($_POST[$name])) {
                $data = array_map('stripslashes', $_POST[$name]);
                $data = array_map('strip_tags', $data);
                return $data;
            }
            return strip_tags(stripslashes($_POST[$name]));
        }
        return $default;
    }


    /**
     * @param $name
     * @param int $default
     * @return int
     */
    public function post_int($name, $default = 0)
    {
        $post = $this->post($name, $default);
        return intval($post);
    }


    /**
     * @param $name
     * @param string $default
     * @return string
     */
    public function post_raw($name, $default = '')
    {
        if (isset($_POST[$name]))
            return $_POST[$name];
        return $default;
    }

    /**
     * @param $name
     * @return bool
     */
    public function post_exists($name)
    {
        if (isset($_POST[$name]))
            return TRUE;
        return FALSE;
    }
}