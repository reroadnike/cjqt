<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 16-4-11
 * Time: 下午8:44
 */
class LogsUtil extends WeUtility
{

    public static function logging($level = 'info', $message = '', $path = '') {
        $filename = IA_ROOT . '/data/logs/' . (!empty($path) ? $path . '/' : '') . date('Ymd') . '.log';
        load()->func('file');
        mkdirs(dirname($filename));
        $content = date('Y-m-d H:i:s') . " {$level} :\n------------\n";
        if(is_string($message) && !in_array($message, array('post', 'get'))) {
            $content .= "String:\n{$message}\n";
        }
        if(is_array($message)) {
            $content .= "Array:\n";
            foreach($message as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        if($message === 'get') {
            $content .= "GET:\n";
            foreach($_GET as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        if($message === 'post') {
            $content .= "POST:\n";
            foreach($_POST as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        $content .= "\n";

        $fp = fopen($filename, 'a+');
        fwrite($fp, $content);
        fclose($fp);
    }

}