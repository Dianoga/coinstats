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

		<div class='row' ng-controller="StatsController">
			<div class='col-md-9'>

			</div>
			<div class='list-group col-md-3'>
				<h4 class='list-group-item active'>Sources</h4>
				<a href="{{pool.link}}" target='_blank' class='list-group-item' ng-class="{loading: !pool.loaded}" ng-repeat="pool in pools">{{pool.name}}</a>
			</div>
		</div>

	</div>
</body>
</html>