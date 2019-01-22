<?php

namespace fsyd88\wangEditor;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Json;

class WangEditorWidget extends InputWidget {

    public $customConfig;

    /**
     * @var string
     */
    private $_editorId;

    public function init() {
        parent::init();
        $this->_editorId = 'editor-' . $this->id;
    }

    public function run() {
        if ($this->hasModel()) {
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
            $attribute = $this->attribute;
            $content = Html::getAttributeValue($this->model, $attribute);
        } else {
            echo Html::hiddenInput($this->name, $this->value, $this->options);
            $content = $this->value;
        }
        echo Html::tag('div', $content, ['id' => $this->_editorId]);
        $this->registerJs();
    }

    public function registerJs() {
        $view = $this->getView();
        WangEditorAsset::register($view);


        $this->customConfig = $this->customConfig? : [];

        $id = $this->_editorId;
        $name = 'weditor' . $this->id;
        $hiddenInputId = $this->options['id'];
        $config = array_merge(['uploadImgServer' => 'upload'], $this->customConfig);
        $customOptions = $name . '.customConfig=' . Json::htmlEncode($config);
        
        $js = <<<JS
            var {$name} = new window.wangEditor('#{$id}');            
            {$customOptions}            
            {$name}.customConfig.onchange = function (html) {
                $('#{$hiddenInputId}').val(html);
            }
            {$name}.create();
JS;
        $view->registerJs($js);
    }

}
