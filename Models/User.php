<?php

/**
 * Class User
 *
 * @package \\${NAMESPACE}
 */

namespace StockTracker\Models;
use PDO;

class User
{
    public function getUser($username, $password, $db)
    {
        $sql = "SELECT * FROM users where username = :Username";
        $pst = $db->prepare($sql);
        $pst->bindParam(':Username', $username);
        $pst->execute();
        $userinfo = $pst->fetchALL(PDO::FETCH_OBJ);
        $dbpass = $userinfo->password;
        if (password_verify($password, $dbpass))
            return $userinfo;
        else
            return NULL;
    }
    public function addWallet($id, $val1, $val2, $db)
    {
        $sql = "UPDATE users SET wallet = :wallet WHERE id = :id";
        $pst = $db->prepare($sql);
        $finalval = $val1 + $val2;
        $pst->bindParam(':wallet', $finalval);
        $pst->bindParam(':id', $id);
        $count = $pst->execute();
        return $count;
    }
}
