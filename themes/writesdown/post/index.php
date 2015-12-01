<?php
/**
 * @file      index.php
 * @date      8/23/2015
 * @time      6:55 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $postType common\models\PostType */
/* @var $posts common\models\Post[] */
/* @var $image common\models\Media */
/* @var $tags common\models\Term[] */
/* @var $pages yii\data\Pagination */

$this->title = Html::encode($postType->post_type_pn . ' - ' . Option::get('sitetitle'));
$this->params['breadcrumbs'][] = Html::encode($postType->post_type_pn);

?>
<div class="archive post-index">
    <header id="archive-header" class="archive-header">
        <h1><?= Html::encode($postType->post_type_pn) ?></h1>
        <?php
        if ($postType->post_type_description) {
            echo Html::tag('div', $postType->post_type_description, ['class' => 'description post-description']);
        }
        ?>
    </header>
    <?php if ($posts): ?>
        <?php foreach ($posts as $post) : ?>
            <article class="hentry">
                <header class="entry-header">
                    <h2 class="entry-title"><?= Html::a(Html::encode($post->post_title), $post->url); ?></h2>
                    <?php
                    $updated = new \DateTime($post->post_modified, new DateTimeZone(Yii::$app->timeZone));
                    ?>
                    <div class="entry-meta">
                        <span class="entry-date">
                            <a rel="bookmark" href="<?= $post->url; ?>">
                                <time datetime="<?= $updated->format('c'); ?>" class="entry-date">
                                    <?= Yii::$app->formatter->asDate($post->post_date); ?>
                                </time>
                            </a>
                        </span>
                        <span class="byline">
                            <span class="author vcard">
                                <a rel="author" href="<?= $post->postAuthor->url; ?>"
                                   class="url fn"><?= $post->postAuthor->display_name; ?>
                                </a>
                            </span>
                        </span>
                        <span class="comments-link">
                            <a title="<?= Yii::t('writesdown', 'Comment on {post}', ['post' => $post->post_title]); ?>"
                               href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment'); ?>
                            </a>
                        </span>
                    </div>
                </header>
                <div class="media">
                    <?php
                    $image = $post->getMedia()->where(['LIKE', 'media_mime_type', 'image/'])->one();
                    if ($image) {
                        echo Html::a($image->getThumbnail('thumbnail', ['class' => 'post-thumbnail']), $post->url, ['class' => 'media-left entry-thumbnail']);
                    }
                    ?>
                    <div class="media-body">
                        <p class="entry-summary">
                            <?= $post->post_excerpt; ?>...
                        </p>
                        <footer class="footer-meta">
                            <h3>
                                <?php
                                $tags = $post->getTerms()->innerJoinWith(['taxonomy'])->andWhere(['taxonomy_slug' => 'tag'])->all();
                                foreach ($tags as $tag) {
                                    echo Html::a($tag->term_name, $tag->url, ['class' => 'badge']) . "\n";
                                }
                                ?>
                            </h3>
                        </footer>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
        <nav id="archive-pagination">
            <?php
            echo LinkPager::widget([
                'pagination'           => $pages,
                'activePageCssClass'   => 'active',
                'disabledPageCssClass' => 'disabled',
                'options'              => [
                    'class' => 'pagination'
                ]
            ]);
            ?>
        </nav>
    <?php endif; ?>
</div>
