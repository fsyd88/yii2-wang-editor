Yii2 wangEditor widget
======================

Yii2 wangEditor widget

[wangEditor 官网](http://www.wangeditor.com/)

安装
------------ 

```
php composer.phar require --prefer-dist fsyd88/yii2-wang-editor "*"
```

使用
-----

widget 使用：
 
```php
echo \fsyd88\wangEditor\WangEditorWidget::widget([
    'name' => 'inputName',
]);
#或者
echo \fsyd88\wangEditor\WangEditorWidget::widget([
    'model'=>$model,
    'attribute'=>'name',
]);
```

ActiveForm 中使用：

```php
$form = new \yii\widgets\ActiveForm();
echo $form->field($model, 'content')->widget(\fsyd88\wangEditor\WangEditorWidget::className());
```
文件上传
```
echo \fsyd88\wangEditor\WangEditorWidget::widget([
    'model'=>$model,   
    'attribute'=>'name'
]);
#控制器中加上
public function actions() {
    return [
        'upload' => [
            'class' => 'fsyd88\wangEditor\WangEditorAction',
        ]
    ];
}
```

配置
-----

```
echo \fsyd88\wangEditor\WangEditorWidget::widget([
    'model'=>$model,   
    'attribute'=>'name',
    'id'=>'iidd',  #id for div(container);div 容器id
    'options'=>['id'=>'inputId'],  #hidden input options; 隐藏input的选项
    'customConfig'=>[  #editor custom config info ；编辑器配置项
        'menus'=>[''head','bold','italic'],
        'uploadImgServer'=>'upload'  #upload ；上传图片 默认使用 web/uploads 目录
    ]
]);
```

更多配置见[官网配置](https://www.kancloud.cn/wangfupeng/wangeditor3/332599)
