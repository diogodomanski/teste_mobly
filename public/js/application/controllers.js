'use strict';

var appControllers = angular.module('app.controllers', []);

appControllers.controller('MainController', ['$rootScope', '$scope', '$state', '$modal', 'Category', 'ShoppingCart', 'Session',
	function($rootScope, $scope, $state, $modal, Category, ShoppingCart, Session) {
		$rootScope.currentUser = null;
		$rootScope.shoppingCart = [];
		$rootScope.shoppingCartCount = 0;
		$rootScope.shoppingCartPrice = 0;
		$scope.categories = [];
		$rootScope.search = "";
		$rootScope.selectedCategory = 0;
		$rootScope.dialogMessage = "";

		$scope.toggleMenu = function() {
			angular.element('.row-offcanvas').toggleClass('active');
		};

		$scope.loadCategories = function() {
			var $defer = Category.list();
			$defer.$promise.then(function(result) {
				if (result.code == 0)
					$scope.categories = result.data;
				else
					$scope.categories = [];
			});
		};

		$scope.selectCategory = function(categoryId) {
			$rootScope.selectedCategory = categoryId;
			$rootScope.loadProducts(categoryId);
		};

		$rootScope.getSessionData = function() {
			var $defer = Session.get();
			$defer.$promise.then(function(result) {
				if (result.code == 0) {
					$rootScope.currentUser = result.data.current_user;
					$rootScope.shoppingCart = result.data.shopping_cart;
				} else {
					$rootScope.shoppingCart = [];
					$rootScope.currentUser = null;
				}

				$rootScope.updateShoppingCartCount();
			});
		};

		$rootScope.addToShoppingCart = function(product, count) {
			var productExists = false;
			for (var i = 0; i < $rootScope.shoppingCart.length; i++) {
				if ($rootScope.shoppingCart[i].product_id == product.id) {
					$rootScope.shoppingCart[i].count = parseInt($rootScope.shoppingCart[i].count) + count;
					$rootScope.shoppingCart[i].total = parseFloat($rootScope.shoppingCart[i].price * $rootScope.shoppingCart[i].count);
					productExists = true;
					break;
				}
			}

			if (!productExists)
				$rootScope.shoppingCart.push({product_id: product.id, name: product.name, price: product.price, count: count, total: parseFloat(product.price * count)});

			$rootScope.saveShoppingCart();
		};

		$rootScope.saveShoppingCart = function() {
			var $defer = ShoppingCart.save({shopping_cart: $rootScope.shoppingCart});
			$defer.$promise.then(function(result) {
				$rootScope.updateShoppingCartCount();
			});
		};

		$rootScope.removeFromShoppingCart = function(product) {
			for (var i = 0; i < $rootScope.shoppingCart.length; i++) {
				if ($rootScope.shoppingCart[i].product_id == product.product_id) {
					$rootScope.shoppingCart.splice(i, 1);
					$rootScope.saveShoppingCart();
					break;
				}
			}
		};

		$rootScope.updateShoppingCartCount = function() {
			$rootScope.shoppingCartCount = 0;
			$rootScope.shoppingCartPrice = 0;

			for (var i = 0; i < $rootScope.shoppingCart.length; i++) {
				$rootScope.shoppingCartCount += $rootScope.shoppingCart[i].count;
				$rootScope.shoppingCartPrice += $rootScope.shoppingCart[i].total;
			}
		};

		$rootScope.showDialog = function(message) {
			$rootScope.dialogMessage = message;
			
			$modal.open({
				templateUrl: 'modalDialog.html',
				controller: function($scope, $modalInstance) {
					$scope.closeDialog = function () {
						$modalInstance.dismiss('ok');
					 };
				}
			});
		};

		$rootScope.getSessionData();
		$scope.loadCategories();
		$state.transitionTo('store.product-list');
	}
]);

appControllers.controller('ProductController', ['$rootScope', '$scope', '$state', 'Product',
	function($rootScope, $scope, $state, Product) {
		$scope.products = [];
		$scope.orderProp = 'name';

		$scope.selectedProduct = null;

		$rootScope.loadProducts = function() {
			var $defer = Product.list({id: $rootScope.selectedCategory});
			$defer.$promise.then(function(result) {
				if (result.code == 0)
					$scope.products = result.data;
				else
					$scope.products = [];

				$state.transitionTo('store.product-list');
			});
		};

		$scope.loadProductDetails = function(productId) {
			var $defer = Product.getDetail({id: productId});
			$defer.$promise.then(function(result) {
				if (result.code == 0) {
					$rootScope.selectedProduct = result.data;
					$rootScope.selecteImageId = $rootScope.selectedProduct.images[0].id;
				} else {
					$rootScope.selectedProduct = null;
					$rootScope.selecteImageId = 0;
				}

			});

			$state.transitionTo('store.product-detail');
		};

		$rootScope.loadProducts();
	}]);

appControllers.controller('ProductDetailController', ['$rootScope', '$scope', '$state', 'Product',
	function($rootScope, $scope, $state, Product) {
		$scope.setImage = function(imageId) {
			$rootScope.selecteImageId = imageId;
		};

		$scope.backToStore = function() {
			$rootScope.loadProducts();
		};
	}]);

appControllers.controller('ShoppingCartController', ['$rootScope', '$scope', '$state',
	function($rootScope, $scope, $state) {
		$scope.mudouQtd = function(item) {
			item.price = parseFloat(item.price);
			item.count = isNaN(parseInt(item.count)) ? 0 : parseInt(item.count);
			item.total = item.price * item.count;

			$rootScope.saveShoppingCart();
		};

		$scope.showCheckoutForm = function() {
			if ($rootScope.currentUser == null)
				$state.transitionTo('store.login');
			else
				$state.transitionTo('store.checkout');
		};
	}]);

appControllers.controller('LoginController', ['$rootScope', '$scope', '$state', 'User',
	function($rootScope, $scope, $state, User) {
		$scope.email = null;
		$scope.password = null;

		$scope.login = function() {
			var $defer = User.login({email: $scope.email, password: $scope.password});
			$defer.$promise.then(function(result) {
				if (result.code == 0 && result.data != null) {
					$rootScope.currentUser = result.data;

					if ($rootScope.shoppingCart.length)
						$state.transitionTo('store.checkout');
					else
						$state.transitionTo('store.shopping-cart');
				} else {
					// show error dialog
					$rootScope.showDialog(result.message);

					$scope.password = null;
				}
			});
		};
	}]);

appControllers.controller('CheckoutController', ['$rootScope', '$scope', '$state', 'ShoppingCart',
	function($rootScope, $scope, $state, ShoppingCart) {
		$scope.placeOrder = function() {
			var $defer = ShoppingCart.placeOrder();
			$defer.$promise.then(function(result) {
				if (result.code == 0) {
					$rootScope.showDialog('Order # ' + result.data.id + ' saved successfully!');

					$rootScope.getSessionData();
					$scope.loadCategories();
					$state.transitionTo('store.product-list');
				} else {
					// show error dialog
					$rootScope.showDialog(result.message);
				}
			});
		};
	}]);

appControllers.controller('MyOrdersController', ['$rootScope', '$scope', '$state', 'Order',
	function($rootScope, $scope, $state, Order) {
		$scope.orders = [];
		
		$scope.getOrders = function() {
			var $defer = Order.list();
			$defer.$promise.then(function(result) {
				if (result.code == 0) {
					$scope.orders = result.data;
					
					for(var i = 0; i < $scope.orders.length; i++) {
						var date = new Date($scope.orders[i].insert_date * 1000);
						$scope.orders[i].insert_date = date;
					}
				} else {
					// show error dialog
					$rootScope.showDialog(result.message);
					$scope.orders = [];
				}
			});
		};
		
		$scope.getOrders();
	}]);