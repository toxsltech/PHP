<!DOCTYPE html>
<html>
   <head>
      <title>Yii2 base </title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
      <style type="text/css">
      @font-face {
        
         font-family: 'OpenSans-Regular';
          src: url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/OpenSans-Regular.ttf),
          url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/opensans-regular.woff),
          url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/opensans-regular.woff2);
                  }
         font-weight: normal;
         font-style: normal;
         
         @media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
         td[class='column'],
         td[class='column'] { 
         float: left !important; 
         display: block !important;
         border-right: 0 !important; width: 100%
         }
         td[class='td'] { 
         width: 100% !important; 
         min-width: 100% !important; 
         }
         table  {
         margin: auto;
         width: 100%;
         }
         }
         .connect:before
         {
         content: '';
         width: 50px;
         height: 1px;
         display: inline-block;
         background-color: #151514;
         margin: 0 15px;
         -webkit-transform: translateY(-4px);
         -ms-transform: translateY(-4px);
         transform: translateY(-4px);
         }
         .connect:after
         {
         content: '';
         width: 50px;
         height: 1px;
         display: inline-block;
         background-color: #151514;
         margin: 0 15px;
         -webkit-transform: translateY(-4px);
         -ms-transform: translateY(-4px);
         transform: translateY(-4px);
         }
      </style>
   </head>
   <body style="background-color:#e7f5f0;font-family: 'OpenSans-Regular';;color: #202020;font-size: 15px;line-height: 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0px">
         <tr>
            <td align="center">
               <table cellspacing="0" cellpadding="0" style="background:#fff;box-shadow: 2px 2px 20px 4px rgba(0, 0, 0, 0.07); -webkit-box-shadow: 2px 2px 20px 4px rgba(0, 0, 0, 0.07);max-width: 650px;width: 100%">
                  <!--- header end-->
                  <tr>
                     <td style="padding:20px 30px;background: #fff;border-bottom:1px solid #daebe1" align="center">
                         <a href="#0" style="color: #28a745;text-decoration: none">
                               <h1 style="font-weight: 600;color:#28a745 ;font-size:25px;margin:0px;text-transform: uppercase;"><?php echo \yii::$app->name?></h1>
                           </a>
                        
                     </td>
                  </tr>
                  <!--- header end-->