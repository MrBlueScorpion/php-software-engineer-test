<?php
namespace SoftwareEngineerTest;

// Question 1a

$DB_HOST = 'localhost';
$DB_NAME = 'test';
$DB_USER = 'test';
$DB_PASS = 'test';

// write your sql to get customer_data here
try {
	/**
	 * Create database connection with PDO
	 */
	$pdo = new \PDO(
		"mysql:host=$DB_HOST;dbname=$DB_NAME",
		"$DB_USER",
		"$DB_PASS"
	);

	$sql = "SELECT c.customer_id, c.username, c.first_name, c.last_name, IFNULL(co.occupation_name, 'un-employed') AS occupation_name
					FROM customer c
					LEFT JOIN customer_occupation co ON c.customer_occupation_id = co.customer_occupation_id ";

	if (isset($_GET['occupation_name']) && ! empty($_GET['occupation_name'])) {
		if ($_GET['occupation_name'] == 'un-employed') {
			$sql .= "WHERE c.customer_occupation_id IS NULL";
		} else {
			$sql .= "WHERE co.occupation_name = :occupation_name";
		}
	}

	$sth = $pdo->prepare($sql);
	$sth->bindParam(":occupation_name", $_GET['occupation_name']);
	$sth->execute();
	$customers = $sth->fetchAll(\PDO::FETCH_OBJ);
	$dbh = null;

} catch (\PDOException $e) {
	echo "Error: " . $e->getMessage() . "<br/>";
	die();
}

?>

<h2>Customer List</h2>

<table>
	<tr>
		<th>Customer ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Occupation</th>
	</tr>
	<!-- Write your code here -->
	<?php
	if (isset($customers)):
		foreach ($customers as $customer): ?>
			<tr>
				<td><?php echo $customer->customer_id; ?></td>
				<td><?php echo $customer->first_name; ?></td>
				<td><?php echo $customer->last_name; ?></td>
				<td><?php echo $customer->occupation_name; ?></td>
			</tr>
		<?php endforeach;
	endif; ?>
</table>

