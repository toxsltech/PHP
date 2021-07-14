<?php
// use app\components\useraction\UserAction;
use app\models\User;
use yii\helpers\Html;
use app\components\useraction\UserAction;
use kartik\rating\StarRating;

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
        echo Html::img($model->getImageUrl('150'), [
            'class' => 'img-responsive',
            'alt' => $model,
            'width' => '150',
            'height' => '150'
        ])?>
            </p>
            <?php
    } else {
        echo Html::img([
            'themes/new/img/user.png'
        ], [
            'class' => 'img-fluid',
            'alt' => $model,
            'width' => '200',
            'height' => '200'
        ]);
    }
    ?>
     
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
                       /*'password',
                       'date_of_birth:date',
                            'gender',
                            'about_me',
                            'contact_no',
                            'address',
                            'latitude',
                            'longitude',
                            'city',
                            'country',
                            'zipcode',
                            'profile_file',
                            'tos:boolean',*/
                    
                    /*'language',
                    'rating',*/
                    [
                        'attribute' => 'role_id',
                        'format' => 'raw',
                        'value' => $model->getAdminRole()
                    ],
                       /* [
                           'attribute' => 'state_id',
                           'format' => 'raw',
                           'value' => $model->getStateBadge()
                       
                       ], */
                            /* [
                                    'attribute' => 'type_id',
                                    'value' => $model->getType ()
                            ], 
                            'last_visit_time:datetime',
                    'last_action_time:datetime',
                    'last_password_change:datetime',*/
                    // 'login_error_count',
                    /* 'activation_key', */
                    // 'timezone',
                    'created_on:datetime'
                ]
            ])?>
           
         </div>
						</div>
					</div>
				</div>

			</div>
        
   </div>
	</div>
</div>
</div>
