<?php
class Loader
{
    /* 路径映射 */
    public static $vendorMap = array(
        'yyc' => __DIR__ . DIRECTORY_SEPARATOR . 'src',
    );

    /**
     * 自动加载器
     * @param $class
     */
    public static function autoload($class)
    {
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }

    /**
     * 解析文件路径
     * @param $class
     * @return string
     */
    private static function findFile($class)
    {
        $vendor = substr($class, 0, strpos($class, '\\'));
        $vendorDir = self::$vendorMap[$vendor];
        $filePath = substr($class, strlen($vendor)) . '.php';
        return strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR);
    }

    /**
     * 引入文件
     * @param $file
     */
    private static function includeFile($file)
    {
        if (is_file($file)) {
            include $file;
        }
    }
}
spl_autoload_register('Loader::autoload');
