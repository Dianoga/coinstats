angular.module('coinstats', [])
	.controller('StatsController', function($scope, $http) {
		$scope.pools = [];
		
		$http.get('fetch.php').
			success(function(data) {
				$scope.pools = data;
				angular.forEach(data, $scope.fetch_pool);
			});
			
		$scope.fetch_pool = function(pool) {
			$http.get('fetch.php?pool=' + pool.id).
				success(function(data) {
					for(var i = 0; i < $scope.pools.length; i++) {
						if($scope.pools[i].id == pool.id) {
							$scope.pools[i].loaded = true;
							$scope.pools[i].data = data;
						}
					}
				});
		};
	});
