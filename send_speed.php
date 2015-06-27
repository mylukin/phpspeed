<?php
/**
 *  send_speed.php
 *
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2014-11-29 22:45
 */

if (function_exists('xhprof_enable')) {
    // set app begin time
    define('XHPROF_BEGIN_TIME', microtime(true));

    $PERCENT = 100;
    $TIMEOUT = 1;
    $MYSPEED = 'http://one-786.a.ajkdns.com/receive_speed.php';
    if (isset($_REQUEST['_ajkspeed'])) {
        $_COOKIE['_ajkspeed'] = $_REQUEST['_ajkspeed'];
        // 保存cookie
        setcookie('_ajkspeed', $_COOKIE['_ajkspeed'], 0, '/', $_SERVER['HTTP_HOST']);
    }
    $XHPROF = isset($_COOKIE['_ajkspeed']) ? $_COOKIE['_ajkspeed'] : false;

    if ((empty($XHPROF) == false) || (mt_rand(1, $PERCENT) == 1)) {
        // 不收集内置函数
        xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_MEMORY);

        function xhprof_send_profile($XHPROF, $url, $timeout) {
            $xhprof_data = xhprof_disable();
            $execTime = microtime(true) - XHPROF_BEGIN_TIME;
            // 小于200ms，不发送
            if ((empty($XHPROF) == true) && ($execTime < 0.2)) {
                return false;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'uri' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD'],
                'data' => serialize($xhprof_data)
            )));
            curl_exec($ch);
            curl_close($ch);
        }

        register_shutdown_function('xhprof_send_profile', $XHPROF, $MYSPEED, $TIMEOUT);
    }
}