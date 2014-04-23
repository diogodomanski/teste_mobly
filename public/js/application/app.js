var app = angular.module('app', [
	'ui.router',
	'ui.bootstrap',
	//'app.animations',
	'app.controllers',
	'app.filters',
	'app.services'
]);

app.config(['$stateProvider', '$urlRouterProvider',
	function($stateProvider, $urlRouterProvider) {
		$urlRouterProvider.otherwise("/");

		$stateProvider
			.state('store', {
				templateUrl: 'store-template.html',
				controller: 'MainController'
			})
			.state('store.product-list', {
				templateUrl: 'product-list-template.html',
				controller: 'ProductController'
			})
			.state('store.product-detail', {
				templateUrl: 'product-detail-template.html',
				controller: 'ProductDetailController'
			})
			.state('store.shopping-cart', {
				templateUrl: 'shopping-cart-template.html',
				controller: 'ShoppingCartController'
			})
			.state('store.login', {
				templateUrl: 'login-template.html',
				controller: 'LoginController'
			})
			.state('store.checkout', {
				templateUrl: 'checkout-template.html',
				controller: 'CheckoutController'
			})
			.state('store.my-orders', {
				templateUrl: 'my-orders-template.html',
				controller: 'MyOrdersController'
			});
	}]);