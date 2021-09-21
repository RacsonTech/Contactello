
<?php
	$inData = getRequestInfo();

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else if( strlen($inData["Login"]) > 50 || strlen($inData["Login"]) < 1  ) 
	{
	echo "Invalid input： Login should be within 1~50 characters";
	} 
	else if ( strlen($inData["Password"]) >32 || strlen($inData["Password"]) < 6 )
	{
	echo "Invalid input： Password should be within 6~32 characters";
	}
	else
	{
		$stmt = $conn->prepare("SELECT UserID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["Login"], HASH('sha256', $inData["Password"], false));
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{	
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
		$retValue = '{"UserID":"","FirstName":"","LastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $FirstName, $LastName, $UserID )
	{
		$retValue = '{"UserID":' . $UserID . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
