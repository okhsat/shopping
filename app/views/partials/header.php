<?php
/**
 * View Partial Header
 * 
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */
?>

<!DOCTYPE html>
<html class="no-js" lang="en" style="opacity: 1;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"              content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description"           content="ABC Hosting Test App">
    <meta name="keywords"              content="ABC Hosting Test App">

    <title><?= isset($title) ? $title : 'Basic Shopping App'; ?></title>
    
    <link href="http://www.turandevelop.com"                                                            rel="canonical"     type="text/css" />
    <link href="/img/favicon.ico"                                                                       rel="shortcut icon" type="image/x-icon" />
    <link href="/font/icons/style.css"                                                                  rel="stylesheet"    type="text/css" />
    <link href="/css/magnific-popup.css"                                                                rel="stylesheet"    type="text/css" />
    <link href="/css/main.css?ver=01"                                                                   rel="stylesheet"    type="text/css" />
    <link href="/css/custom.css?ver=01"                                                                 rel="stylesheet"    type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700&amp;subset=latin-ext" rel="stylesheet"    type="text/css" />
</head>

<body>
    <div class="c-site-wrapper" id="app">
        <div class="c-site-inner">
            <div class="c-off-canvas">
                <div class="c-off-canvas__inner">
                    <nav class="c-off-canvas__nav">
                        <ul class="c-off-canvas__list">
                            <li class="c-off-canvas__item">
                                <a class="c-off-canvas__link" href="/products">Products</a>
                            </li>
                            <li class="c-off-canvas__item">
                                <a class="c-off-canvas__link" href="/cart">Cart</a>
                            </li>
                            <?php if ($isLoggedIn) : ?>
                            <li class="c-off-canvas__item has-user-nav user-access">
                                <a class="c-off-canvas__link" href="#">User</a>
                                <ul class="c-off-canvas__user-nav">
                                    <li class="c-off-canvas__item">
                                        <a class="c-off-canvas__link" href="/orders">My Orders</a>
                                    </li>
                                    <li class="c-off-canvas__item">
                                        <a class="c-off-canvas__link" href="/user">My Account</a>
                                    </li>
                                    <li class="c-off-canvas__item">
                                        <a class="c-off-canvas__link" href="/action/logout">Sign out</a>
                                    </li>
                                </ul>
                            </li>
                            <?php else : ?>
                            <li class="c-off-canvas__item has-user-nav visitor-access">
                                <a class="c-off-canvas__link" href="#">Start</a>
                                <ul class="c-off-canvas__user-nav">
                                    <li class="c-off-canvas__item">
                                        <a class="c-off-canvas__link" href="/register">Register</a>
                                    </li>
                                
                                    <li class="c-off-canvas__item c-off-canvas__item--before-bracket">
                                        <a class="c-off-canvas__link" href="/login">Login</a>
                                    </li>                                
                                </ul>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                
                    <?php if ($isLoggedIn) : ?>
                    <a class="c-off-canvas__user" href="#">
                        <span class="c-site-nav__user o-flag">
                            <span class="c-site-nav__user-img o-flag__img">
                                <i class="c-site-nav__user-icon icon-user-circle-o">
                                    <span class="u-hidden-visually">User</span>
                                </i>
                            </span>
                            <span class="c-site-nav__user-body o-flag__body">
                                <span class="c-site-nav__user__name"><?= isset($logged_user['name']) ? $logged_user['name'] : ''; ?></span>
                            </span>
                        </span>
                    </a>
                    <?php endif; ?>
                
                    <button class="c-off-canvas__close" id="c-off-canvas__close" type="button">
                        <i class="icon-close">
                            <span class="u-hidden-visually">Close</span>
                        </i>
                    </button>
                </div>

                <div class="c-off-canvas__morph-shape" id="c-off-canvas__morph-shape" data-morph-open="M-7.312,0H15c0,0,66,113.339,66,399.5C81,664.006,15,800,15,800H-7.312V0z;M-7.312,0H100c0,0,0,113.839,0,400c0,264.506,0,400,0,400H-7.312V0z">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="Gradient1" x1="0" x2="0" y1="0" y2="1">
                                <stop class="stop1" offset="0%" />
                                <stop class="stop2" offset="100%" />
                            </linearGradient>
                            <style type="text/css">
                                .stop1 {
                                    stop-color: #00b0c4;
                                }

                                .stop2 {
                                    stop-color: #16b68f;
                                }
                            </style>
                        </defs>

                        <path d="M-7.312,0H0c0,0,0,113.839,0,400c0,264.506,0,400,0,400h-7.312V0z" fill="url(#Gradient1)" />
                    </svg>
                </div>
            </div>
        
            <header class="c-site-header c-site-header--sticky">
                <div class="c-site-header__wrapper o-wrapper">                    
                    <div class="c-site-header__flag o-flag o-flag--middle">
                        <div class="c-site-header__img o-flag__img">
                            <a class="c-site-logo" href="/">
                                <strong class="u-hidden-visually">App</strong>
                            </a>
                        </div>
                        <div class="c-site-header__body o-flag__body">
                            <button id="open-off-canvas" class="c-site-burger" type="button">
                                <i class="icon-burger-menu">
                                    <span class="u-hidden-visually">Menu</span>
                                </i>
                            </button>
                            
                            <nav class="c-site-nav c-site-nav--primary" id="site-nav">
                                <ul class="c-site-nav__list">
                                    <li class="c-site-nav__item">
                                        <a class="c-site-nav__link" href="/products">Products</a>
                                    </li>
                                    <li class="c-site-nav__item">
                                        <a class="c-site-nav__link" href="/cart">Cart</a>
                                    </li>
                                </ul>
                            </nav>

                            <nav class="c-site-nav c-site-nav--secondary" id="site-nav-secondary">
                                <?php if ($isLoggedIn) : ?>
                                <ul class="c-site-nav__list user-access">
                                    <li class="c-site-nav__item has-dropdown">
                                        <a class="c-site-nav__link has-user" href="#">
                                            <span class="c-site-nav__user o-flag">
                                                <span class="c-site-nav__user-img o-flag__img">
                                                    <i class="c-site-nav__user-icon icon-user-circle-o">
                                                        <span class="u-hidden-visually">User</span>
                                                    </i>
                                                </span>
                                                <span class="c-site-nav__user-body o-flag__body">
                                                    <span class="c-site-nav__user__name"><?= isset($logged_user['name']) ? $logged_user['name'] : ''; ?></span>
                                                </span>
                                            </span>
                                        </a>
                                        
                                        <ul class="c-dropdown c-dropdown--sm c-dropdown--right">
                                            <li class="c-dropdown__item">
                                                <a class="c-dropdown__link" href="/orders">My Orders</a>
                                            </li>
                                            <li class="c-dropdown__item">
                                                <a class="c-dropdown__link" href="/user">My Account</a>
                                            </li>
                                            <li class="c-dropdown__item">
                                                <a class="c-dropdown__link" href="/action/logout">Sign out</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <?php else : ?>
                                <ul class="c-site-nav__list visitor-access">
                                    <li class="c-site-nav__item">
                                        <a class="c-site-nav__link" href="/register">Register</a>
                                    </li>
                                    
                                    <li class="c-site-nav__item c-site-nav__item--before-bracket">
                                        <a class="c-site-nav__link" href="/login">Login</a>
                                    </li>                                            
                                </ul>
                                <?php endif; ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>

