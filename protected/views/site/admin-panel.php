<?php
use app\models\User;
use app\modules\emailreader\models\EmailAccount;
/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );

?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
<div class="alert alert-danger">
    <?php echo Yii::$app->session->getFlash('error')?>
</div>
<?php endif; ?>
<div class="wrapper">

	<div class="page-head">

		<h1>Hello</h1>
		<strong>
                    <?php echo Yii::$app->user->identity->email;?></strong>
		<br> <span class="sub-title">Welcome to your <b><?php echo User::getRoleOptions(Yii::$app->user->identity->role_id);?></b>
			dashboard
		</span>

	</div>
	<div class="wrapper">
		<!--state overview start-->
		<div class="row state-overview">
			<div class="col-lg-3 col-sm-6">
				<section class="panel purple">
					<div class="symbol">
						<i class="fa fa-user"></i>
					</div>
					<div class="value white">
						<h1 class="timer" data-from="0" data-to="320" data-speed="1000">
						<?php echo User::find()->count();?>
					</h1>
						<p>Total Users</p>
					</div>
				</section>
			</div>

			<div class="row state-overview">
				<div class="col-lg-3 col-sm-6">
					<section class="panel purple">
						<div class="symbol">
							<i class="fa fa-envelope"></i>
						</div>
						<div class="value white">
							<h1 class="timer" data-from="0" data-to="320" data-speed="1000">
						<?php  echo  EmailAccount::find()->count();?>
					</h1>
							<p>Total EmailAccount</p>
						</div>
					</section>
				</div>


				<div class="col-lg-12 col-sm-6">
					<section class="panel panel-body">
				
		<?php
/*
 * $arr = User::graph ();
 *
 * // $email = EmailAccount::graph ();
 *
 * echo Highcharts::widget ( [
 * 'options' => [
 * 'title' => [
 * 'text' => Yii::t ( 'app', 'User Overview' )
 * ]
 * // 'text' => Yii::t('app',' EmailAccount Overview')
 * ,
 * 'xAxis' => [
 * 'categories' => [
 * Yii::t ( 'app', 'Jan' ),
 * Yii::t ( 'app', 'Feb' ),
 * Yii::t ( 'app', 'Mar' ),
 * Yii::t ( 'app', 'Apr' ),
 * Yii::t ( 'app', 'May' ),
 * Yii::t ( 'app', 'Jun' ),
 * Yii::t ( 'app', 'Jul' ),
 * Yii::t ( 'app', 'Aug' ),
 * Yii::t ( 'app', 'Sep' ),
 * Yii::t ( 'app', 'Oct' ),
 * Yii::t ( 'app', 'Nov' ),
 * Yii::t ( 'app', 'Dec' )
 * ]
 * ],
 * 'yAxis' => [
 * 'title' => [
 * 'text' => ''
 * ]
 * ],
 *
 * 'series' => [
 * [
 * 'name' => Yii::t ( 'app', 'Total User' ),
 * 'data' => [
 * intval ( $arr ['jan'] ),
 * intval ( $arr ['feb'] ),
 * intval ( $arr ['mar'] ),
 * intval ( $arr ['apr'] ),
 * intval ( $arr ['may'] ),
 * intval ( $arr ['jun'] ),
 * intval ( $arr ['jul'] ),
 * intval ( $arr ['aug'] ),
 * intval ( $arr ['sep'] ),
 * intval ( $arr ['oct'] ),
 * intval ( $arr ['nov'] ),
 * intval ( $arr ['dec'] )
 * ]
 * ],
 * [
 * 'name' => Yii::t ( 'app', 'Total EmailAccount' ),
 * 'data' => [
 * intval ( $email ['jan'] ),
 * intval ( $email ['feb'] ),
 * intval ( $email ['mar'] ),
 * intval ( $email ['apr'] ),
 * intval ( $email ['may'] ),
 * intval ( $email ['jun'] ),
 * intval ( $email ['jul'] ),
 * intval ( $email ['aug'] ),
 * intval ( $email ['sep'] ),
 * intval ( $email ['oct'] ),
 * intval ( $email ['nov'] ),
 * intval ( $email ['dec'] )
 * ]
 * ]
 * ]
 *
 * ]
 * ] );
 */
?>
		</section>
				</div>


				<!--  
		<div class="col-lg-3 col-sm-6">
			<section class="panel ">
				<div class="symbol purple-color">
					<i class="fa fa-rss"></i>
				</div>
				<div class="value gray">
					<h1 class="purple-color timer" data-from="0" data-to="123"
						data-speed="1000">
						<?php // echo Spot::find()->count();?>
					</h1>
					<p>Total Spot</p>
				</div>
			</section>
		</div>
		<div class="col-lg-3 col-sm-6">
			<section class="panel green">
				<div class="symbol ">
					<i class="fa fa-laptop"></i>
				</div>
				<div class="value white">
					<h1 class="timer" data-from="0" data-to="432" data-speed="1000">
						<?php // echo CarModel::find()->count();?>
					</h1>
					<p>Total CarModels</p>
				</div>
			</section>
		</div>
		<div class="col-lg-3 col-sm-6">
			<section class="panel panel-body">
				<div class="symbol green-color">
					<i class="fa fa-list"></i>
				</div>
				<div class="value gray">
					<h1 class="green-color timer" data-from="0" data-to="2345:)"
						data-speed="3000">
						<?php // echo SpotType::find()->count();?>
					</h1>
					<p>Total SpotTypes</p>
					</div>
			</section>
		</div>-->
				<div class="clearfix"></div>
				<!-- <div class="panel panel-body">
	<div class="panel-body"> -->
		<?php
/*
 * $query = User::find();
 * $dataProvider = new ActiveDataProvider ( [
 * 'query' => $query
 * ] );
 * $dataProvider->pagination->pagesize =5;
 * echo TGridView::widget ( [
 * 'dataProvider' => $dataProvider,
 * 'summary'=>'',
 *
 * 'columns' => [
 * [
 * 'class' => 'yii\grid\SerialColumn'
 * ],
 * 'full_name',
 * 'email:email',
 * 'contact_no',
 *
 * [
 * 'class' => 'yii\grid\ActionColumn'
 * ]
 * ]
 * ] );
 */
?>
	
			
<!-- 	</div>
	</div>  -->
			</div>
		</div>
	</div>
	<!--state overview end-->
</div>
