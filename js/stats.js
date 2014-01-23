angular.module('coinstats', [])
	.controller('StatsController', function($scope, $http, $timeout, $filter) {
		$scope.coinGroupCount = 4;

		$scope.pools = {};
		$scope.coins = {};
		$scope.workers = {};

		$scope.coinGroups = $filter('group')($scope.coins, $scope.coinGroupCount);

		$http.get('fetch.php').
		success(function(data) {
			$scope.pools = data;
			angular.forEach(data, $scope.fetch_pool);
		});

		$scope.fetch_pool = function(pool) {
			for (var i = 0; i < $scope.pools.length; i++) {
				if ($scope.pools[i].id == pool.id) {
					var id = i;
				}
			}
			$scope.pools[id].loaded = false;
			$http.get('fetch.php?pool=' + pool.id)
				.success(function(data) {
					$scope.pools[id].data = data;
					$scope.process_data();
					$scope.pools[id].loaded = true;

					var timer = (Math.random() * 30) + 45;
					console.log("Updating " + pool.name + " in " + timer + " seconds");
					$timeout(function() {
						$scope.fetch_pool(pool)
					}, timer * 1000);
				});
		};

		$scope.process_data = function() {
			angular.forEach($scope.pools, function(pool, key) {
				if (pool.data.balance != undefined) {
					angular.forEach(pool.data.balance, function(val) {
						if ($scope.coins[val.type] == undefined || key == 0) {
							$scope.coins[val.type] = {
								name: val.type,
								balance: 0
							};
						}

						$scope.coins[val.type].balance += parseFloat(val.value);
					});
					$scope.coinGroups = $filter('group')($scope.coins, $scope.coinGroupCount);
				}

				if (pool.data.workers != undefined) {
					angular.forEach(pool.data.workers, function(val, key) {
						$scope.workers[val.name] = val;
					});
				}
			})

		};
	})
	.filter('group', function() {
		return function(items, count) {
			var newArr = [];
			var i = 0;
			angular.forEach(items, function(val, key) {
				var row = Math.floor(i / count);
				var col = i % count;

				if (newArr[row] == undefined) {
					newArr[row] = [];
				}

				newArr[row][col] = val;
				i++;
			});

			return newArr;
		};
	});