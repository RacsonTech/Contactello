<?php
	$inData = getRequestInfo();

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	}
    else if (strlen($inData["FirstName"]) > 50 || strlen($inData["LastName"]) > 50 || strlen($inData["FirstName"]) < 1 || strlen($inData["LastName"]) < 1){
        echo "Invalid input： FirstName, LastName should be within 1~50 characters";
    }
	else
	{
		$stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, PhoneNumber=?,Email=? WHERE ContactID=?");
		$stmt->bind_param("sssss",$inData["FirstName"],$inData["LastName"],$inData["PhoneNumber"],$inData["Email"],$inData["ContactID"]);
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