<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

use Yii;

class Asset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/bootstrap-gtreetable/dist';

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
    public $language;
    public $minSuffix = '.min';

    public $publishOptions = [
        'forceCopy' => true
    ];
    
    public function registerAssetFiles($view) {
        $this->js[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.js';
        $this->css[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.css';

        if ($this->language !== null) {
            $langFile = 'languages/bootstrap-gtreetable.' . $this->language . (YII_ENV_DEV ? '' : '.' . $this->minSuffix) . '.js';
            if (file_exists(Yii::getAlias($this->sourcePath . DIRECTORY_SEPARATOR . $langFile))) {
                $this->js[] = $langFile;
            }
        }

        parent::registerAssetFiles($view);
    }

}
