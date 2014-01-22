<!doctype html>
<html ng-app='coinstats'>
<head>
	<title>Bitcoin Stats</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.9/angular.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/cyborg/bootstrap.min.css" rel="stylesheet">
	<link rel='stylesheet' href='style.css' />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<script src='stats.js'></script>
</head>
<body>
	<div class='container'>
		<h1>Bitcoin Stats</h1>
	
		<div ng-controller="StatsController" class='pools row'>
			<div class='pool col-md-3' ng-repeat="pool in pools">
				<div class='panel panel-default'>
					<div class="panel-heading" ng-class="{loading: !pool.loaded}">
						<a href="{{pool.link}}" target='_blank'><h2 class="panel-title">{{pool.name}}</h2></a>
					</div>
					<div class="panel-body">
						<table class="balance table table-striped table-hover table-condensed">
							<tr ng-repeat='bal in pool.data.balance'>
								<td class='type'>{{bal.type}}:</td>
								<td class='value'>{{bal.value}}</td>
							</tr>
						</table>
					</div>
					<ul class='list-group' ng-if="pool.data.workers[0]">
						<li class="list-group-item worker" ng-repeat="worker in pool.data.workers">
							<span class="speed badge">{{worker.speed}}</span>
							<span class="name">{{worker.name}}</span>
							<div class="last_share" ng-if="worker.last_share">{{worker.last_share}}</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
	</div>
</body>
</html>
