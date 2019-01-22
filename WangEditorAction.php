<?php

namespace fsyd88\wangEditor;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UploadedFile;

class WangEditorAction extends Action {

    /**
     * @var array
     */
    public $rootDir = 'uploads';
    protected $files = [];

    public function init() {
        //close csrf
        Yii::$app->request->enableCsrfValidation = false;
        parent::init();
    }

    public function run() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!file_exists($this->rootDir)) {
            mkdir($this->rootDir);
        }
        return $this->handleAction();
    }

    /**
     * 处理action
     */
    protected function handleAction() {
        if (empty($_FILES)) {
            return $this->createError(4, '没有文件被上传');
        }
        foreach ($_FILES as $value) {
            switch ($value['error']) {
                case 0:
                    if (!$this->valid($value)) {
                        return $this->createError(9, '文件格式不允许');
                    }
                    if (!$this->uploadImage($value)) {
                        return $this->createError(8, '文件上传失败');
                    }
                    break;
                case 1:
                case 2:
                    return $this->createError(2, '文件过大');
                case 3:
                case 4:
                    return $this->createError(4, '没有文件被上传');
                case 6:
                    return $this->createError(4, '找不到临时文件夹');
                case 7:
                    return $this->createError(4, '文件写入失败');
            }
        }
        return $this->createSuccess($this->files);
    }

    //验证格式
    protected function valid($file) {
        if (preg_match('/image\/.+/', $file['type'])) {
            return true;
        }
        return false;
    }

    protected function uploadImage($file) {

        if (is_uploaded_file($file['tmp_name'])) {
            $path = $this->rootDir . '/' . date('Ymd');
            if (!file_exists($path)) {
                mkdir($path);
            }
            //获取文件扩展名
            $info = explode('.', $file['name']);
            $ext = array_pop($info);

            $new_filename = $path . '/' . uniqid() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $new_filename)) {
                $this->files[] = '/' . $new_filename;
                return true;
            }
            return false;
        }
    }

    protected function createError($errno, $message) {
        return ['errno' => $errno, 'message' => $message];
    }

    protected function createSuccess($data) {
        return ['errno' => 0, 'data' => $data];
    }

}
