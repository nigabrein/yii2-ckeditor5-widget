yii2-ckeditor5-widget
==============
Виджет для ckeditor5 для yii2

Установка
------------
Предпочтительный способ установки этого расширения — через [composer](http://getcomposer.org/download/).

Либо вставьте
```
"nigabrein/yii2-ckeditor5-widget": "*"
```
в раздел require вашего файла `composer.json`

Использование
-----

Вам нужно [скачать](https://ckeditor.com/ckeditor-5/online-builder/) или добавить имеющийся файл `ckeditor.js` в `AppAsset.php` в раздел js.

Путь
```
use nigabrein\ckeditor5\CKEditor;
```

Виджет 1
```
<?= $form->field($model, 'text')->widget(CKEditor::className(), 
   [
       'options' => ['id' => 'text-editor-id-you'],
       'toolbar' => [
          'SourceEditing', 
          '|', 'undo', 'redo',
          '|', 'heading',
          '|', 'bold', 'italic',
          '|', 'alignment:left', 'alignment:right', 'alignment:center',
          '|', 'link', 'uploadImage', 'blockQuote', 'insertTable',
          '|', 'bulletedList', 'numberedList',
          '|', 'removeFormat',
       ],
       //'value' => 'Text',
   ]
); ?>
```

Виджет 2
```
<?= $form->field($model_create, 'name')->widget(CKEditor::className(), 
   [
       'toolbar' => [
          'bold', 'italic','link','removeFormat',
       ],
   ]
); ?>
```
Функции
```
'uploadUrl' => 'site/upload', //this will be the url where you want to ckeditor send the post request with file data

'uploadUrl' => '/someUpload.php',

'value' => 'Text',

'toolbar' => [
   'Sourceediting',
   '|', 'heading', 
   '|',
   'bold', 
   'italic', 
   'link',
   'bulletedList',
   'numberedList',
   'blockQuote',
   '|',
   'indent',
   'outdent',
   '|',
   'imageUpload',
   'insertTable',
   'mediaEmbed',
   'undo',
   'redo',
   'exportPdf',
   'exportWord',
   'fontSize',
   'fontFamily',
   'fontColor',
   'fontBackgroundColor',
   'highlight',
   'imageInsert',
   'alignment',
   'alignment:left', 
   'alignment:right', 
   'alignment:center',
   'code',
   'removeFormat'
]
```

Прочее
```
<?= $form->field($model, 'content')->widget(CKEditor::className(),[
   'clientOptions' => [
      'language' => 'en',
      'uploadUrl' => 'upload',   //url for upload files
      'uploadField' => 'image',  //field name in the upload form
   ]
]); ?>
```
Конфликт стилей bootstrap5 и ckeditor5 - если поле ckeditor5 находиться в модальном окне bootstrap5 то не работает функция 'link' у ckeditor5.
Решение:
```
document.addEventListener("DOMContentLoaded", function() {
  var button = document.querySelector('button[crutch-ckeditor-btn="true"]');
  
  if (button) {
    button.addEventListener("click", function() {
        
      var element = document.querySelector('.ck-body-wrapper');
      if(element){
        //element.style.position = 'fixed';
        //element.style.zIndex = 1060;
          
        var modal = document.querySelector('div[crutch-ckeditor-modal="true"]');
        if(modal){
            modal.appendChild(element);
        }
      }
    });
  }
});

//crutch-ckeditor-btn="true" параметр на кнопку с модальным окном с ckeditor

//crutch-ckeditor-modal="true" параметр на модальное окно с ckeditor
```
Или
```
function CrutChckeditorModal(type, my_id_modal_create, my_id_modal_update){
    if(type === 'create'){
        var ckBodyWrapper = document.createElement('div');
        ckBodyWrapper.classList.add('ck-body-wrapper');
        ckBodyWrapper.style.zIndex = "1060";
        document.body.appendChild(ckBodyWrapper);
        
        // Находим модальное окно
        var myModal_create = new bootstrap.Modal(document.getElementById(my_id_modal_create));

        // Отключаем FocusTrap при открытии модального окна
        myModal_create._element.addEventListener('shown.bs.modal', function () {
          myModal_create._focustrap.deactivate();
        });
        
        var myModal_update = new bootstrap.Modal(document.getElementById(my_id_modal_update));

        myModal_update._element.addEventListener('shown.bs.modal', function () {
          myModal_update._focustrap.deactivate();
        });
    }
        
    if(type === 'update'){
        var element = document.querySelector('.ck-body-wrapper');
        if(element){
            element.innerHTML = '';
        }
    }
}
```

Чтобы убрать с поля ckeditor5 логотип нужно скрыть ck-powered-by:
```
.ck.ck-powered-by a {
    display: none !important;
}
```
