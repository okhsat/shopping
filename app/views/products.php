<?php
/**
 * View Main
 * 
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */
?>

<div class="c-section c-section--register visitor-access register">
    <div class="o-wrapper">
        <div class="c-heading u-align-center">
            <h1 class="u-h2 u-color-secondary u-font-medium"><?= $title; ?></h1>
        </div>
        
        <div class="c-content">
            <?php include_once $config['application']['viewsDir'].'partials/exception.php'; ?>
            
            <?php if ($isLoggedIn) : ?>
            <h2 class="u-color-secondary u-font-medium u-font-size-large u-align-center">Your Balance: <span class="u-color-primary">$<?= $logged_user['balance']; ?></span></h2>
            <?php endif; ?>
           
            <?php if (isset($products) && count($products)) : ?>
            <table width="100%;">
                <tr>
                    <td>
                        ID
                    </td>            
                    <td>
                        Name
                    </td>
                    <td>
                        Price
                    </td>
                    <td>
                        Ratings
                    </td>
                    <td>
                        Rate
                    </td>
                    <td>
                        Add
                    </td>
                    <td>
                    </td>
                </tr> 
                <?php foreach ($products as $product) : ?>
                <tr>
                    <td>
                        <?= $product->id; ?>
                    </td>            
                    <td>
                        <?= $product->name; ?>
                    </td>
                    <td>
                        $<?= $product->price; ?>/<?= !empty($product->unit) ? $product->unit : 'one'; ?>
                    </td>
                    <td>
                        <div>Total: <?= count($ratings[$product->id]); ?></div>
                        <div>Average: <?= $average_rating[$product->id]; ?></div>
                    </td>
                    <td>
                        <select name="rate" product_id="<?= $product->id; ?>" class="rating">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" value="1" product_id="<?= $product->id; ?>" class="c-form__input c-form__input--text to-add" /> 
                        <?= $product->unit; ?>
                   </td>
                    <td>
                        <button type="button" product_id="<?= $product->id; ?>" class="c-btn c-btn--secondary add">Add</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
    
    <div id="fixed-cart">
        <span id="shopping-count"><span class="value"><?= count($shopping); ?></span> Product</span>
        <span id="shopping-total">$<span class="value"><?= $shopping_total; ?></span></span>
        <a href="/cart" class="c-btn c-btn--secondary">Cart</a>
    </div>
</div>

<style>
footer {
    margin-bottom: 50px;
}
</style>