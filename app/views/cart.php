<?php
/**
 * View Cart
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
            
            <?php if ($isLoggedIn) : ?>
            <h2 class="u-color-secondary u-font-medium u-font-size-large u-align-center">Your Balance: <span class="u-color-primary">$<?= $logged_user['balance']; ?></span></h2>
            <?php endif; ?>
            
            <?php if (isset($shopping) && count($shopping)) : ?>
            <?php $total = 0; ?>
            <table width="100%;">
                <tr>
                    <td>
                        Name
                    </td>                
                    <td>
                        Product ID
                    </td>
                    <td>
                        Quantity
                    </td>
                    <td>
                        Unit Price
                    </td>
                    <td>
                        Subtotal
                    </td>
                    <td>
                        Remove
                    </td>
                </tr>
                <?php foreach ($shopping as $item) : ?>
                <?php if (isset($products[$item->product_id]) && is_object($products[$item->product_id])) : ?>
                <tr>
                    <td>
                        <?= $products[$item->product_id]->name; ?>
                    </td>            
                    <td>
                        <?= $products[$item->product_id]->id; ?>
                    </td>
                    <td>
                        <?= !in_array($products[$item->product_id]->unit, ['G', 'KG']) ? number_format($item->quantity, 0, '.', ',') : $item->quantity; ?> <?= $products[$item->product_id]->unit; ?>
                    </td>
                    <td>
                        $<?= $products[$item->product_id]->price; ?>
                    </td>
                    <td>
                        $<?= number_format($item->quantity * $products[$item->product_id]->price, 2, '.', ','); ?>
                    </td>
                    <td>
                        <a href="#" class="remove" item_id="<?= $item->id; ?>">
                            <img src="/img/close.png" />
                        </a>
                    </td>
                </tr>
                
                <?php $total += $item->quantity * $products[$item->product_id]->price; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                
                <tr>
                    <td colspan="4" style="border-bottom: none;"></td>            
                    <td style="border-bottom: none;">
                        $<?= number_format($total, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom: none;">
                    </td>
                    <td style="border-bottom: none;">
                        <form action="/cart" method="get" id="cargo-form">
                            <select name="cargo" id="cargo" class="c-form__input c-form__input--text" style="width: auto; height: 50px;">
                                <option value="">__ select Cargo __</option>
                                <option value="pickup" <?php if ($cargo == 'pickup') : ?>selected<?php endif; ?>>Pick up (Free)</option>
                                
                                <?php if (isset($cargoes) && count($cargoes)) : ?>
                                <?php foreach ($cargoes as $c) : ?>
                                <option value="<?= $c->id; ?>" <?php if ($c->id == $cargo) : ?>selected<?php endif; ?>><?= $c->name; ?> ($<?= $c->price; ?>)</option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom: none;">
                    </td>
                    <td style="border-bottom: none;">
                        <form action="/action/payCart" method="post" id="pay-form">
                            <input type="hidden" name="total" value="<?= round($total, 2); ?>" />
                            <input type="hidden" name="cargo" value="<?= $cargo; ?>" />
                            <button type="submit" class="c-btn c-btn--secondary check-out">Check out</button>
                        </form>
                    </td>
                </tr>
            </table>
            <?php endif; ?>
            
            <form action="/action/removeCartItem" method="post" id="remove-form">
                <input type="hidden" name="id" value="0" />
            </form>
        </div>
    </div>
</div>     