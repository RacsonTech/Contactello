<?php
	$inData = getRequestInfo();
	
	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	}
    	else if (strlen($inData["FirstName"]) > 50 || strlen($inData["FirstName"]) < 1){
		echo "Invalid input： FirstName should be within 1~50 characters";
	}
    else if (strlen($inData["LastName"]) > 50 || strlen($inData["Email"]) > 50  || strlen($inData["PhoneNumber"]) > 50 ){
        echo "Invalid input： LastName, Email, and PhoneNumber should be within 1~50 characters";
    }
	else
	{
		$stmt = $conn->prepare("INSERT into Contacts (UserID,FirstName,LastName,PhoneNumber,Email) VALUES(?,?,?,?,?)");
		$stmt->bind_param("sssss",  $inData["UserID"], $inData["FirstName"],  $inData["LastName"], $inData["PhoneNumber"], $inData["Email"]);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
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
