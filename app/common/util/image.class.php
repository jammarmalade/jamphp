<?php

namespace Common\Util;

class image {

    var $p = array();
    var $rotate_angle_gd = array(3 => '180', 6 => '360', 8 => '90');
    var $rotate_angle_im = array(3 => '180', 6 => '90', 8 => '270');
    var $exif = array();
    var $imginfo = array();
    var $orientation = '';
    var $angle = '';
    var $errormsg = array('0' => '缩略图生成失败', '-1' => '目标图片不存在', '-2' => '目录不可写', '-3' => '不支持的图片类型'); //错误提示

    function __construct($source, $target = '', $thumbW = 600, $thumbH = 3000) {
        if ($source == $target || $target == '') {
            $target = $source . '.thumb.jpg';
        }
        $this->p = array(
            'imagickpath' => '/usr/local/imagemagick/bin/',
            'thumbquality' => 85,
            'imglib' => 'gd',
            'source' => $source,
            'target' => $target,
            'thumbW' => $thumbW,
            'thumbH' => $thumbH,
        );
    }

    function Thumb() {
        $targetpath = dirname($this->p['target']);

        clearstatcache();
        if (!is_readable($this->p['source']) || !is_writable($targetpath)) {
            return -2;
        }

        $imginfo = @getimagesize($this->p['source']);
        if ($imginfo === FALSE) {
            return -1;
        }
        $this->imginfo['width'] = $imginfo[0];
        $this->imginfo['height'] = $imginfo[1];
        $this->imginfo['mime'] = $imginfo['mime'];
        $this->imginfo['size'] = @filesize($this->p['source']);
        //以宽为基准计算缩略图等比例高度
        $this->imginfo['thumbH'] = $this->p['thumbH'] = ceil($this->imginfo['height'] * ($this->p['thumbW'] / $this->imginfo['width']));
        if ($this->exif && in_array($this->exif['Orientation'], array(3, 6, 8))) {
            $this->orientation = $this->exif['Orientation'];
            $this->angle = $this->p['imglib'] == 'gd' ? $this->rotate_angle_gd[$this->exif['Orientation']] : $this->rotate_angle_im[$this->exif['Orientation']];
        }
        $return = $this->p['imglib'] == 'gd' ? $this->Thumb_GD() : $this->Thumb_IM();
        return $return;
    }

    function loadsource($imagecreatefromfunc) {
        $im = @$imagecreatefromfunc($this->p['source']);
        if (!$im) {
            if (!function_exists('imagecreatefromstring')) {
                return 0;
            }
            $fp = @fopen($this->p['source'], 'rb');
            $contents = @fread($fp, filesize($this->p['source']));
            fclose($fp);
            $im = @imagecreatefromstring($contents);
            if ($im == FALSE) {
                return -3;
            }
        }
        return $im;
    }

    function Thumb_GD() {

        $img = imagecreatetruecolor($this->imginfo['width'], $this->imginfo['height']);
        $white = imagecolorallocate($img, 255, 255, 255); //白色
        imagefill($img, 0, 0, $white);
        switch ($this->imginfo['mime']) {
            case 'image/jpeg':
                $imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
                $imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
                break;
            case 'image/gif':
                $imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
                $imagefunc = function_exists('imagegif') ? 'imagegif' : '';
                break;
            case 'image/png':
                $imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
                $imagefunc = function_exists('imagepng') ? 'imagepng' : '';
                break;
        }
        if ($imagecreatefromfunc == '' || $imagefunc == '') {
            $imagecreatefromfunc = 'imagecreatefromjpeg';
            $imagefunc = 'imagejpeg';
        }
        $srcImage = $this->loadsource($imagecreatefromfunc);
        if ($srcImage <= 0) {
            return $srcImage;
        }
        imagecopy($img, $srcImage, 0, 0, 0, 0, $this->imginfo['width'], $this->imginfo['height']);
        $returnPic = imagecreatetruecolor($this->p['thumbW'], $this->p['thumbH']);
        imagecopyresampled($returnPic, $img, 0, 0, 0, 0, $this->p['thumbW'], $this->p['thumbH'], $this->imginfo['width'], $this->imginfo['height']);
        clearstatcache();
        if ($returnPic) {
            //若有旋转角度，则旋转图片
            if ($this->angle) {
                $returnPic = imagerotate($returnPic, $this->angle, 0);
            }

            if ($this->imginfo['mime'] == 'image/jpeg') {
                @$imagefunc($returnPic, $this->p['target'], $this->p['thumbquality']);
            } else {
                @$imagefunc($returnPic, $this->p['target']);
            }
            $status = 1;
        } else {
            $status = 0;
        }
        imagedestroy($returnPic);
        imagedestroy($img);
        return $status;
    }

    function Thumb_IM() {
        //若有旋转角度，则旋转图片
        $rotate_str = '';
        if ($this->angle) {
            $rotate_str = '-rotate ' . $this->angle;
        }
        if ($this->imginfo['width'] > $this->p['thumbW'] || $this->imginfo['height'] > $this->p['thumbH']) {
            $exec_str = $this->p['imagickpath'] . '/convert -quality ' . intval($this->p['thumbquality']) . ' -geometry ' . $this->p['thumbW'] . 'x' . $this->p['thumbH'] . ' ' . $rotate_str . ' +profile "*" ' . $this->p['source'] . ' ' . $this->p['target'];
            exec($exec_str);
            if (!file_exists($this->p['target'])) {
                return 0;
            }
        }
        return 1;
    }

    function errormsg($code) {
        return $this->errormsg[$code];
    }

}
