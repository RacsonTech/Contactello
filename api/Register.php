<?php
	$inData = getRequestInfo();

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("INSERT into Users (FirstName,LastName,Login,Password) VALUES(?,?,?,?)");
		$hashValue = HASH('sha256', $inData["Password"], false);
		$stmt->bind_param("ssss",$inData["FirstName"],$inData["LastName"],$inData["Login"], $hashValue );
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError('Duplicate Login account'); // mysql_error(resource $link_identifier = NULL): string
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
