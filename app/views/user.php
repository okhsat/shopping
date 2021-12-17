<?php
/**
 * View User
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
            
            <div class="o-layout u-margin-none">
                <div class="o-layout__item c-box u-1/3@desktop u-margin-auto">
                    <h2 class="u-color-secondary u-font-medium u-font-size-large">My Balance: <span class="u-color-primary">$<?= $user->balance; ?></span></h2>
                            
                    <form action="/action/updateUser" method="post" autocomplete="off" id="user-form" class="c-auth__form"> 
                        <div class="c-form__group">
                            <label for="name" class="c-form__label">Full Name</label>
                                
                            <div class="c-form__field">
                                <input class="c-form__input c-form__input--text" id="name" name="name" type="text" value="<?= $user->name; ?>" placeholder="" data-validation="required">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="email" class="c-form__label">Email</label>

                            <div class="c-form__field" id="check-email">
                                 <input class="c-form__input c-form__input--text" id="email" name="email" type="email" value="<?= $user->email; ?>" placeholder="" data-validation="required email">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="phone" class="c-form__label">Phone</label>
                                    
                            <div class="c-form__field" id="check-phone">
                                <input class="c-form__input c-form__input--text" id="phone" name="phone" type="tel" value="<?= $user->phone; ?>" placeholder="" data-validation="required length" data-validation-length="5-20">
                            </div>
                        </div>

                        <div class="c-form__group">
                            <label for="gender_female" class="c-form__label">Gender</label>
                                
                            <div class="c-form__field">
                                <div class="c-check-list c-check-list--inline">
                                    <div class="c-check-list__item">
                                        <label class="c-check c-check--circle">
                                            <input class="c-check__input" type="radio" id="gender_female" name="gender" value="0" <?= $user->gender == 0 ? 'checked' : ''; ?>>
                                            <span class="c-check__text">Woman</span>
                                        </label>
                                    </div>
                                            
                                    <div class="c-check-list__item">
                                        <label class="c-check c-check--circle">
                                            <input class="c-check__input" type="radio" id="gender_male" name="gender" value="1" <?= $user->gender == 1 ? 'checked' : ''; ?>>
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

                        <div class="c-form__group c-form__group--submit u-margin-bottom-none">
                            <button class="c-btn c-btn--block c-btn--secondary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>     