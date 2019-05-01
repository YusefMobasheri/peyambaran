<?php

namespace app\components;

use app\models\Log;
use app\models\Menu;
use devgroup\dropzone\DropZone;
use devgroup\dropzone\UploadedFiles;
use dosamigos\tinymce\TinyMce;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

abstract class CustomActiveRecord extends ActiveRecord
{
    /* This fields for save system logs */
    protected $logging = true;
    protected $logModelID;
    protected $insertLogCode;
    protected $updateLogCode;
    protected $deleteLogCode;
    protected $searchLogCode;
    private $_old;

    /* end log */

    public function beforeSave($insert)
    {
        /* for log */
        $this->_old = $this->oldAttributes;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        /* save log process */
        if ($this->logging) {
            if ($insert && $this->insertLogCode) {
                $attributes = $this->attributes;
                if (isset($attributes['dyna']))
                    $attributes['dyna'] = $this->getDynamicAttributes();
                Log::create(Log::ACTION_INSERT,
                    $this->insertLogCode,
                    $this->tableName(),
                    $this->logModelID ? $this->{$this->logModelID} : $this->primaryKey,
                    Json::encode(['new' => $attributes])
                );
            } else if (!$insert && $this->updateLogCode)
                Log::create(Log::ACTION_UPDATE,
                    $this->updateLogCode,
                    $this->tableName(),
                    $this->logModelID ? $this->{$this->logModelID} : $this->primaryKey,
                    Json::encode(['old' => $changedAttributes, 'new' => $this->getNewValues($changedAttributes, $this->attributes)])
                );
        }
        /* end save log process */

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        /* for log */
        $this->_old = $this->oldAttributes;
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterDelete()
    {
        /* save log process */
        if ($this->logging && $this->deleteLogCode)
            @Log::create(Log::ACTION_DELETE,
                $this->deleteLogCode,
                $this->tableName(),
                $this->logModelID ? $this->{$this->logModelID} : $this->primaryKey,
                Json::encode(['old' => $this->_old])
            );
        /* save log process */

        parent::afterDelete();
    }

    public function afterFind()
    {
        /* save log process */
        if ($this->logging && $this->searchLogCode)
            @Log::create(Log::ACTION_SEARCH,
                $this->searchLogCode,
                $this->tableName(),
                $this->logModelID ? $this->{$this->logModelID} : $this->primaryKey
            );
        /* save log process */

        parent::afterFind();
    }

    public function getOld($changedAttributes)
    {
        $keys = array_keys($changedAttributes);
        return array_filter($this->_old, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getNewValues($changedAttributes, $attributes)
    {
        $result = [];
        foreach (array_keys($changedAttributes) as $attributeName)
            $result[$attributeName] = $attributes[$attributeName];
        return $result;
    }


    /**************************************    Form Renderer    **************************************/
    const FORM_SEPARATOR = 0;
    const FORM_FIELD_TYPE_TEXT = 1;
    const FORM_FIELD_TYPE_TEXT_AREA = 2;
    const FORM_FIELD_TYPE_SWITCH = 3;
    const FORM_FIELD_TYPE_SELECT = 4;
    const FORM_FIELD_TYPE_CHECKBOX_LIST = 5;
    const FORM_FIELD_TYPE_RADIO_LIST = 6;
    const FORM_FIELD_TYPE_CHECKBOX = 7;
    const FORM_FIELD_TYPE_RADIO = 8;
    const FORM_FIELD_TYPE_TEXT_EDITOR = 9;
    const FORM_FIELD_TYPE_DROP_ZONE = 10;
    const FORM_FIELD_TYPE_DATETIME = 11;
    const FORM_FIELD_TYPE_DATE = 12;
    const FORM_FIELD_TYPE_TIME = 13;
    const FORM_FIELD_TYPE_TAG = 14;
    const FORM_FIELD_TYPE_PASSWORD = 15;

    /**
     * @return array
     */
    public function formAttributes()
    {
        return [];
    }

    /**
     * @param $form ActiveForm
     * @param string $template
     * @param string $allContainerCssClass
     * @return string
     */
    public function formRenderer($form, $template = '{field}', $allContainerCssClass = '', $method = 'formAttributes')
    {
        $output = '';

        if (method_exists($this, $method)) {
            $fields = $this->{$method}();
            $i = 0;
            foreach ($fields as $name => $field) {

                if (isset($field['visible'])) {
                    if ($field['visible'] instanceof \Closure)
                        $visible = $field['visible']($this);
                    else
                        $visible = $field['visible'];

                    if (!$visible)
                        continue;
                }

                $labelEx = true;
                $label = '';
                if ($field['type'] !== static::FORM_SEPARATOR && isset($field['label'])) {
                    if ($field['label'] instanceof \Closure)
                        $label = $field['label']($this);
                    else
                        $label = $field['label'];

                    $labelEx = $label === true;

                    unset($field['label']);
                }

                $containerOptions = isset($field['containerOptions']) ? $field['containerOptions'] : [];
                unset($field['containerOptions']);
                $containerCssClass = isset($field['containerCssClass']) ? $field['containerCssClass'] : '';
                unset($field['containerCssClass']);

                $field['attribute'] = $name;
                $field['tabindex'] = isset($field['tabindex']) ? $field['tabindex'] : $i;
                $obj = $this->fieldRenderer($form, $field);
                if (!$labelEx) {
                    if (strpos($template, '{label}') === false)
                        $obj->label($label);
                    else {
                        $output .= strtr($template, ['{label}' => $label]);
                        $obj->label(false);
                    }
                }

                Html::addCssClass($containerOptions, empty($containerCssClass) ? ($field['type'] !== static::FORM_SEPARATOR ? $allContainerCssClass : 'col-sm-12') : $containerCssClass);
                $fieldHtml = Html::tag('div', $obj, $containerOptions);
                $output .= strtr($template, ['{field}' => $fieldHtml]);
                $i++;
            }
        }

        return $output;
    }

    /**
     * @param $form ActiveForm
     * @param $field array
     * sample: [
     *      'model' => $model, // optional, if not set be $this
     *      'attribute' => ...,
     *      'type' => static::FORM_FIELD_TYPE_...,
     *      'options' => [...], // field htmlOptions
     * ]
     * @return \yii\widgets\ActiveField|string
     */
    public function fieldRenderer($form, $field)
    {
        $model = isset($field['model']) ? $field['model'] : $this;
        $options = isset($field['options']) ? $field['options'] : [];
        $fieldOptions = isset($field['fieldOptions']) ? $field['fieldOptions'] : [];
        $items = isset($field['items']) ? $field['items'] : [];
        $attribute = $field['attribute'];
        $type = isset($field['type']) ? $field['type'] : false;

        if (isset($field['tabindex']))
            $options['tabindex'] = $field['tabindex'];
        switch ($type) {
            case static::FORM_SEPARATOR:
                Html::addCssClass($options, 'm-form__heading');
                return Html::tag('hr') .
                    Html::tag('div', Html::tag('h3', $field['label'], ['class' => 'm-form__heading-title']), $options);
            case static::FORM_FIELD_TYPE_DROP_ZONE:
                unset($options['tabindex']);
                if (!$model->isNewRecord)
                    $options['options']['storedFiles'] = new UploadedFiles($field['path'], $model->$attribute, $field['filesOptions']);
                return $form->field($model, $attribute)->widget(DropZone::className(), $options);
            case static::FORM_FIELD_TYPE_CHECKBOX:
                return $form->field($model, $attribute)->checkbox($options);
            case static::FORM_FIELD_TYPE_RADIO:
                return $form->field($model, $attribute)->radio($options);
            case static::FORM_FIELD_TYPE_CHECKBOX_LIST:
                return $form->field($model, $attribute)->checkboxList($items, $options);
            case static::FORM_FIELD_TYPE_RADIO_LIST:
                return $form->field($model, $attribute)->radioList($items, $options);
            case static::FORM_FIELD_TYPE_SELECT:
                return $form->field($model, $attribute)->dropDownList($items, $options);
            case static::FORM_FIELD_TYPE_SWITCH:
                if (!isset($fieldOptions['template']))
                    $fieldOptions['template'] = '{label}<label class="switch">{input}<span class="slider round"></span></label>{error}';
                return $form->field($model, $attribute, $fieldOptions)->checkbox([], false);
            case static::FORM_FIELD_TYPE_TEXT_EDITOR:
                return $form->field($model, $attribute, $fieldOptions)->widget(TinyMce::className(), $options);
            case static::FORM_FIELD_TYPE_TEXT_AREA:
                return $form->field($model, $attribute, $fieldOptions)->textarea($options);
            case static::FORM_FIELD_TYPE_PASSWORD:
                return $form->field($model, $attribute, $fieldOptions)->passwordInput($options);
            case static::FORM_FIELD_TYPE_TAG:
            case static::FORM_FIELD_TYPE_TIME:
            case static::FORM_FIELD_TYPE_DATE:
            case static::FORM_FIELD_TYPE_DATETIME:
            case static::FORM_FIELD_TYPE_TEXT:
            default:
                return $form->field($model, $attribute, $fieldOptions)->textInput($options);
        }
    }

    public static function encodeUrl($url)
    {
        return str_replace(array(' ', '/', '\\'), '-', $url);
    }
}