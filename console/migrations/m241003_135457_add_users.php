<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m241003_135457_add_users
 */
class m241003_135457_add_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = "User";
        $user->email = "user@example.com";
        $user->setPassword("User");
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        
        return $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        User::deleteAll(["username" => ["User"]]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241003_135457_add_users cannot be reverted.\n";

        return false;
    }
    */
}
