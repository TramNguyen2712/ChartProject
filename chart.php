<?php
$username = "root";
$password = "";
$database = "population_mn_cities";

try {
	$pdo = new PDO("mysql:host=localhost;database=$database", $username, $password);
	// Set the PDO error mode to exception 
	$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
	die("ERROR: Could not connect.". $e->getMessage());
}


?>

<!doctype html>
<html>
	<head>
		<meta chaset="utf-8">
		<meta name = "viewport" content = "width=device-width, initial-scale=1">
		<title> Population In Minnesota </title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<head>
			<img src="logo_mn.gif">
			<h1>Minnesota Cities by Population</h1>
			<p>The data is from the US Census. Below are 10 Minnesota cities ranked 1 through 10</p>
		</head>
		<div class='data1'>
			<?php
			// Attempt select query execution
			try {
				$sql = "SELECT C.City_Name as city, P.Population as population
					FROM population_mn_cities.city C JOIN population_mn_cities.population P
					ON C.City_ID = P.City_ID
					ORDER BY P.Population DESC
					LIMIT 10";
				$result = $pdo->query($sql);
				if ($result -> rowCount() > 0) {
					// create array
					$city = array();
					$population = array();
					//print a table from database
					echo "<table border='1' style='width: 50%; border-collapse:collapse; border: 2px solid black'>
					<tr>
					<th style='background-color: rgba(255, 99, 132, 0.6); height: 30px; border-bottom: 2px solid black'>Cities</th>
					<th style='background-color: rgba(255, 99, 132, 0.6); height: 30px'>Population</th>
					</tr>";
					while($row = $result->fetch()) {
						// list the rows by array
						$city[] = $row["city"];
						$population[] = $row["population"];
						echo "<tr>";
						echo "<td>". $row["city"]. "</td>";
						echo "<td>". $row["population"]."</td>";
					}
					echo "</tr>";
					echo "</table>";
				}
				else {
					echo "No records matching your query were found";
				}
			} catch(PDOException $e){
				die("ERROR: Could not able to execute $sql" . $e->getMessage());
			}
		
			//Close connection
			
			unset($pdo)
			?>
		
			<div class="container">
				<canvas id="myChart"></canvas>
			</div>
			<!-- chart.js -->
			<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

			<script>
				// connect database to js
				const city =<?php echo json_encode($city); ?>;

				const population=<?php echo json_encode($population); ?>; 
				// Setup Block
				const data = {
					labels: city,
						datasets: [{
							label: 'Population',
							data: population,
							backgroundColor: [
								'rgba(255, 99, 132, 0.6)',
								'rgba(54, 162, 235, 0.6)',
								'rgba(255, 206, 86, 0.6)',
								'rgba(75, 192, 192, 0.6)',
								'rgba(153, 102, 255, 0.6)',
								'rgba(255, 159, 64, 0.6)'
							],
							
							borderWidth: 2,
							borderColor: '#777',
							hoverBorderWidth: 3,
							hoverBorderColor: '#000'
						}]
				};
				// Config Block
				const config = {
					type: 'bar',
					data: data,
					options: {
						plugins: {
							title: {
								display: true,
								text: '10 Largest Population Cities in Minnesota',
								font: {
									size: 25
								}
							}
						},
						legend:{
							display: false,
							position:'right',
							lables: {
								fontColor: '#000'
							}
						},
						layout: {
							padding: {
								left:50,
								right:0,
								bottom:0,
								top:0
							}
						},
						scales: {
							x: {
								title:{
									display: true,
									text:'Cities',
									font:{
										weight: 'bold',
										size: 15
									}
								}
							},
							y:{
								title:{
									display: true,
									text:'Population',
									font:{
										weight: 'bold',
										size: 15
									}
								
								}
							}
						}
						
					}
				};
				// Render Block
				const myChart = new Chart(
					document.getElementById('myChart'),
					config
				);
			</script>
			
		</div>

	</body>
	
</html>