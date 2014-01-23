angular.module('coinstats', [])
	.controller('StatsController', function($scope, $http, $timeout) {
		$scope.pools = [];
		
		$http.get('fetch.php').
			success(function(data) {
				$scope.pools = data;
				angular.forEach(data, $scope.fetch_pool);
			});
			
		$scope.fetch_pool = function(pool) {
			for(var i = 0; i < $scope.pools.length; i++) {
				if($scope.pools[i].id == pool.id) {
					var id = i;
				}
			}
			$scope.pools[id].loaded = false;
			$http.get('fetch.php?pool=' + pool.id).
				success(function(data) {					
					$scope.pools[id].loaded = true;
					$scope.pools[id].data = data;
					$timeout(function() {
						$scope.fetch_pool(pool)
					}, 60000);
				});
		};
	});
