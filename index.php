<!doctype html>
<html ng-app='coinstats'>
<head>
	<title>Coin Stats</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/cyborg/bootstrap.min.css" rel="stylesheet">
	<link rel='stylesheet' href='css/style.css' />
</head>
<body>
	<div class='container'>
		<h1>Coin Stats</h1>

		<div class='row' ng-controller="StatsController">
			<div class='col-md-10'>
				<div class='row' ng-repeat="coinGroup in coinGroups">
					<div class='coin col-md-3' ng-repeat="coin in coinGroup">
						<div class='panel panel-default'>
							<div class='panel-heading'>
								<h2 class='panel-title'>
									{{coin.name}}
									<span class="badge pull-right">{{coin.balance | number:8}}</span>
								</h2>
							</div>
							<div class='list-group'>
								<a href='{{pools[pool.pool].link}}' target='_blank' class='list-group-item' ng-repeat="pool in coin.pools">
									<img src='{{pools[pool.pool].icon}}' height='16px' /> {{pool.balance | number:8}}
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='list-group col-md-2 sources'>
				<h4 class='list-group-item active'>Sources</h4>
				<div class='list-group-item' ng-class="{loading: !pool.loaded}" ng-repeat="pool in pools">
					<a href="{{pool.link}}" target='_blank'>
						<img src='{{pool.icon}}' height='16px' />
						{{pool.name}}
					</a>
					<div class='worker' ng-repeat="worker in pool.data.workers">
						<span class='name'>{{worker.name}}</span>
						<span class='speed pull-right'>{{worker.speed}}</span>
					</div>
				</div>
			</div>
		</div>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.9/angular.min.js"></script>
	<script src='js/stats.js'></script>
</body>
</html>