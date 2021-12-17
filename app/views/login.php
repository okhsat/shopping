<?php
/**
 * View Login
 * 
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */
?>

<div class="c-section not-found">                    
    <div class="o-wrapper">
        <div class="c-heading u-align-center">
            <h1 class="u-h2 u-color-secondary u-font-medium"><?= $title; ?></h1>
        </div>
        <div class="c-content">
            <?php include_once $config['application']['viewsDir'].'partials/exception.php'; ?>
            
            <div class="c-auth">
                <form class="c-auth__form" id="login-form" method="post" action="/action/login" autocomplete="off"> 
                    <div class="o-layout">
                        <div class="c-form o-layout__item">
                            <div class="c-form__group">
                                <label for="email" class="c-form__label">Email</label>

                                <div class="c-form__field">
                                    <input class="c-form__input c-form__input--text" id="email" name="email" type="email" placeholder="" data-validation="email required">
                                </div>
                            </div>

                            <div class="c-form__group">
                                <label for="password" class="c-form__label">Password</label>

                                <div class="c-form__field">
                                    <input class="c-form__input c-form__input--text" id="password" name="password" type="password" placeholder="*********" data-validation="length required" data-validation-length="min6">
                                </div>
                            </div>
 
                            <div class="c-form__group c-form__group--submit u-margin-bottom-none">
                                <input type="hidden" name="return_url" value="<?= $return_url; ?>" />
                                <button class="c-btn c-btn--block c-btn--secondary" type="submit">Login</button>
                            </div>
                        </div>
                    </div>
                </form>                        
            </div>
                             
            <div class="u-align-center u-padding-top">
                <p>
                    Not registered yet!?
                    <br>
                    <a href="/register" class="u-color-secondary u-font-medium">Register Now</a>
                </p>
            </div>
        </div>
    </div>
</div>     