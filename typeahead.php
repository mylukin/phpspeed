<?php
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

/**
 * AJAX endpoint for XHProf function name typeahead.
 *
 * @author(s)  Kannan Muthukkaruppan
 *             Changhao Jiang
 */

// by default assume that xhprof_html & xhprof_lib directories
// are at the same level.
$GLOBALS['XHPROF_LIB_ROOT'] = dirname(__FILE__) . '/lib';

require_once $GLOBALS['XHPROF_LIB_ROOT'] . '/display/xhprof.php';

$datei = isset($_GET['datei']) ? $_GET['datei'] : date('Ymd');

$log_path = __DIR__ . '/logs/' . $datei . '/';
if (!is_dir($log_path)) {
    xhprof_mkdirs($log_path);
}
$xhprof_runs_impl = new XHProfRuns_Default($log_path);

require_once $GLOBALS['XHPROF_LIB_ROOT'] . '/display/typeahead_common.php';
