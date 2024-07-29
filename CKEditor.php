<?php

namespace nigabrein\ckeditor5;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use Yii;

/**
 * CKEditor renders a CKEditor5 js plugin for classic editing.
 * @author Parveen nigabrein <itanikobolow@gmail.com>
 * @package nigabrein\ckeditor5
 */
class CKEditor extends InputWidget
{
    public $clientOptions = [
        'language'=> 'ru',
        'toolbar' => [
            'items' => [
		'heading',
		'|',
		'bold',
		'italic',
		'link',
		'bulletedList',
		'numberedList',
		'|',
		'indent',
		'outdent',
		'|',
		'imageUpload',
		'blockQuote',
		'insertTable',
		'mediaEmbed',
		'undo',
		'redo',
		'fontSize',
		'fontFamily',
		'fontColor',
		'fontBackgroundColor',
		'highlight',
		'imageInsert',
		'alignment'
            ],
        ]
    ];
    
    public $toolbar;
    
    public $uploadUrl;
    
    public $value = null;
    
    /**
     * @inheritdoc
     */
    
    public function init()
    {
        parent::init();
       // $this->initOptions();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }

        $this->registerPlugin();
    }

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        if (!empty($this->toolbar)) {
            $this->clientOptions['toolbar'] = $this->toolbar;
            $this->clientOptions['mediaEmbed'] = ['toolbar' => $this->toolbar];
        }
	    
        if (!empty($this->uploadUrl)) {
            $this->clientOptions['simpleUpload'] = [
                'uploadUrl' => $this->uploadUrl,
                'withCredentials' => true,
                'headers' => [
                    'X-CSRF-TOKEN' => Yii::$app->request->csrfToken,
                ]
            ];
        }
	    
        $clientOptions = Json::encode($this->clientOptions);
      
 	$js = new JsExpression(
            "ClassicEditor.create( document.querySelector( '#{$this->options['id']}' ), {$clientOptions} ).then(
                    editor => {
                        //console.log( editor );
                        editor.setData(`{$this->value}`);
                        window.editors = window.editors || {};
                        window.editors['{$this->options['id']}'] = editor;
                    }
            ).catch( error => {console.error( error );} );"
        );

        $this->view->registerJs($js, \yii\web\View::POS_READY, 'ck-editor-' . $this->options['id']);
    }
}
