<?php

namespace StockTracker\Models;

use PDO;

/**
 * Class Shares
 *
 * @package \StockTracker\Models
 */
class Shares
{
    public function getAllShares($db)
    {
        $sql = "SELECT * FROM shares";
        $pst = $db->prepare($sql);
        $pst->execute();
        return $pst->fetchALL(PDO::FETCH_OBJ);
    }
    public function getSharesByUserId($id, $db)
    {
        $sql = "SELECT * FROM usersxshares WHERE user_id = :Userid";
        $pst = $db->prepare($sql);
        $pst->bindParam(':Userid', $id);
        $pst->execute();
        return $pst->fetchALL(PDO::FETCH_OBJ);
    }

    public function getShareDetails($id, $db)
    {
        $sql = "SELECT * FROM shares WHERE id = :Id";
        $pst = $db->prepare($sql);
        $pst->bindParam(':Id', $id);
        $pst->execute();
        return $pst->fetchALL(PDO::FETCH_OBJ);
    }

    public function BuyShares($id, $shareid, $units, $buyvalue, $db)
    {
        $sql = "INSERT INTO usersxshares (user_id,share_id,shares_bought,buy_value) VALUES (:id,:id2,:unit,:val)";
        $pst = $db->prepare($sql);
        $pst->bindParam(':id', $id);
        $pst->bindParam(':id2', $shareid);
        $pst->bindParam(':unit', $units);
        $pst->bindParam(':val', $buyvalue);
        $count = $pst->execute();
        return $count;
        //TODO: I need to add another sql query where after buying shares the amount that was bought gets subtracted from the wallet
    }
}
