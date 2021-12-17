<?php
/**
 * View Partial Exception
 *
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */
?>

<?php if (isset($exception) && count($exception)) : ?>
    <?php foreach ($exception as $ex) : ?>
        <?php 
        if (isset($ex['type']) && $ex['type'] == 'success') {
            $type = 'c-alert--success-2';
            
        } elseif (isset($ex['type']) && $ex['type'] == 'notice') {
            $type = 'c-alert--info';
            
        } elseif (isset($ex['type']) && $ex['type'] == 'warning') {
            $type = 'c-alert--warning';
            
        } else {
            $type = 'c-alert--danger';
        }
        ?>
        
        <div class="c-box <?= $type; ?> u-margin-bottom">
            <div class="u-margin-bottom-tiny">
                <span><?= $ex['message'] ?></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>