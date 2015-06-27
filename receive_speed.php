<?php
/**
 *  receive_speed.php
 *
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2014-11-29 20:35
 */

//$input = file_get_contents('php://input');

$method = isset($_POST['method']) ? $_POST['method'] : 'GET';
$uri = isset($_POST['uri']) ? $_POST['uri'] : '/index';
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}
$xhprof_data = empty($_POST['data']) ? null : unserialize($_POST['data']);
$GLOBALS['XHPROF_LIB_ROOT'] = dirname(__FILE__) . '/lib';

include_once $GLOBALS['XHPROF_LIB_ROOT'] . "/utils/xhprof_lib.php";
include_once $GLOBALS['XHPROF_LIB_ROOT'] . "/utils/xhprof_runs.php";

//保存统计数据，生成统计ID和source名称
$log_path = __DIR__ . '/logs/' . date('Ymd') . '/';
if (!is_dir($log_path)) {
    xhprof_mkdirs($log_path);
}
$xhprof_runs = new XHProfRuns_Default($log_path);
$run_id = $xhprof_runs->save_run($xhprof_data, sprintf('my_%s_%s', $method, preg_replace('@[^a-z\d]+@i', '_', trim($uri, '/')))); //source名称是xhprof_foo

$result = array(
    'status' => 'ok',
    'runid' => $run_id,
    'path' => sprintf('my_%s_%s', $method, preg_replace('@[^a-z\d]+@i', '_', trim($uri, '/')))
);

echo json_encode($result);
