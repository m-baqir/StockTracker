<?php
session_start();

use StockTracker\Models\{Database, User, Shares};

$userid = $_SESSION["userid"];
$userwallet = $_SESSION["userwallet"];
$dbcon = Database::getDb();
$s = new Shares();
//gets details of shares owned by user from the bridge table
$shareids = $s->getSharesByUserId($userid, $dbcon);
//gets all share details using the share id from the shares table
$sharedetails = $s->getShareDetails($shareids, $dbcon);
//get all shares and their information
$allshares = $s->getAllShares($dbcon);

if (isset($_POST["addwallet"])) {
    $newwallet = $_POST["wallet"];
    $u = new User();
    $count = $u->addWallet($userid, $userwallet, $newwallet, $dbcon);

    if ($count)
        header("Location: portfolio.php");
    else
        echo 'problem adding to the wallet';
}
//buying shares
if (isset($_POST["addshare"])) {
    if (!empty($_POST["share"])) {
        $selectedshare = $_POST["share"];
        $sharevalue = $_POST["sid"];
        $unitspurchased = $_POST["units"];
        $s->BuyShares($userid, $selectedshare, $unitspurchased, $sharevalue, $dbcon);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Portfolio Page</title>
</head>

<body>
    <!--your current wallet and ability to add more to it-->
    <p>Amount in your wallet currently: <?= $userwallet ?></p>
    <form method="post">
        <label for="wallet">Add to your wallet</label>
        <input type="number" name="wallet" id="wallet" />
        <input type="submit" name="addwallet" value="Submit" />
    </form>
    <p>Your current shares:</p>
    <!--Table listing your current portfolio, shares and relative information-->
    <table>
        <tr>
            <th>Share Name</th>
            <th>Share Value</th>
            <th>Units Bought</th>
            <th>Buy-in Value</th>
        </tr>
        <?php foreach ($sharedetails as $sharedet) { ?>
            <tr>
                <td><?= $sharedet->name ?></td>
                <td><?= $sharedet->value ?></td>
                <?php foreach ($shareids as $share) { ?>
                    <td><?= $share->shares_bought ?></td>
                    <td><?= $share->buy_value ?></td>
            </tr>

    <?php }
            } ?>
    </table>
    <!--Buying a share and adding to the db-->
    <!--TODO: I am missing the functionality that would subtract money from the wallet once you have bought a share. that needs to be done at the db level-->
    <form method="post">
        <label for="share">Choose a share to add to your portfolio:</label>
        <select name="share">
            <?php foreach ($allshares as $share) { ?>
                <option value="<?= $share->id ?>"><?= $share->name . '@' . $share->value ?></option>
                <input type="hidden" name="sid" value="<?= $share->value ?>" />
            <?php } ?>
        </select>
        <label for="units">How many units do you wish to purchase:</label>
        <input type="number" name="units" />
        <input type="submit" name="addshare" />
    </form>
    <!--TODO: incorporate sell functionality. I need to add a  sell column in the bridge table for when the user sells a share and that then check whether that sell was done at a loss or profit and then reflect it in the wallet accordingly-->
</body>

</html>