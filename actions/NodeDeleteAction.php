<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\Html;

class NodeDeleteAction extends ModifyAction {

    public function run($id) {
        $model = $this->getNodeById($id);

        if ($model->isRoot() && (integer) $model->findNestedSet()->roots()->count() === 1) {
            throw new HttpException(500, Yii::t('gtreetable', 'Main element can`t be deleted!'));
        }

        $nodes = $model->descendants()->with()->all();
        $nodes[] = $model;

        $trans = $model->getDB()->beginTransaction();
        try {
            if (is_callable($this->beforeAction)) {
                call_user_func_array($this->beforeAction,['model' => $model]);
            }
            
            if (!$model->deleteNode()) {
                throw new Exception(Yii::t('gtreetable', 'Deleting operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }
            
            if (is_callable($this->afterAction)) {
                call_user_func_array($this->afterAction,['model' => $model]);
            }          
            
            $trans->commit();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            throw new HttpException(500, $e->getMessage());
        }
    }

}
