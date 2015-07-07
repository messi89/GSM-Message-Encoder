<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 07/07/2015
 * Time: 11:00
 */

header('Content-type: text/plain; charset=utf-8');

include_once('GSMEncoder.php');

if (isset($_POST['message'])) {

    if (get_magic_quotes_gpc())
    {
        $message		= stripslashes(trim($_POST['message']));
    }
    else
    {
        $message		= trim($_POST['message']);
    }

    $gsmEncoder = new GSMEncoder();
    $dataMessage = $gsmEncoder->generateEncodedMessage($message);

    if ($dataMessage === false) {
        echo $gsmEncoder->getErrorMessage();
    } else {
        echo $dataMessage;
    }
}