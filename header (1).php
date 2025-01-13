<?php
 if (($_SERVER['REQUEST_URI'] == '/school/') || ($_SERVER['REQUEST_URI'] == '/schools')) {
    wp_redirect(home_url());
    exit();
}
    $ID = get_the_ID();
    $headerLogo = get_field('header__logo', 'option');
    $headerNav = 'header__navigation';
    $headerBtn = get_field('header__tickets-btn', 'option');
    $homeURL =  home_url().$_SERVER['REQUEST_URI'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if($ID == 7422){ ?>
        <meta property="og:title" content="Log In - Colour Blast" />
        <meta property="og:image" content="<?php echo home_url(); ?>/wp-content/uploads/2024/02/URL-TAG.png" />
    <?php }else if($ID == 242){ ?>
        <meta property="og:title" content="Register A Participant - Find A School or Club - Colour Blast" />
    <?php }else if($ID == 8966){ ?>
        <meta property="og:title" content="Donate - Find My School or Club - Colour Blast" />
    <?php }else if($ID == 14622){ ?>
        <meta property="og:title" content="Participant Profile - Colour Blast" />
    <?php }else if($ID == 393){ ?>
        <meta property="og:title" content="Environmental Focus - Colour Blast" />
    <?php }?>
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/main.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!--    <title>--><?php //wp_title(); ?><!--</title>-->
    <?php wp_head(); ?>
<link href="<?php echo get_template_directory_uri() ?>/assets/css/media.css" rel="stylesheet">
</head>
    <body id="page-id-<?= $ID; ?>">
<?php if(!is_page_template('templates/template-single-without-header.php')): ?>
    <header id="header" class="header header--school-event header--violet">
        <div class="container">
            <div class="header__wrapper">
                <div class="header__fader">
                </div>
                <a href="<?php echo site_url(); ?>"  class="header__logo">
                <?php  if($ID == 8966){ ?>
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/02/FUNDRAISE-ICON-CMYK.png" alt="logo">
                <?php  }else{ ?>
                    <img src="<?= $headerLogo['url']; ?>" alt="logo">
                <?php } ?>
                </a>
                <div class="header__inner">
                    <!--                <div class="header__btns">-->
                     <?php    if($headerBtn): ?>
                        <a href="<?= $headerBtn['url']; ?>"  target="<?= $headerBtn['target']; ?>" class="btn btn--without-border">
                            <?= $headerBtn['title']; ?>
                        </a>
                    <?php endif; ?>
                    <!--                </div>-->
                    <nav class="header__menu menu">
                        <ul class="menu__list">
                            <?php while(have_rows($headerNav, 'option')): the_row();
                            $navLink = get_sub_field('link');
                            $navSubmenuCond = get_sub_field('add_submenu');
                            $submenuItems = 'submenu_items';
                            ?>
                            <?php if($navSubmenuCond): ?>
                                <li class="menu__list-item" data-dropdown-status="close" data-working-range="0 1024">
                                <div class="menu__list-item-dropdown" data-dropdown-head>
                                    <a href="<?= $navLink['url']; ?>"  target="<?= $navLink['target']; ?>" class="menu__list-item-link">
                                        <?= $navLink['title']; ?>
                                    </a>
                                    <img src="<?= get_template_directory_uri(); ?>/assets/img/icon/shape.svg" alt="shape">
                                </div>
                                <ul class="menu__sub-list menu-sub-list" data-dropdown-body>
                                    <?php while(have_rows($submenuItems)): the_row();
                                    $submenuLink = get_sub_field('submenu_link');
                                    ?>
                                    <li class="menu-sub-list__item">
                                        <a href="<?= $submenuLink['url']; ?>"  target="<?= $submenuLink['target']; ?>" class="menu-sub-list__item-link">
                                            <?= $submenuLink['title']; ?>
                                        </a>
                                    </li>
                                    <?php  endwhile; ?>
                                </ul>
                            </li>
                            <?php else: ?>
                                <li class="menu__list-item" >
                                <div class="menu__list-item-dropdown" >
                                    <a href="<?= $navLink['url']; ?>"  target="<?= $navLink['target']; ?>" class="menu__list-item-link">
                                        <?= $navLink['title']; ?>
                                    </a>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                    </nav>
                     <div class="header__pages-links pages-links school-active" style="display:none;">
                        <a href="<?php echo site_url(); ?>"  class="pages-links__link active">
                            SCHOOLS
                        </a>
                        <a href="/"  class="pages-links__link">
                            PUBLIC
                        </a>
                        <div class="flap-bg"></div>
                    </div>
                    <div class="header__pages-links myaccount mobile_theme">
                        <?php if(!is_user_logged_in()): ?>
                            <a href="<?php echo site_url(); ?>/findmyschoolorclub/"  class="btn btn--without-border btn--animation">
                                REGISTER
                            </a>
                            <a href="<?php echo site_url(); ?>/school-login"  class="btn btn--without-border btn--animation" style="margin-left: 10px;">
                                LOGIN
                            </a>
                        <?php else: ?>
                            <a href="<?php echo wp_logout_url( home_url() ); ?>"  class="btn btn--without-border btn--animation" style="margin-left: 10px;">
                                LOGOUT
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="header__btn">
                    <span>
                    </span>
                </button>
            </div>
        </div>
    </header>
<?php endif; ?>
<script>
    $(".header__btn").click(function(){
        $("header").toggleClass("header--open-menu");
    });
    $(".menu__list-item-dropdown img").click(function(){
        $(this).parent().parent().toggleClass("show");
    });
</script>
<main class="<?= is_page_template('templates/template-single-without-header.php')? 'without-margin' : ''; ?>">