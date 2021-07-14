<?php
use app\assets\AppAsset;
use yii\helpers\VarDumper;
use yii\widgets\ListView;
use yii\grid\GridView;
?>
<div class="banner_area">
	<h2>My Profile</h2>
	<a href="#">Home <span>&gt; My Profile</span></a>
</div>
<section class="space-ptb bg-light rider-dashbord">
	<div class="container">
		<div class="row">
			<?= Yii::$app->controller->renderPartial('user_profile',['model' => $model]);  ?>
			<div class="col-md-9">
				<div class="tab-content bg-blue border-style">
					<div class="profile-edit" id="videos">
						<div class="widget">
							<div class="widget-title bg-primary">
								<h6 class="text-white mb-0">Videos</h6>
							</div>
							<div class="widget-content">
								<div class="row">
									
										<?php
        echo ListView::widget([
            'layout' => "{items}</div><div class=''><div class=''>{pager}</div>",
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'row',
            ],
            'itemOptions' => [
                'tag' => 'div',
                'class' => 'col-lg-4 col-sm-12',
            ],
            'itemView' => '_video_list',
            'emptyText' => 'No Record Found'
        ]);
        ?>
										
										
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
