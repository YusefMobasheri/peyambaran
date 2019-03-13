<?php

use app\models\Menu;

/** @var $this \yii\web\View */
/** @var $model \app\models\Page */
/** @var $relatedMenu Menu */

$relatedMenu = Menu::find()->andWhere([
    Menu::columnGetString('page_id') => $model->id,
    Menu::columnGetString('content') => 1,
])->one();
if ($relatedMenu)
//    $root = $relatedMenu->parents()->andWhere('parentID IS NULL')->one();
    $root = $relatedMenu->parents(1)->one();
?>

<section class="gallery">
    <div class="container">
        <div class="row">
            <?php if ($root): ?>
                <div class="col-md-3">
                    <nav id="sidebar">
                        <div class="sidebar-header mt-5">
                            <h4><?= $root->name ?></h4>
                        </div>
                        <ul class="list-unstyled mt-5">
                            <?php foreach ($root->children(1)->all() as $sub_item):$sc = $sub_item->children(1)->count(); ?>
                                <li class="mb-3">
                                    <?php if ($sc > 0): ?>
                                        <a href="void:;" class="text-purple"><?= $sub_item->name ?></a>
                                        <ul class="list-unstyled submenu">
                                            <?php foreach ($sub_item->children(1)->all() as $sub_item_child): ?>
                                                <li>
                                                    <a class="-hoverBlue text-dark-2<?= $relatedMenu->id === $sub_item_child->id ? " active" : "" ?>"
                                                       href="<?= $sub_item_child->url ?>"><?= $sub_item_child->name ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <a class="-hoverBlue text-dark-2<?= $relatedMenu->id === $sub_item->id ? " active" : "" ?>"
                                           href="<?= $sub_item->url ?>"><?= $sub_item->name ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
            <div class="<?= $root ? 'col-sm-9' : 'col-sm-10 col-sm-offset-1' ?>">
                <div class="content-header bg-cyan">
                    <div class="content-header__gradient-overlay"></div>
                    <img class="content-header__fade-bg"
                         src="<?= Yii::getAlias('@web/uploads/pages/') . $model->image ?>">
                    <div class="content-header__titles">
                        <h1 class="media-heading content-header__title"><?= $model->name ?></h1>
                    </div>
                </div>
                <div class="content-body">
                    <div class="mb-5 mt-5 text-justify page-text"><?= $model->body ?></div>
                    <?php if ($model->gallery):
                        $this->registerJsFile($this->theme->baseUrl . '/js/vendors/html5lightbox/html5lightbox.js',[],'lightbox');
                        ?>
                        <div class="mt-3 mb-5 page-gallery row">
                            <? foreach ($model->gallery as $item):
                                if ($item->file && is_file(Yii::getAlias("@webroot/" . \app\models\Attachment::$attachmentPath . "/$item->path/$item->file"))):?>
                                    <div class="col-sm-3 mb-5">
                                        <div class="gallery__imageContainer">
                                            <a href="<?= Yii::getAlias("@web/" . \app\models\Attachment::$attachmentPath . "/$item->path/$item->file") ?>"
                                               data-transition="crossfade"
                                               data-thumbnail="<?= Yii::getAlias("@web/" . \app\models\Attachment::$attachmentPath . "/thumbs/200x200/$item->file") ?>"
                                               class="html5lightbox"
                                               data-group="mygroup" >
                                                <img class="gallery__images"
                                                     src="<?= Yii::getAlias("@web/" . \app\models\Attachment::$attachmentPath . "/thumbs/200x200/$item->file") ?>"
                                                     alt="<?= \yii\helpers\Html::encode($model->name) ?>">
                                            </a>

                                            <div class="-hoverBox bg-cyan">
                                                <a href="<?= Yii::getAlias("@web/" . \app\models\Attachment::$attachmentPath . "/$item->path/$item->file") ?>"
                                                   data-transition="crossfade"
                                                   data-thumbnail="<?= Yii::getAlias("@web/" . \app\models\Attachment::$attachmentPath . "/thumbs/200x200/$item->file") ?>"
                                                   class="html5lightbox"
                                                   data-group="mygroup" data-width="600"
                                                   data-height="400">
                                                    <!--                                            <h4>آزمایشگاه پاتولوژی</h4>-->
                                                    <img src="<?= $this->theme->baseUrl ?>/images/gallery/frame.png">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <? endif; ?>
                            <? endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
