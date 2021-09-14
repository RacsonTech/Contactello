<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL); // error report for testing

	if($_SERVER['REQUEST_METHOD'] != "DELETE")
	{
		exit();
	}

	$UserID = ""; // set default UserID as null
	$ContactID = ""; // set default ContactID as null

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else if ( isset($_GET['UserID']) && isset($_GET['ContactID']) )
	{
		$UserID = $_GET['UserID']; 
		$ContactID = $_GET['ContactID'];

		$stmt = $conn->prepare("DELETE FROM Contacts WHERE ContactID=? && UserID=?");
		$stmt->bind_param("ss", $ContactID, $UserID);
		$stmt->execute();

		returnWithError("");

		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
