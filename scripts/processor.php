<?php
/**
 * This script handles the form processing
 *
 * PHP version 7.2
 *
 * @category Registration
 * @package  Registration
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  GPL https://opensource.org/licenses/gpl-license
 * @link     https://stbensonimoh.com
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// echo json_encode(array($_POST, $_FILES));

// Pull in the required files
require '../config.php';
require './DB.php';
require './Notify.php';

// Capture the post data coming from the form
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['full_phone'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$stateOfOrigin = $_POST['stateOfOrigin'];
$position = $_POST['position'];
$tempCV = $_FILES['cvUpload']['tmp_name'];
$tempCoverLetter = $_FILES['coverLetterUpload']['tmp_name'];
$cvUploadFileName = "CV-" . $_FILES['cvUpload']['name'];
$cvUploadFileType = $_FILES['cvUpload']['type'];
$cvUploadFileSize = $_FILES['cvUpload']['size'];
$coverLetterUploadFileName = "CoverLetter-" . $_FILES['coverLetterUpload']['name'];
$coverLetterUploadFileType = $_FILES['coverLetterUpload']['type'];
$coverLetterUploadFileSize = $_FILES['coverLetterUpload']['size'];

//Upload the Files
move_uploaded_file($tempCV, "../uploads/". $cvUploadFileName);
move_uploaded_file($tempCoverLetter, "../uploads/". $coverLetterUploadFileName);

$details = array(
    "firstName" => $firstName,
    "lastName" => $lastName,
    "email" => $email,
    "phone" => $phone,
    "address" => $address,
    "gender" => $gender,
    "stateOfOrigin" => $stateOfOrigin,
    "position" => $position,
    "cvUploadFileName" => $cvUploadFileName,
    "coverLetterUploadFileName" => $coverLetterUploadFileName
);

$db = new DB($host, $db, $username, $password);

$notify = new Notify($emailHost, $emailUsername, $emailPassword, $SMTPDebug, $SMTPAuth, $SMTPSecure, $Port);

    // Insert the user into the database
    $db->getConnection()->beginTransaction();
    $db->insertUser("applicant", $details);

        $name = $firstName . ' ' . $lastName;
        // Send Email
        require './emails.php';
        // Send Email
        $notify->viaEmail("no-reply@leadfrontierpartners.com", "Lead Frontier Partners", $email, $name, $emailBody, "Application Submission Successful!");

        $db->getConnection()->commit();

        echo json_encode("success");