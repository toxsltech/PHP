<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\commands;

use app\base\TDefaultData;
use app\components\TConsoleController;
use app\models\EmailQueue;
use app\models\File;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;

class ClearController extends TConsoleController
{

    public function actionChar()
    {
        die('Hi');
        self::log('actionChar');
        $name = \Yii::$app->db->createCommand()
            ->setSql("select database()")
            ->queryScalar();

        \Yii::$app->db->createCommand()
            ->setSql(" ALTER DATABASE " . $name . " CHARACTER SET utf8 COLLATE utf8_general_ci")
            ->execute();
        foreach (\Yii::$app->db->schema->tableNames as $table) {

            self::log("character " . $table);
            \Yii::$app->db->createCommand()
                ->setSql("ALTER TABLactionDbE
                $table
                CONVERT TO CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci")
                ->execute();
        }
    }

    public function actionIndex()
    {
        $this->actionRuntime();
        $this->actionAsset();
    }

    /**
     * Add default data
     */
    public function actionDefault()
    {
        $this->actionRuntime();
        $this->actionAsset();
        $this->actionUploads();
        $this->actionDb();
        TDefaultData::data();
    }

    public static function actionRuntime($delete = false)
    {
        $dir = Yii::getAlias('@runtime');
        self::log('cleaning Runtime :' . $dir);
        if (is_dir($dir)) {
            if ($delete) {
                FileHelper::removeDirectory($dir);
                return;
            }

            $objects = FileHelper::findFiles($dir);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (! FileHelper::unlink($object)) {
                    self::log('Unlink Error:' . $object);
                }
            }
            $objects = FileHelper::findDirectories($dir);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
            }
            self::log('Runtime cleaned');
        }
    }

    public function actionAsset($delete = false)
    {
        $assetsDirs = FileHelper::normalizePath(DB_CONFIG_PATH . '../../assets/');
        self::log('cleaning Assets :' . $assetsDirs);
        if (is_dir($assetsDirs)) {

            if ($delete) {
                FileHelper::removeDirectory($assetsDirs);
                return;
            }

            $objects = FileHelper::findFiles($assetsDirs, [
                'recursive' => true
            ]);

            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
                if (! FileHelper::unlink($object)) {
                    self::log('Unlink Error:' . $object);
                }
            }
            $objects = FileHelper::findDirectories($assetsDirs);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
            }

            self::log('Assets cleaned');
        }
    }

    public function actionUploads()
    {
        $uploadDirs = UPLOAD_PATH;
        if (is_dir($uploadDirs)) {

            FileHelper::removeDirectory($uploadDirs);
        }
        self::log('Uploads cleaned');
    }

    public function actionDb($dontSkip = 0)
    {
        self::log('clean db dontSkip:' . $dontSkip);

        $skip_tables = [
            'tbl_user_role',
            'tbl_user',
            'tbl_user_detail',
            'tbl_subscription_plan',
            'tbl_social_provider',
            'tbl_product_video',
            'tbl_product',
            'tbl_payment_gateway',
            'tbl_package',
            'tbl_habbit',
            'tbl_faq',
            'tbl_cities',
            'tbl_countries',
            'tbl_page',
            'tbl_category'
        ];
        \Yii::$app->db->createCommand()
            ->checkIntegrity(false)
            ->execute();

        foreach (\Yii::$app->db->schema->tableNames as $table) {
            if (! $dontSkip && in_array($table, $skip_tables)) {
                continue;
            }
            self::log("Cleaning " . $table);
            \Yii::$app->db->createCommand()
                ->truncateTable($table)
                ->execute();
        }
        \Yii::$app->db->createCommand()
            ->checkIntegrity(true)
            ->execute();

        FileHelper::removeDirectory(UPLOAD_PATH);
    }

    public function actionEmailQueue($m = 12)
    {
        $query = EmailQueue::find()->where([
            'state_id' => EmailQueue::STATE_SENT
        ])
            ->andWhere([
            '<',
            'date_sent',
            date('Y-m-d H:i:s', strtotime("-$m months"))
        ])
            ->orderBy('id asc');

        EmailQueue::log("Cleaning up emails : " . $query->count());
        foreach ($query->each() as $email) {
            EmailQueue::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($m == 0) {
            EmailQueue::truncate();
        }
    }

    public function actionFiles($id = 0, $limit = 0)
    {
        $query = File::find()->where([
            '>',
            'id',
            $id
        ])->orderBy('id asc');

        if ($limit > 0) {
            $query->limit($limit);
        }
        File::log("Cleaning up files : " . $query->count());
        foreach ($query->each() as $file) {

            try {

                $file->rename();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }
}

