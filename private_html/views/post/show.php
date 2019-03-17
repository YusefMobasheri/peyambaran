<?php
/** @var $this \yii\web\View */
/** @var $model \app\models\Post */
/** @var $relatedPosts \app\models\Post[] */

?>
<section class="news-show">
    <div class="container">
        <div class="row news-show-container">
            <div class="col-xs-12">
                <div class="content-header ">
                    <img src="<?= $this->theme->baseUrl?>/images/news/news-header-image.png" class="img-fluid content-header__image" alt="">
                    <div class="content-header__titles">
                        <h1 class="media-heading content-header__title"><?= Yii::t('words', 'News & Articles') ?></h1>
                        <h3 class="content-header__subTitle"><?= Yii::t('words', 'Payambaran hospital') ?></h3>
                    </div>
                    <div class="newsSearchBox">
                        <form class="search-form" style="min-width: 400px;">
                            <p style="color: #fff;margin-right: 10px;"
                               class="search-form-label"><?= Yii::t('words', 'Search in news...') ?></p>
                            <div class="input-group search-container">
                                <input class="form-control" placeholder="<?= Yii::t('words', 'Search...') ?>">
                                <span class="input-group-addon">
                            <button type="submit" class="search-btn"><i class="search-icon"></i></button>
                        </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="col-xs-12">
                <div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-4 pr-0">
                                <div class="card m-0">
                                    <div class="card-header">
                                        <p>
                                            <i class="icomoon-comment-alt-regular"> <?= number_format($model->comments_count) ?> </i>
                                        </p>

                                        <p>
                                            <i class="icomoon-eye-regular"> <?= number_format($model->seen) ?> </i>
                                        </p>
                                        <p>
                                            <i class="icomoon-calendar-alt-regular"> <?= $model->publish_date ?> </i>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <a title="" href="#" class="card-link">
                                            <img class="card-img-top" src="<?= Yii::getAlias('@web/uploads/post/').$model->image ?>" alt="">
                                            <h4 class="card-title"><?= $model->name ?></h4>
                                        </a>
                                    </div>
                                    <hr>
                                    <div class="clearfix">
                                        <p style="color: #7a7a7a;">تصاویر خبری</p>
                                        <div class="imgContainer">
                                            <a class="simpleGallery__link" href="<?= $this->theme->baseUrl?>/images/gallery/gallery-2.jpg">
                                                <img class="simpleGallery__image" src="<?= $this->theme->baseUrl?>/images/gallery/gallery-2.jpg"
                                                     alt="">
                                                <!--<div class="-hoverShowBox purple rounded">-->
                                                <!--<div>-->
                                                <!--<h4>آزمایشگاه پاتولوژی</h4>-->
                                                <!--<div class="zoom-icon"><i></i></div>-->
                                                <!--</div>-->
                                                <!--</div>-->
                                            </a>
                                        </div>
                                        <div class="imgContainer">
                                            <a class="simpleGallery__link" href="images/gallery/gallery-2.jpg">
                                                <img class="simpleGallery__image" src="images/gallery/gallery-2.jpg"
                                                     alt="">
                                                <!--<div class="-hoverShowBox purple rounded">-->
                                                <!--<div>-->
                                                <!--<h4>آزمایشگاه پاتولوژی</h4>-->
                                                <!--<div class="zoom-icon"><i></i></div>-->
                                                <!--</div>-->
                                                <!--</div>-->
                                            </a>
                                        </div>
                                        <div class="imgContainer">
                                            <a class="simpleGallery__link" href="images/gallery/gallery-2.jpg">
                                                <img class="simpleGallery__image" src="images/gallery/gallery-2.jpg"
                                                     alt="">
                                                <!--<div class="-hoverShowBox purple rounded">-->
                                                <!--<div>-->
                                                <!--<h4>آزمایشگاه پاتولوژی</h4>-->
                                                <!--<div class="zoom-icon"><i></i></div>-->
                                                <!--</div>-->
                                                <!--</div>-->
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-8  px-0">
                                <div class="newsShow">
                                    <div class="newsShow__content">
                                        <h4 class="newsShow__title"><?= $model->name ?></h4>
                                        <div class="mb-5 mt-5 text-justify page-text"><?= $model->body ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="related-news">
        <div class="container">
            <div class="row insurance-container">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <div class="inline-title">
                        <h3 class="section-title"><?= Yii::t('words', 'Related News & Articles') ?></h3>
                    </div>
                </div>
            </div>
            <div class="news-carousel owl-carousel owl-theme" data-stagePadding="50" data-rtl="true"
                 data-autoWidth="true" data-nav="true" data-items="3" data-margin="15">
                <?php foreach ($relatedPosts as $item): ?>
                    <div class="news-item">
                        <div class="news-item-inner">
                            <a href="<?= $item->url ?>">
                                <div class="statics-row">
                                <span class="comments-count text-right"><?= number_format($item->comments_count) ?><i
                                            class="comment-icon"></i></span>
                                    <span class="view-count text-center"><?= number_format($item->seen) ?><i
                                                class="eye-icon"></i></span>
                                    <span class="news-date text-left"><?= jDateTime::date('Y/m/d', strtotime($item->publish_date)) ?>
                                        <i class="calendar-icon"></i></span>
                                </div>
                                <div class="news-image">
                                    <div class="news-image-inner">
                                        <img src="<?= Yii::getAlias('@web/uploads/post/') . $item->image ?>">
                                    </div>
                                </div>
                                <div class="news-details">
                                    <h3><?= $item->name ?></h3>
                                    <div class="news-description"><?= !empty($item->summary) ? $item->summary : mb_substr(strip_tags(nl2br($item->body)), 0, 200) ?>
                                        <div class="overlay"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
