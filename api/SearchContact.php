<?php
	session_start(); // Start the session
	$inData = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;
	$limit=30; // set default return limit as 30 records
	$offset = 0; // set default offset as 0

	$conn = new mysqli("localhost", "test_api", "api123", "ContactDB");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		if(isset($_GET['limit'])){ // when 'limit' is passed from front-end, assign the new value to $limit
			$limit = $_GET['limit'];
		}
		
		if(isset($_GET['offset'])){ // when 'offset' is passed from front-end, assign the new value to $offset
			$offset = $_GET['offset'];
		}

		$stmt = $conn->prepare("select * from Contacts where (FirstName like ? or LastName like ? or PhoneNumber like ? or Email like ?) and UserID=? LIMIT $limit OFFSET $offset"); // search "?" in database
		$key = "%" . $inData["Search"] . "%";
		$stmt->bind_param("sssss", $key,$key,$key,$key, $inData["UserID"]);
		$stmt->execute();

		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"FirstName":"' . $row["FirstName"] . '","LastName":"' . $row["LastName"] . '","PhoneNumber":"' . $row["PhoneNumber"]. '","Email":"' . $row["Email"]. '","ContactID":"' . $row["ContactID"] . '"}';
		}

		$_SESSION['UserID'] = $row['UserID']; // Set session variables
		$_SESSION['FirstName'] = $row['FirstName'];
		$_SESSION['LastName'] = $row['LastName'];

		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
