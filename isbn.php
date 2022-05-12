<?php
/**
 * @package Isbn_FaireUngeste
 * @version 1.0.0
 */
/*
Plugin Name: ISBN
Plugin URI: https://github.com/Johnkennabii
Description: Extension conçu pour le site faireungeste.fr : remonter des articles via ISBN
Author: JohnKenNabii
Version: 3.0
Author URI: https://github.com/Johnkennabii
*/

function isbn_page(){
    include('index_isbn.php');
}

function isbn(){
    add_menu_page('ISBN Page', 'ISBN Scan', 'administrator','isbn/index.php','isbn_page','dashicons-book', 3 );
}

add_action('admin_menu', 'isbn', 'isbn_page');