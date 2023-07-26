<?php

namespace tingyu\YiConf;

class Config {

    /**
     * @var class Config
     */
    private static $instance = null;

    private $data = [];

    private function __construct() {
    }

    private function __clone() {
    }

    /**
     * @return class|Config|null
     */
    private static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    // 传入路径名称
    private function setConfigDir($dir) {
        if (!is_dir($dir)) {
            throw new \Exception("配置目录不存在");
        }

        $this->scanConfigDir($dir);
    }

    private function scanConfigDir($dir) {

        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                $fileName = basename($file, '.php');

                $filePath = $dir . DIRECTORY_SEPARATOR . $file;


                if (is_file($filePath)) {
                    $this->data[$fileName] = include $filePath;
                }
            }
            closedir($handle);
        }
    }

    private function setConfigFile($filePath) {
        if (!is_file($filePath)) {
            throw new \Exception("配置文件不存在");
        }

        $filename = basename($filePath, '.php');

        $this->read($filename, $filePath);
    }

    private function read($fileName, $filePath) {
        $this->data[$fileName] = include $filePath;
    }

    private function getConfig($key = null)
    {
        if (empty($key)) {
            return $this->data;
        }

        list($file, $field) = explode('.', $key);

        if (array_key_exists($file, $this->data)) {
            if (array_key_exists($field, $this->data[$file])) {
                return $this->data[$file][$field];
            }
        }

        return null;
    }

    public static function ConfigFile($filePath) {
        if (is_null(self::$instance)) {
            self::getInstance();
        }
        return self::$instance->setConfigFile($filePath);
    }

    public static function ConfigDir($dirPath) {
        if (is_null(self::$instance)) {
            self::getInstance();
        }
        return self::$instance->setConfigDir($dirPath);
    }

    public static function Get($key = null) {
        if (is_null(self::$instance)) {
            self::getInstance();
        }
        return self::$instance->getConfig($key);
    }

}