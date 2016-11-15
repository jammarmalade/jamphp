<?php
namespace Common\Util;

class upload {

    var $file = array(); //文件信息
    var $type = ''; //目录名称
    var $errorcode = 0; //错误代号
    var $attachdir = '';
    var $errormsg = array('1' => '图片无法保存', '2' => '上传失败'); //错误提示

    function init($file, $type, $attachdir) {
        if (!is_array($file) || empty($file) || !$this->is_upload_file($file['tmp_name']) || trim($file['name']) == '' || $file['size'] == 0) {
            $this->file = array();
            $this->errorcode = 2; //上传失败
            return false;
        } else {
            $this->type = $this->check_dir_type($type);
            $this->attachdir = __ROOT__ . '/' . $attachdir;
            $file['size'] = intval($file['size']);
            $file['name'] = trim($file['name']);
            $file['thumb'] = '';
            $file['ext'] = $this->fileext($file['name']);

            $file['name'] = htmlspecialchars($file['name'], ENT_QUOTES);
            if (strlen($file['name']) > 90) {
                $file['name'] = cutstr($file['name'], 80, '') . '.' . $file['ext'];
            }

            $file['isimage'] = $this->is_image_ext($file['ext']);
            $file['filedir'] = $this->get_target_dir($this->type);
            $file['filepath'] = $file['filedir'] . $this->get_target_filename() . '.jpg';
            $file['target'] = $this->attachdir . '/' . $this->type . '/' . $file['filepath'];
            $file['imgurl'] = $attachdir . '/' . $this->type . '/' . $file['filepath'];

            $this->file = & $file;
            $this->errorcode = 0;
            return true;
        }
    }

    //保存图片
    function save($source, $target) {
        if (!self::is_upload_file($source)) {
            $succeed = false;
        } elseif (@copy($source, $target)) {
            $succeed = true;
        } elseif (function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)) {
            $succeed = true;
        } elseif (@is_readable($source) && (@$fp_s = fopen($source, 'rb')) && (@$fp_t = fopen($target, 'wb'))) {
            while (!feof($fp_s)) {
                $s = @fread($fp_s, 1024 * 512);
                @fwrite($fp_t, $s);
            }
            fclose($fp_s);
            fclose($fp_t);
            $succeed = true;
        }
        if ($succeed) {
            $this->errorcode = 0;
            @chmod($target, 0644);
            @unlink($source);
        } else {
            $this->errorcode = 1; //图片无法保存
        }
        return true;
    }

    //是否是上传的文件
    function is_upload_file($source) {
        return $source && ($source != 'none') && (is_uploaded_file($source) || is_uploaded_file(str_replace('\\\\', '\\', $source)));
    }

    //存放目录
    function check_dir_type($type) {
        return !in_array($type, array('article')) ? 'temp' : $type;
    }

    //获取后缀名
    function fileext($filename) {
        return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
    }

    //是否是图片
    function is_image_ext($ext) {
        static $imgext = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
        return in_array($ext, $imgext) ? 1 : 0;
    }

    //获取要存入的目标文件夹路径
    function get_target_dir($type) {
        $subdir = $subdir1 = $subdir2 = '';
        $subdir1 = date('Ym');
        $subdir2 = date('d');
        $subdir = $subdir1 . '/' . $subdir2 . '/';

        self::check_dir_exists($type, $subdir1, $subdir2);

        return $subdir;
    }

    //检查文件夹目录是否存在，不存在则创建
    function check_dir_exists($type = '', $sub1 = '', $sub2 = '') {
        $basedir = $this->attachdir;
        $typedir = $type ? ($basedir . '/' . $type) : '';
        $subdir1 = $type && $sub1 !== '' ? ($typedir . '/' . $sub1) : '';
        $subdir2 = $sub1 && $sub2 !== '' ? ($subdir1 . '/' . $sub2) : '';

        $res = $subdir2 ? is_dir($subdir2) : ($subdir1 ? is_dir($subdir1) : is_dir($typedir));

        if (!$res) {
            $res = $typedir && self::make_dir($typedir);
            $res && $subdir1 && ($res = self::make_dir($subdir1));
            $res && $subdir1 && $subdir2 && ($res = self::make_dir($subdir2));
        }
        return $res;
    }

    //创建目录
    function make_dir($dir, $index = true) {
        $res = true;
        if (!is_dir($dir)) {
            $res = @mkdir($dir, 0777);
            $index && @touch($dir . '/index.html');
        }
        return $res;
    }

    //获取存入后的图片的名字
    function get_target_filename() {
        return date('His') . strtolower(random(16));
    }

    //返回错误代号
    function error() {
        return $this->errorcode;
    }

    function errormsg() {
        return $this->errormsg[$this->errorcode];
    }

}
