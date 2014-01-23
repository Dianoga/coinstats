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
                    $scope.pools[id].loaded = true;
                    $scope.pools[id].data = data;
                    $scope.process_data(data);
                    $timeout(function() {
                        $scope.fetch_pool(pool)
                    }, 60000);
                });
        };

        $scope.process_data = function(data) {
            angular.forEach(data.balance, function(val, key) {
                if ($scope.coins[val.type] == undefined) {
                    $scope.coins[val.type] = {
                        name: val.type,
                        balance: 0
                    };
                }

                $scope.coins[val.type].balance += parseFloat(val.value);
            });
            $scope.coinGroups = $filter('group')($scope.coins, $scope.coinGroupCount);

            angular.forEach(data.workers, function(val, key) {
                $scope.workers[val.name] = val;
            });
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