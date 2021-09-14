<?php
	$inData = getRequestInfo();

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else if( strlen($inData["FirstName"]) > 50 || strlen($inData["FirstName"]) < 1 || strlen($inData["LastName"]) > 50 || strlen($inData["LastName"]) < 1 
			|| strlen($inData["Login"]) > 50 || strlen($inData["Login"]) < 1  ) 
	{
		echo "Invalid input： FirstName, LastName and Login should be within 1~50 characters";
	} 
	else if ( strlen($inData["Password"]) >32 || strlen($inData["Password"]) < 6 )
	{
		echo "Invalid input： Password should be within 6~32 characters";
	}
	else 
	{
		$stmt = $conn->prepare("INSERT into Users (FirstName,LastName,Login,Password) VALUES(?,?,?,?)");
		$hashValue = HASH('sha256', $inData["Password"], false);
		$stmt->bind_param("ssss",$inData["FirstName"],$inData["LastName"],$inData["Login"], $hashValue );
		$stmt->execute();

		returnWithError(mysqli_error($conn)); // mysql_error(resource $link_identifier = NULL): string // get the error message from database

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
