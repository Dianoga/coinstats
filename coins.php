<!doctype html>
<html ng-app='coinstats'>
<head>
	<title>Bitcoin Stats</title>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.9/angular.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/cyborg/bootstrap.min.css" rel="stylesheet">
	<link rel='stylesheet' href='css/style.css' />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<script src='js/stats.js'></script>
</head>
<body>
	<div class='container'>
		<h1>Bitcoin Stats</h1>

		<div ng-controller="StatsController">
			<ul class='list-group'>
				<li class='list-group-item' ng-repeat="pool in pools">
					<a href="{{pool.link}}" target='_blank'>{{pool.name}}</a>
				</li>
			</ul>
		</div>

	</div>
</body>
</html>