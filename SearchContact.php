<?php
	if($_SERVER['REQUEST_METHOD'] != "GET")
	{
		exit();
	}

	$searchResults = "";
	$searchCount = 0;
	$limit=30; // set default return limit as 30 records
	$offset = 0; // set default offset as 0
	$Search = ""; // set default search content as null
	$UserID = ""; // set default UserID as null

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

		if(isset($_GET['Search'])){ // when 'Search' is passed from front-end, assign the new value to $Search
			$Search = $_GET['Search'];
		}

		if(isset($_GET['UserID'])){ // when 'UserID' is stored in $_SESSION, assign the new value to $UserID
			$UserID = $_GET['UserID']; 
		}

		$stmt = $conn->prepare("select * from Contacts where (FirstName like ? or LastName like ? or PhoneNumber like ? or Email like ?) and UserID=? LIMIT ? OFFSET ?"); // search "?" in database
		$key = "%" . $Search . "%";
		$stmt->bind_param("sssssii", $key,$key,$key,$key,$UserID,$limit,$offset);
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
