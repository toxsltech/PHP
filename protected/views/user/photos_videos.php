<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$pagename = "Photos-videos";
?>
<div id="main-wrapper">
	<div class="main-content container-fluid">
		<div class="row">
			<!-- sticky-parent -->
			<div id="dash-sidebar" class="col-lg-4 col-xl-3 p-0">
        <?= Yii::$app->controller->renderPartial('side_nav',['model'=> $model]);?> 
      					</div>
			<div id="dash-sidebarclass" class="col-lg-8 col-xl-9 p-0">
				<div class="dash-content">
					<h3 class="mr-3">Photos & Videos</h3>
					<div class="row align-items-center mt-3">
						<div class="col-lg-8 mb-4 mb-lg-0">
							<div class=" d-flex align-items-center">
								<ul class="nav nav-tabs" id="themeTab" role="tablist">
									<li class="nav-item"><a class="nav-link active" id="Videos-tab"
										data-toggle="tab" href="#Videos" role="tab"
										aria-controls="Videos" aria-selected="true">Videos</a></li>
									<li class="nav-item"><a class="nav-link" id="Photos-tab"
										data-toggle="tab" href="#Photos" role="tab"
										aria-controls="Photos" aria-selected="false">Photos</a></li>
								</ul>
							</div>
						</div>
						<div class="col-md-4 text-md-right">
							<a class="ml-auto font-18" id="add-photos"
								href="<?=Url::toRoute(['show/add-media?type=Photos'])?>">+ Add
								New Photos</a> <a class="ml-auto font-18" id="add-videos"
								href="<?=Url::toRoute(['show/add-video?type=Videos'])?>">+ Add
								New Videos</a>
						</div>
						<div class="col-lg-12 mt-30">
							<div class="tab-content" id="VideosTabContent">
								<div class="tab-pane fade active show" id="Videos"
									role="tabpanel" aria-labelledby="Videos-tab">
									<div class="boxDesign">
										<div id="videos-main" class="row">
                                           <?php
                                        foreach ($video->each() as $videos) {
                                            $asset = AppAsset::register($this);
                                            $filepath = $asset->baseUrl . '/file/files?file=' . $videos;
                                            ?>
                                            <div
												class="col-sm-6 col-md-4 col-xl-3 col-xxl-five mb-30">
												<video width="100%" height="200" controls>
													<source src="<?php echo $filepath;?>" type="video/mp4">
												</video>
											</div>
                                     			<?php
                                        }
                                        ?>
            						</div>
									</div>
								</div>
								<div class="tab-pane fade" id="Photos" role="tabpanel"
									aria-labelledby="Photos-tab">
									<div class="boxDesign">
										<div id="phots-main" class="row">
                       <?php foreach ($photos->each() as $photo) {?>
                      <div
												class="col-sm-6 col-md-4 col-xl-3 col-xxl-five mb-30">
                       <?=$photo->displayImage($photo->name, ['class' => 'img-fluid'], 'default.png');?>
                      </div>
                       <?php }?>
                    </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
   $("#add-photos").hide();
    $("#Photos-tab").on("click", function(){
     $("#add-photos").show();
     $("#add-videos").hide();
    
  });
   $("#Videos-tab").on("click", function(){
     $("#add-videos").show();
     $("#add-photos").hide();
     });
});
</script>