<?php
?>

<?php
echo tugmaks\RssFeed\RssReader::widget([
    'channel' => 'http://www.opec.org/opec_web/en/pressreleases.rss',
    'itemView' => 'item', // To set own viewFile set 'itemView'=>'@frontend/views/site/_rss_item'. Use $model var to access item properties
    'wrapTag' => 'div',
    'wrapClass' => 'rss-wrap'
]);


