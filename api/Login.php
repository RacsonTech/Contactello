
<?php
	session_start();
	$inData = getRequestInfo();

	$UserID = 0;
	$FirstName = "";
	$LastName = "";

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT UserID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["Login"], HASH('sha256', $inData["Password"], false));
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{	
			$_SESSION['UserID'] = $row['UserID'];
			$_SESSION['FirstName'] = $row['FirstName'];
			$_SESSION['LastName'] = $row['LastName'];
			returnWithInfo( $row['FirstName'], $row['LastName'], $row['UserID'] );
		}
		else
		{
			returnWithError("No Records Found");
		}
			
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
		$retValue = '{"UserID":0,"FirstName":"","LastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $FirstName, $LastName, $UserID )
	{
		$retValue = '{"UserID":' . $UserID . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>