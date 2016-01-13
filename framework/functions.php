<?php

//蜘蛛
function checkrobot($useragent = '') {
    static $kw_spiders = array('bot', 'crawl', 'spider', 'slurp', 'sohu-search', 'lycos', 'robozilla');
    static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

    $useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
    if (strpos($useragent, 'http://') === false && astrpos($useragent, $kw_browsers))
        return false;
    if (astrpos($useragent, $kw_spiders))
        return true;
    return false;
}

//strpos 支持数组
function astrpos($string, &$arr) {
    if (empty($string))
        return false;
    foreach ((array) $arr as $v) {
        if (strpos($string, $v) !== false) {
            return $v;
        }
    }
    return false;
}

//转义html实体 支持数组
function bhtmlspecialchars($string) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = bhtmlspecialchars($val);
        }
    } else {
        $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
        if (strpos($string, '&amp;#') !== false) {
            $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string); //类似&#38889的unicode
        }
    }
    return $string;
}

//特殊字符 去除反斜杠
function astripslashes($string) {
    if (empty($string))
        return $string;
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = astripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

//特殊字符 增加反斜杠
function aaddslashes($string) {
    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = aaddslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

//随机数 长度 ，纯数字
function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

//设置cookie
function bsetcookie($var, $value = '', $life = 0) {
    global $_B;
    $config = $_B['config']['cookie'];
    $life = $life > 0 ? TIMESTAMP + $life : ($life < 0 ? TIMESTAMP - 600 : 0);
    $path = $config['cookiepath'];
    $var = $config['cookiepre'] . $var;
    setcookie($var, $value, $life, $path, $config['cookiedomain']);
}

function getcookie($var) {
    global $_B;
    $config = $_B['config']['cookie'];
    $var = $config['cookiepre'] . $var;
    return $_COOKIE[$var];
}


//验证用户名
function check_username($username) {
    $guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
    $len = strlen($username);
    if ($len > 15 || $len < 3 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
        return false;
    } else {
        return true;
    }
}

//验证邮箱
function check_email($email) {
    return strlen($email) > 6 && strlen($email) <= 32 && preg_match("/^([a-z0-9\-_.+]+)@([a-z0-9\-]+[.][a-z0-9\-.]+)$/", $email);
}

//加密 解密
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    global $_B;
    $ckey_length = 4;
    $key = md5($key != '' ? $key : $_B['config']['security']['authkey']);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

//获取跳转来源
function getReferer() {
    global $_B;
    $referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $_B['siteurl'];
    $_B['referer'] = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
}

//获取用户信息
function getUser($uid) {
    static $users = array();
    if (empty($users[$uid])) {
        $users[$uid] = J::t('users')->find_by_pk($uid);
    }
    return $users[$uid];
}

//php 跳转 $replace【指示该报头是否替换之前的报头，或添加第二个报头】  http_response_code【把 HTTP 响应代码强制为指定的值。（PHP 4 以及更高版本可用）】
function bheader($string, $replace = true, $http_response_code = 0) {
    $islocation = substr(strtolower(trim($string)), 0, 8) == 'location';
    $string = str_replace(array("\r", "\n"), array('', ''), $string);
    if (empty($http_response_code) || PHP_VERSION < '4.3') {
        @header($string, $replace);
    } else {
        @header($string, $replace, $http_response_code);
    }
    if ($islocation) {
        exit();
    }
}
/**
 * 格式化时间
 * @param string $time 时间戳
 * @param int $ago 是否显示为 几秒/几分/几小时...的相对时间
 * @param string $format  显示的日期格式
 * @return string
 */
function formatTime($time, $ago = '', $format = '') {
    if ($ago) {
        $dur = TIMESTAMP - $time;
        if ($dur < 0) {
            return date('Y-m-d H:i:s', $time);
        } else {
            if ($dur < 60) {
                return $dur . ' 秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . ' 分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . ' 小时前';
                    } else {
                        if ($dur < 604800) {
                            $day = floor($dur / 86400);
                            if ($day == 1) {
                                return '昨天 ' . date('H:i', $time);
                            } elseif ($day == 2) {
                                return '前天 ' . date('H:i', $time);
                            } else {
                                return $day . ' 天前';
                            }
                        } else {
                            return date('n-j H:i', $time);
                        }
                    }
                }
            }
        }
    } else {
        return date($format ? $format : ('Y-m-d H:i:s'), $time);
    }
}
/**
 * 简单的分页
 * @param int $count    数据记录总数
 * @param int  $page    当前页数
 * @param int $limit    每页显示条数
 * @param string $link  分页链接
 * @param int $showpage  显示多少页
 * @return string 返回组织好的 html
 */
function simplePage($count, $page, $limit, $link, $showpage = 10) {
    $html = '';
    $middlepage = ceil($showpage / 2);
    $tmp = '?';
    if (strpos($link, '?')) {
        $tmp = '&';
    }
    $countpage = ceil($count / $limit);
    if ($page <= 1) {
        if ($page == $countpage) {
            return '';
        }
        $head = '<li><a href="#">上一页</a></li>';
    } else {
        $head = '<li><a href="' . $link . $tmp . 'page=' . ($page - 1) . '">上一页</a></li>';
    }
    if ($page >= $countpage) {
        $end = '<li><a href="#">下一页</a></li>';
    } else {
        $end = '<li><a href="' . $link . $tmp . 'page=' . ($page + 1) . '">下一页</a></li>';
    }
    $predot = $enddot = '';
    if ($page - $middlepage <= 1) {
        $from = 1;
    } else {
        $predot = '...';
        $from = $page - $middlepage;
    }
    if (($from + $showpage) >= $countpage) {
        $to = $countpage;
        $from = $to - $showpage;
        if ($from <= 0) {
            $from = 1;
        }
    } else {
        $enddot = '...';
        $to = $from + $showpage;
    }
    if ($from == 1) {
        $predot = '';
    }
    if ($from == $countpage) {
        $enddot = '';
    }
    $html.='<nav><ul class="pagination" style="float:right;">';
    $html.=$head;
    if ($predot) {
        $html.='<li><a href="#">' . $predot . '</a></li>';
    }
    for ($i = $from; $i <= $to; $i++) {
        if ($i == $page) {
            $html.='<li class="active"><a href="#">' . $i . '</a></li>';
        } else {
            $html.='<li><a href="' . $link . $tmp . 'page=' . $i . '">' . $i . '</a></li>';
        }
    }
    if ($enddot) {
        $html.='<li><a href="#">' . $enddot . '</a></li>';
    }
    $html.=$end;
    $html.='</ul></nav>';
    return $html;
}

/**
 * 二维数组排序，指定key值
 * @param array $multi_array    要排序的数组
 * @param string $sort_key      按照哪一个key值排序
 * @param const $sort           排序规则 SORT_ASC，SORT_DESC
 * @return array                排序后的数组
 */
function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC) {
    if (is_array($multi_array)) {
        foreach ($multi_array as $row_array) {
            if (is_array($row_array)) {
                $key_array[] = $row_array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_array, $sort, $multi_array);
    return $multi_array;
}

/**
 * 截取字符串
 * @param string $string    带截取字符串
 * @param int $length       长度（一个中文也是一个字符）
 * @param string $dot       超过截取长度显示的字符串
 * @return string           截取后的字符串
 */
function cutstr($string, $length, $dot = ' ...') {
    if (strlen($string) <= $length) {
        return $string;
    }
    $pre = chr(1);
    $end = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
    $strcut = '';
    $n = $tn = $noc = 0;
    while ($n < strlen($string)) {
        $t = ord($string[$n]);
        if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1;
            $n++;
            $noc++;
        } elseif (194 <= $t && $t <= 223) {
            $tn = 2;
            $n += 2;
            $noc += 2;
        } elseif (224 <= $t && $t <= 239) {
            $tn = 3;
            $n += 3;
            $noc += 2;
        } elseif (240 <= $t && $t <= 247) {
            $tn = 4;
            $n += 4;
            $noc += 2;
        } elseif (248 <= $t && $t <= 251) {
            $tn = 5;
            $n += 5;
            $noc += 2;
        } elseif ($t == 252 || $t == 253) {
            $tn = 6;
            $n += 6;
            $noc += 2;
        } else {
            $n++;
        }
        if ($noc >= $length) {
            break;
        }
    }
    if ($noc > $length) {
        $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    $pos = strrpos($strcut, chr(1));
    if ($pos !== false) {
        $strcut = substr($strcut, 0, $pos);
    }
    return $strcut . $dot;
}

//引入js和css,压缩和未压缩
//$page 对应页面/或标识
function includeJSCSS($page = 'all') {

    $asset = array();
    //-----------头部引入 all
    //js jquery2.1
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/jquery.min.js', //测试
        'online' => JS_DIR . '/jquery.min.js', //线上
    );
    //jq 1.9 之后废弃了一些api,导致一些老的插件不能用,此文件就是解决这个问题的
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/jquery-migrate-1.2.1.min.js',
        'online' => JS_DIR . '/jquery-migrate-1.2.1.min.js',
    );
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/bootstrap.min.js',
        'online' => JS_DIR . '/bootstrap.min.js',
    );
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/lazyload.js',
        'online' => JS_DIR . '/lazyload.min.js',
    );
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/autocomplete.js',
        'online' => JS_DIR . '/autocomplete.min.js',
    );
    $asset['all']['js'][] = array(
        'debug' => JS_DIR . '/common.js',
        'online' => JS_DIR . '/common.min.js',
    );
    //弹出层插件
    $asset['all']['js'][] = array(
        'debug' => STATIC_DIR.'/layer/layer.js',
        'online' => STATIC_DIR.'/layer/layer.js',
    );
    //css 
    $asset['all']['css'][] = array(
        'debug' => CSS_DIR . '/bootstrap.css',
        'online' => CSS_DIR . '/bootstrap.min.css',
    );
    $asset['all']['css'][] = array(
        'debug' => CSS_DIR . '/common.css',
        'online' => CSS_DIR . '/common.min.css',
    );

    //------添加文章编辑器 articleNew
    //js
    $asset['articleNew']['js'][] = array(
        'debug' => JS_DIR . '/summernote.js',
        'online' => JS_DIR . '/summernote.min.js',
    );
    $asset['articleNew']['js'][] = array(
        'debug' => JS_DIR . '/summernote-zh-CN.js',
        'online' => JS_DIR . '/summernote-zh-CN.min.js',
    );
    $asset['articleNew']['js'][] = array(
        'debug' => JS_DIR . '/article-new.js',
        'online' => JS_DIR . '/article-new.min.js',
    );
    //css
    $asset['articleNew']['css'][] = array(
        'debug' => CSS_DIR . '/font-awesome.min.css',
        'online' => CSS_DIR . '/font-awesome.min.css',
    );
    $asset['articleNew']['css'][] = array(
        'debug' => CSS_DIR . '/summernote.min.css',
        'online' => CSS_DIR . '/summernote.min.css',
    );
    //-------简单(ubb)编辑器 editor
    $asset['editor']['js'][] = array(
        'debug' => JS_DIR . '/editor.js',
        'online' => JS_DIR . '/editor.min.js',
    );
    //--------右侧电子时钟
    //js
    $asset['clock']['js'][] = array(
        'debug' => JS_DIR . '/clock/moment.min.js',
        'online' => JS_DIR . '/clock/moment.min.js',
    );
    $asset['clock']['js'][] = array(
        'debug' => JS_DIR . '/clock/script.js',
        'online' => JS_DIR . '/clock/script.min.js',
    );
    $asset['clock']['js'][] = array(
        'debug' => JS_DIR . '/clock/html5.js',
        'online' => JS_DIR . '/clock/html5.min.js',
    );
    //css
    $asset['clock']['css'][] = array(
        'debug' => CSS_DIR . '/clock.css',
        'online' => CSS_DIR . '/clock.min.css',
    );
    //--------代码高亮
    //js
    $asset['code']['js'][] = array(
        'debug' => JS_DIR . '/code/shbrush.js',
        'online' => JS_DIR . '/code/shbrush.js',
    );
    //css
    $asset['code']['css'][] = array(
        'debug' => CSS_DIR . '/code/shcore.css',
        'online' => CSS_DIR . '/code/shcore.css',
    );
    $asset['code']['css'][] = array(
        'debug' => CSS_DIR . '/code/shthemedefault.css',
        'online' => CSS_DIR . '/code/shthemedefault.css',
    );

    $tmp = $asset[$page];
    $csses = $tmp['css'];
    $jses = $tmp['js'];
    $html = '';
    foreach ($csses as $cssLinks) {
        if (DEBUG) {
            $_tmpLink = $cssLinks['debug'];
        } else {
            $_tmpLink = $cssLinks['online'];
        }
        echo '<link rel="stylesheet" type="text/css" href="' . $_tmpLink . '" />' . PHP_EOL;
    }
    foreach ($jses as $jsLinks) {
        if (DEBUG) {
            $_tmpLink = $jsLinks['debug'];
        } else {
            $_tmpLink = $jsLinks['online'];
        }
        echo '<script src="' . $_tmpLink . '" type="text/javascript"></script>' . PHP_EOL;
    }
}

/**
 * 实例化模型
 * @staticvar path $_instanceM 存放实例化后的模型类
 * @param type $name            模型名称
 * @return \path
 * @throws \Exception
 */
function Model($name = '') {
    static $_instanceM;
    if ($name) {
        $path = MODULE_NAME . '\\Model\\' . $name . 'Model';
        if(!file_exists($path.'.class.php')){
            $path = 'common\\Model\\' . $name . 'Model';
            if(!file_exists(APP_PATH.$path.'.class.php')){
                $path = 'Framework\\Model';
            }
        }
    } else {
        $path = 'Framework\\' . $name . 'Model';
    }
    if (!isset($_instanceM[$path])){
        try {   
            $_instanceM[$path] = new $path($name);
        } catch (Exception $e) {   
            throw new \Exception('无法加载模块 '.$name.'Model');
        } 
        
    }
    return $_instanceM[$path];
}

function config($key = '') {
    static $_config;
    if (!$_config) {
        $config = include(__ROOT__ . '/app/common/config/config.php');
        $_config = $config;
    }
    return $key ? $_config[$key] : $_config;
}
/**
 * 调试用，记录运行数据
 * @param    $msg       要记录的数据
 * @param    $arr       0(默认)/1,是否是数组，若是数组将此值改为1
 * @param    $ext       扩展记录数据，字符串类型，如记录 在哪个页面，那一行写入的，方便以后删除
 * @return      无返回值，到 网站根目录\log.txt 下查看记录的数据
 */
function fput($msg, $arr = 0, $ext = '') {
    $time = date('Y-m-d H:i:s', TIMESTAMP);
    $ext .= CONTROLLER_NAME . '  - ' . ACTION_NAME;
    if ($arr) {
        file_put_contents(__ROOT__ . '/log.txt', var_export($msg, true) . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents(__ROOT__ . '/log.txt', $msg . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
    }
}

function printarr($arr) {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function size_count($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

/**
 * 文件缓存
 * @param type $name    缓存名称
 * @param type $data    缓存的数据，若是读取，则为空
 * @param type $mode    读写模式
 * @return string       
 */
function fCache($name, $data = '',$mode = 'w') {
    $path = __ROOT__.CACHE_DIR.'cache/'.$name.'.txt';
    if ($data) {
        if ($fp = fopen($path, $mode)) {
            flock($fp, 2);
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        if (file_exists($path)) {
            $cache = json_decode(file_get_contents($path), true);
            if ($cache) {
                return $cache;
            } else {
                return '';
            }
        }
    }
}
/**
 * 设置/获取/销毁 session
 * @param string $name    session 名称，支持二维，格式如 var1.var2，不填则返回整个 session，若是为null，则销毁session
 * @param string $value   session 值，若是为 null ，则销毁该值
 * @return string
 */
function session($name = '', $value = '') {
    if (!isset($_SESSION)) {
        session_start();
    }
    $pre = 'jam_';
    if ($name != '') {
        if ($pre) {
            //有前缀
            $name1 = $name2 = '';
            if (strpos($name, '.')) {
                list($name1, $name2) = explode('.', $name);
            }
            if ($value != '') {
                if ($name1) {
                    return $_SESSION[$pre][$name1][$name2] = $value;
                } else {
                    return $_SESSION[$pre][$name] = $value;
                }
            } elseif (is_null($value)) {
                //销毁
                if ($name1) {
                    unset($_SESSION[$pre][$name1][$name2]);
                } else {
                    unset($_SESSION[$pre][$name]);
                }
            } else {
                //返回session值
                if ($name1) {
                    isset($_SESSION[$pre][$name1][$name2]) ? $_SESSION[$pre][$name1][$name2] : null;
                } else {
                    isset($_SESSION[$pre][$name]) ? $_SESSION[$pre][$name] : null;
                }
            }
        } else {
            //没有前缀
            $name1 = $name2 = '';
            if (strpos($name, '.')) {
                list($name1, $name2) = explode('.', $name);
            }
            if ($value != '') {
                if ($name1) {
                    return $_SESSION[$name1][$name2] = $value;
                } else {
                    return $_SESSION[$name] = $value;
                }
            } elseif (is_null($value)) {
                //销毁
                if ($name1) {
                    unset($_SESSION[$name1][$name2]);
                } else {
                    unset($_SESSION[$name]);
                }
            } else {
                //返回session值
                if ($name1) {
                    isset($_SESSION[$name1][$name2]) ? $_SESSION[$name1][$name2] : null;
                } else {
                    isset($_SESSION[$name]) ? $_SESSION[$name] : null;
                }
            }
        }
    } elseif (is_null($name)) {
        if ($pre) {
            unset($_SESSION[$pre]);
        } else {
            unset($_SESSION);
        }
    } else {
        return $pre ? $_SESSION[$pre] : $_SESSION;
    }
}
/**
 * 获取客户端ip
 * @return IP
 */
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] as $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}
/**
 * 获取站点首页
 * @return string
 */
function get_site_url() {
    $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
    if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
        $currentPath = $_SERVER['SCRIPT_NAME'];
    } else if (basename($_SERVER['PHP_SELF']) === $scriptName) {
        $currentPath = $_SERVER['PHP_SELF'];
    } else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
        $currentPath = $_SERVER['ORIG_SCRIPT_NAME'];
    } else if (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
        $currentPath = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
    } else {
        $currentPath = '';
    }
    $sitepath = substr($currentPath, 0, strrpos($currentPath, '/'));
    $res = bhtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].$sitepath.'/');
    return $res;
}
/**
 * 常用状态返回
 * @param boolean $status       状态
 * @param string $msg           提示信息
 * @param string/array $data    返回数据，任意数据类型
 * @return array
 */
function jreturn($status=true,$msg='',$data=''){
    $return['status'] = $status;
    $return['msg'] = $msg;
    $return['data'] = $data;
    return $return;
}
/**
 * 用于模板中引入其他模板
 * @param string $fileName 模版文件名称
 * @param string $theme 主题名称
 */
function display($fileName,$theme=''){
    $prePath = '';
    if ($theme) {
        $prePath = $theme . '/';
    }
    $viewFile = APP_PATH . MODULE_NAME . '/view/' . $prePath . CONTROLLER_NAME . '/' . $fileName . '.html';
    //不存在
    if (!is_file($viewFile)) {
        return '';
    }
    //编译后存放的路径
    $tplcachepath = __ROOT__ . CACHE_DIR.'tplcache/' . $theme . '-'.MODULE_NAME.'-' . CONTROLLER_NAME . '-'.ACTION_NAME.'-' . $fileName . '.tpl.php';
    //是否有布局模板
    $layoutpath = '';
    //编译模版
    $template = new \Framework\Template;
    $viewfile = $template->display($viewFile, $tplcachepath, '');
    return $viewfile;
}