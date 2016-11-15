<?php

/*
 * 上传
 */

namespace Blog\Controller;

use \Common\webController;
use Common\Util\upload;
use Common\Util\image;

class uploadController extends webController {

    public function index() {
        if (!session('user.uid')) {
            $this->ajaxReturn('请先登录', 'login', false);
        }
        $upload=new upload();
        
        $upload->init($_FILES['file'], 'article', 'data/attachment');
        $file = &$upload->file;

        if (!$file['isimage']) {
            $this->ajaxReturn('请上传图片附件  0027', '', false);
        }
        if ($file['size'] > 2097152) {//2M
            $this->ajaxReturn('请上传小余 2M 的图片  0032', '', false);
        }
        $upload->save($file['tmp_name'], $file['target']);
        $errorcode = $upload->error();
        if ($errorcode < 0) {
            $data = $upload->errormsg();
            @unlink($file['target']);
            $this->ajaxReturn($data, '', false);
        }
        //判断是否开启了exif，并获取照片的exif信息
        $my_exif = array();
        if (extension_loaded('exif') && extension_loaded('mbstring')) {
            $my_exif = exif_read_data($file['target'], "EXIF");
        }
        $image = new image($file['target']);
        $image->exif = $my_exif;
        $status = $image->Thumb();
        if ($status <= 0) {
            $data = $image->errormsg($status);
            @unlink($file['target']);
            $this->ajaxReturn($data, '', false);
        }
        $insert = array(
            'uid' => session('user.uid'),
            'aid' => 0,
            'path' => $file['imgurl'],
            'type' => 'article',
            'size' => $image->imginfo['size'],
            'width' => $image->imginfo['width'],
            'height' => $image->imginfo['height'],
            'thumbH' => $image->imginfo['thumbH'],
            'status' => 0,
            'dateline' => TIMESTAMP,
        );
        $aid = Model('image')->add($insert);
        if (!is_numeric($aid) || $aid <= 0) {
            @unlink($file['target']);
            $this->ajaxReturn('上传失败  0071', '', false);
        }

        $data['url'] = SITE_URL . $file['imgurl'] . '.thumb.jpg';
        $data['aid'] = $aid;
        $this->ajaxReturn('success' ,$data);
    }

}
