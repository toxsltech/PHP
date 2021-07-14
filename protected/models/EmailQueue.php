<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models;

/**
 * This is the model class for table "tbl_email_queue".
 *
 * @property integer $id
 * @property string $from_email
 * @property string $to_email
 * @property string $message
 * @property string $subject
 * @property string $date_published
 * @property string $last_attempt
 * @property string $date_sent
 * @property integer $attempts
 * @property integer $state_id
 * @property integer $email_id
 * @property integer $project_id
 *
 */
class EmailQueue extends \app\components\TEmailQueue
{

    // public static function add($args = [], $trySendNow = true)
    // {
    // $mail = parent::add($args, false);
    // if ($mail != false && $mail->state_id == self::STATE_SENT) {
    // // Delete emails alreday sent
    // /// $mail->delete();
    // }
    // return $mail;
    // }
}