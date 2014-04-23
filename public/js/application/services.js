'use strict';

var appServices = angular.module('app.services', ['ngResource']);

appServices.factory('User', ['$resource',
	function($resource) {
		return $resource('application/index/:action', {}, {
			get: {method: 'GET', params: {action: 'get-current-user'}, isArray: false},
			login: {method: 'POST', params: {action: 'login'}, isArray: false}
		});
	}]);

appServices.factory('Session', ['$resource',
	function($resource) {
		return $resource('application/index/get-session-data', {}, {
			getSessionData: {method: 'GET', params: {}, isArray: false}
		});
	}]);

appServices.factory('ShoppingCart', ['$resource',
	function($resource) {
		return $resource('application/index/:action', {}, {
			get: {method: 'GET', params: {action: 'get-shopping-cart'}, isArray: false},
			save: {method: 'POST', params: {action: 'save-shopping-cart'}, isArray: false},
			placeOrder: {method: 'GET', params: {action: 'place-order'}, isArray: false}
		});
	}]);

appServices.factory('Category', ['$resource',
	function($resource) {
		return $resource('application/index/list-categories', {}, {
			list: {method: 'GET', params: {}, isArray: false}
		});
	}]);

appServices.factory('Product', ['$resource',
	function($resource) {
		return $resource('application/index/:action/:id', {}, {
			list: {method: 'GET', params: {action: 'list-products', id: '@id'}, isArray: false},
			getDetail: {method: 'GET', params: {action: 'get-product-detail', id: '@id'}, isArray: false}
		});
	}]);

appServices.factory('Order', ['$resource',
	function($resource) {
		return $resource('application/index/get-orders', {}, {
			list: {method: 'GET', params: {}, isArray: false}
		});
	}]);
