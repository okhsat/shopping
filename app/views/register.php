<?php
/**
 * View Register
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
                <form action="/action/register" method="post" autocomplete="off" id="register-form" class="c-auth__form">
                    <div class="c-form">
                        <div class="c-form__group">
                            <label for="name" class="c-form__label">Full Name</label>
                                
                            <div class="c-form__field">
                                <input class="c-form__input c-form__input--text" id="name" name="name" type="text" value="<?= isset($data['name']) ? $data['name'] : ''; ?>" placeholder="" data-validation="required">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="email" class="c-form__label">Email</label>

                            <div class="c-form__field" id="check-email">
                                 <input class="c-form__input c-form__input--text" id="email" name="email" type="email" value="<?= isset($data['email']) ? $data['email'] : ''; ?>" placeholder="" data-validation="required email">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="phone" class="c-form__label">Phone</label>
                                    
                            <div class="c-form__field" id="check-phone">
                                <input class="c-form__input c-form__input--text" id="phone" name="phone" type="tel" value="<?= isset($data['phone']) ? $data['phone'] : ''; ?>" placeholder="" data-validation="required length" data-validation-length="5-20">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="gender_female" class="c-form__label">Gender</label>
                                
                            <div class="c-form__field">
                                <div class="c-check-list c-check-list--inline">
                                    <div class="c-check-list__item">
                                        <label class="c-check c-check--circle">
                                            <input class="c-check__input" type="radio" id="gender_female" name="gender" value="0" <?= isset($data['gender']) && $data['gender'] == 0 ? 'checked' : ''; ?>>
                                            <span class="c-check__text">Woman</span>
                                        </label>
                                    </div>
                                            
                                    <div class="c-check-list__item">
                                        <label class="c-check c-check--circle">
                                            <input class="c-check__input" type="radio" id="gender_male" name="gender" value="1" <?= isset($data['gender']) && $data['gender'] == 1 ? 'checked' : ''; ?>>
                                            <span class="c-check__text">Man</span>
                                        </label>
                                    </div>    
                                </div>
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="password" class="c-form__label">Password</label>
                                
                            <div class="c-form__field">
                                <input class="c-form__input c-form__input--text" id="password" name="password" type="password" placeholder="" data-validation="length required" data-validation-length="min7">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="password_confirm" class="c-form__label">Repeat Password</label>
                                
                            <div class="c-form__field">
                                <input class="c-form__input c-form__input--text" id="password_confirm" name="password_confirm" type="password" placeholder="" data-validation="confirmation" data-validation-confirm="password">
                            </div>
                        </div>    
                            
                        <div class="c-form__group">
                            <label class="c-check">
                                <input name="tos_pp_aggreed" type="checkbox" value="1" id="opt_in_aggrement" class="c-check__input" data-validation="required">
                                <span class="c-check__text">Confirm the registration agreement</span>
                            </label>
                        </div>

                        <div class="c-form__group c-form__group--submit u-margin-bottom-none">
                            <button class="c-btn c-btn--block c-btn--secondary" type="submit">Register</button>
                        </div>
                    </div>
                </form>
            </div>
                             
            <div class="u-align-center u-padding-top">
                <p>
                    Have an account?
                    <br>
                    <a href="/login" class="u-color-secondary u-font-medium">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>     