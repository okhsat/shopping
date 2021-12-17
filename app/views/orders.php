<?php
/**
 * View Orders
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
            
            <?php if (isset($orders) && count($orders)) : ?>
            <table width="100%;">
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        Price
                    </td>
                    <td>
                        Items
                    </td>
                    <td>
                        Status
                    </td>
                    <td>
                        Paid
                    </td>
                    <td>
                        Created
                    </td>
                </tr>
                <?php foreach ($orders as $o) : ?>
                <tr>
                    <td>
                        <?= $o->id; ?>
                    </td>
                    <td>
                        $<?= number_format($o->price, 2, '.', ','); ?>
                    </td>
                    <td>
                        <button type="button" class="c-btn c-btn--secondary view items" order_id="<?= $o->id; ?>">View</button>
                        
                        <span id="items-<?= $o->id; ?>" style="display: none;">
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
                                         Price
                                    </td>
                                </tr>
                                
                                <?php if (isset($shoppings[$o->id]) && count($shoppings[$o->id])) : ?>
                                <?php $total = 0; ?>
                                
                                <?php foreach ($shoppings[$o->id] as $s) : ?>
                                <tr>
                                    <td>
                                         <?php if (isset($products[$s->product_id]) && is_object($products[$s->product_id])) : ?>
                                         <?= $products[$s->product_id]->name; ?>
                                         <?php endif; ?>
                                    </td>
                                    <td>
                                         <?= $s->product_id; ?>
                                    </td>
                                    <td>
                                         <?= !in_array($products[$s->product_id]->unit, ['G', 'KG']) ? number_format($s->quantity, 0, '.', ',') : $s->quantity; ?>
                                    </td>
                                    <td>
                                         $<?= number_format($s->price, 2, '.', ','); ?>
                                    </td>
                                </tr>
                                
                                <?php $total += $s->quantity * $s->price; ?>
                                <?php endforeach; ?>
                                
                                <tr>
                                    <td colspan="3" style="border-bottom: none;">Total: </td>            
                                    <td style="border-bottom: none;">
                                         $<?= number_format($total, 2, '.', ','); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>     
                        </span>
                    </td>
                    <td>
                        <?= $o->status > 0 ? 'Confirmed' : 'In-Process'; ?>
                        <br>
                        <?= $o->payment_status > 0 ? 'Paid' : 'Unpaid'; ?>
                    </td>
                    <td>
                        <?= isset($order_data[$o->id]['paid']) ? $order_data[$o->id]['paid'] : $o->paid; ?>
                    </td>
                    <td>
                        <?= isset($order_data[$o->id]['created']) ? $order_data[$o->id]['created'] : $o->created; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>   
</div>