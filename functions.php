<?php

use Classes\User\User;
use Classes\Cart\Cart;

function formatPrice($vlprice){
	return number_format($vlprice, 2, ",", ".");
}

function formatDate($date){
	return date('d/m/Y', strtotime($date));
}

function checkLogin($inadmin = true){
	return User::checkLogin($inadmin);
}

function getUserName(){
	$user = User::getFromSession();
	return $user->getnome();
}

function getCartNrQtd(){
	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return $totals['nrqtd'];

}

function getCartVlSubTotal(){
	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return formatPrice($totals['vlprice']);

}

?>
