<?php

namespace stalkalex\utilities\components;

use yii\validators\Validator;
use yii\web\UploadedFile;
use Yii;

/**
 * Validator that can be used within UploadForm model
 * Class AvFileValidator
 */
class AvFileValidator extends Validator
{
    public $virusMessage;

    public function validateAttribute($model, $attribute)
    {
        assert($model->$attribute instanceof UploadedFile);
        $filePath = $model->file->tempName;
        $result = $this->check($filePath);
        if (!$result) {
            $this->addError($model, $attribute, Yii::t('stalkalex.avFileValidator', $this->virusMessage));
            return false;
        }
        return true;
    }

    private function check($filePath)
    {
        if (Yii::$app->has('av')) {
            $result = Yii::$app->av->check($filePath);
        } else {
            $checker = new AvCheck();
            $result = $checker->check($filePath);
        }
        return $result;
    }
}