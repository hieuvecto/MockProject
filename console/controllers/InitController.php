<?php 

namespace console\controllers;

use yii\console\Controller;
use common\models\User;

class InitController extends Controller
{
   
    public function actionIndex()
    {   
        $email = 'Đặt sân tại chỗ';
        $password = '123abc';
        if (User::findByEmail($email) !== null) {
            echo "Self create booking user existed.\n";
            return;
        }

        $user = new User();
        $user->user_id = 99;
        $user->email = $email;
        $user->password = $password;
        $user->phone = '00';

        if ($user->save()) {
            echo 'Self create booking user saved.';
        }
        echo $user->getErrors();
    }
}