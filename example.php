<?php
namespace console\controllers;

use yii;
use yii\console\Controller;

use MySQLHandler\MySQLHandler;

/**
 * This is a example for Monolog, which can't be used directly
 * Because this example is used in Yii2 console
 */
class MonologController extends Controller
{
    /**
     * test
     */
    public function actionGo()
    {
        // get the Yii pdo,You should change it for example:$dsn = 'host:localhost;dbname:log' etc
        $pdo = Yii::$app->db->getMasterPdo();

        // Create MysqlHandler
        $addColumns = [
            'user_id',
            'messages',
            'other_one',
            'other_two'
        ];
        $description = [
            'user_id' => 'INTEGER UNSIGNED NOT NULL DEFAULT 0',
            'other_one' => 'VARCHAR(32) NOT NULL DEFAULT ""',
        ];
        $mySQLHandler = new MySQLHandler($pdo, Yii::$app->db->tablePrefix . 'test_log', $addColumns, \Monolog\Logger::DEBUG, true, $description);

        // Create logger
        $context = 'test';
        $logger = new \Monolog\Logger($context);
        $logger->pushHandler($mySQLHandler);

        // Now you can use the logger, and further attach additional information
        try {
            $res = $logger->addWarning("This is a great message, woohoo!", ['user_id' => 245, 'messages' => 'woohoo,cool', 'other_one' => 'yes', 'other_two' => 'no']);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
