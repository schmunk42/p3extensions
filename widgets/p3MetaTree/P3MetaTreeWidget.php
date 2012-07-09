<?php

/**
 * Class file
 */
/**
 * Renders a hierarchical list of meta data records
 *
 * @see P3MetaDataBehavior
 */
Yii::setPathOfAlias('P3MetaTreeWidget', dirname(__FILE__));

class P3MetaTreeWidget extends CWidget {

    public $model = null;
    public $rootNodeId = null;
    public $view = 'tree';
    public $routes = array(
        'updateContent' => null,
        'updateMetaData' => null,
        'viewContent' => null,
        'viewMetaData' => null,
    );
    public $cssClass = "p3-meta-tree-widget";

    function init() {

    }

    function run() {
        $criteria = new CDbCriteria;
        // SQLite workaround for <=>
        if ($this->rootNodeId === null) {
            $criteria->condition = "treeParent_id IS :id";
        } else {
            $criteria->condition = "treeParent_id = :id";
        }
        $criteria->params = array(':id' => $this->rootNodeId);
        $model = new $this->model;
        $firstLevelNodes = $model::model()->findAll($criteria);
        //var_dump($firstLevelNodes);exit;
        $this->renderTree($firstLevelNodes);
    }

    private function renderTree($models) {
        echo "<div  class='{$this->cssClass}'>";
        echo "<ul>";
        foreach ($models AS $model) {
            echo "<li>";
            $this->render($this->view, array('model' => $model));
            $this->renderTree($model->getChildren());
            echo "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }

}

?>
