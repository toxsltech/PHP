<?php
// use app\components\useraction\UserAction;
use app\models\User;
use yii\helpers\Html;
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\User */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card mb-4">
   <?php
echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>
</div>
	<div class="content-section clearfix">
		<div class="widget light-widget">
			<div class="user-view">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
       <?php
    if (! empty($model->profile_file)) {
        ?>
            <?php
        echo Html::img($model->getImageUrl(), [
            'class' => 'img-responsive',
            'alt' => $model,
            'width' => '100%',
            'height' => '100'
        ])?><br /> <br />
								<p>
               <?=Html::a('Download image ', $model->getImageUrl(), ['class' => 'btn btn-success'])?>
            </p>
            <?php
    } else {
        ?>
            <img id="profile_file" class="w-100"
									src="<?=$this->theme->getUrl('images/default.png')?>" alt="img">
            <?php } ?>
         	</div>
							<div class="col-md-10">     
            <?php
            echo \app\components\TDetailView::widget([
                'model' => $model,

                'options' => [
                    'class' => 'table table-bordered'
                ],
                'attributes' => [
                    'id',
                    // 'full_name',
                    'email:email',
                    /* [
                     'attribute' => 'state_id',
                     'format' => 'raw',
                     'value' => $model->getStateBadge()
                        
                     ], */
                       //'password',
                       'date_of_birth:date',
                    [
                        'attribute' => 'gender',
                        'format' => 'raw',
                        'value' => $model->getGender()
                    ],

                    // 'about_me',
                    'contact_no',
                    // 'address',
                    // 'latitude',
                    // 'longitude',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    // 'profile_file',
                    // 'tos:boolean',

                    [
                        'attribute' => 'role_id',
                        'format' => 'raw',
                        'value' => $model->getRole()
                    ],
                    [
                        'attribute' => 'Height',
                        'value' => isset($model->userDetail->user_height)?$model->userDetail->user_height:''
                    ],
                    [
                        'attribute' => 'Weight',
                        'value' => isset($model->userDetail->weight)?$model->userDetail->weight:'' 
                    ],
                    [
                        'attribute' => 'Eating Habbits',
                        'value' =>  isset($model->userDetail->eating_habits)?$model->userDetail->eating_habits:''
                    ],
                    [
                        'attribute' => 'Eating Counts',
                        'value' => isset($model->userDetail->eat_count)?$model->userDetail->eat_count:'' 
                    ],
                    [
                        'attribute' => 'Description',
                        'value' => isset($model->userDetail->description)?$model->userDetail->description:'' 
                    ],
                    [
                        'attribute' => 'Subscription Plan',
                        'value' =>  isset($model->userDetail->subscription->title)?$model->userDetail->subscription->title:'' 
                    ],
                    [
                        'attribute' => 'Subscription Validity',
                        'value' => isset($model->userDetail->subscription->validity)?$model->userDetail->subscription->validity:'' 
                    ],
                    [
                        'attribute' => 'state_id',
                        'format' => 'raw',
                        'value' => $model->getStateBadge()
                    ],
                            /* [
                                    'attribute' => 'type_id',
                                    'value' => $model->getType ()
                            ], */
                            /*'last_visit_time:datetime',
                    'last_action_time:datetime',
                    'last_password_change:datetime',*/
                    // 'login_error_count',
                    /* 'activation_key', */
                    // 'timezone',
                    'created_on:datetime',

                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ])?>
           
         </div>
						</div>
					</div>
				</div>
    
     <?php if( \yii::$app->user->id != $model->id){?>
             <?php
            echo UserAction::widget([
                'model' => $model,
                'attribute' => 'state_id',
                'states' => $model->getStateOptions(),
                'visible' => User::isAdmin()
            ]);
            ?>
            <?php }?>
         
      <div class="card">
					<div class="card-body">
            <?php
            $this->context->startPanel();

            $this->context->addPanel('LoginHistories', 'loginHistories', 'LoginHistory', $model);
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model);
            $this->context->addPanel('Files', 'files', 'File', $model);
            $this->context->addPanel('Comments', 'comments', 'Comment', $model);
            $this->context->endPanel();
            ?>
         </div>
				</div>
      
            <?php echo  \app\modules\comment\widgets\CommentsWidget::widget(['model'=>$model]); ?>
        
   </div>
		</div>
	</div>
</div>
