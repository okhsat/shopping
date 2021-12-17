<?php
/**
 * View Partial Footer
 * 
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */
?>

                <footer>
                    <div class="o-wrapper">
                        <div class="u-align-center u-padding-small">
                            <div>© <?php date('Y') ?> <?= $app_title; ?>. Created By <a title="Okhtay Sattari" href="http://www.okhtay.name/" target="_blank">Okhtay Sattari</a>. All Rights Reserved.</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <div id="dataBox" class="modal mfp-hide">
        <div class="c-modal">
            <div class="c-modal__header">
                <button class="c-modal__close u-color-gray u-color-danger@hover" type="button" data-dismiss="modal">
                    <i class="icon-close">
                        <span class="u-hidden-visually">Close</span>
                    </i>
                </button>

                <h3 class="c-modal__title u-color-primary u-font-medium">Data</h3>
            </div>
        
            <div class="c-modal__body"></div>
        
            <div class="c-modal__footer u-align-right">
                <button class="c-btn c-btn--small c-btn--secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</body>

<?php if (count($js) > 0) : ?>
<?php foreach ($js as $jsf) : ?>
<script src="/js/<?= $jsf; ?>.js" type="text/javascript"></script>
<?php endforeach; ?>
<?php endif; ?>

<script type="text/javascript">
var width        = $(window).width();
var message_top  = '100px';
var message_left = '30%';

if ( width < 1024 ) {
   	message_left   = '20%';
}                		

if ( width < 768 ) {
   	message_top    = '70px';
    message_left   = '10%';
}

if ( width < 540 ) {
	message_top  = '50px';
	message_left = '5%';
}

/**
 * Method to throw and display a message (success, notice, warning, error)
 *
 * return string
 * since  1.0
 */
function message(type, text) {
    if (!type) type = 'notice';
    if (!text) text = 'There is a message to be displayed.';
        
    var num = Math.floor(Math.random() * 100001);
    var n   = num.toString();
        
    var time_out;
                	
    $('body').append('<div id="message_' + n + '" class="message ' + type + '">' + text + '<button type="button" class="message-close">x</button></div>');
    $('div#message_' + n).animate({top: message_top, left: message_left, opacity: '1'}, 1);
                	
    time_out = setTimeout(function(){ $('div#message_' + n).remove(); }, 3000);
                	
    $('div#message_' + n).hover(	
        function() {
            clearTimeout(time_out);

        }, function() {
            time_out = setTimeout(function(){ $('div#message_' + n).remove(); }, 3000);
        }
    ); 
                	
    $('div#message_' + n + ' button.message-close').click(function() {
        $('div#message_' + n).remove();
        return false;
    });
}

/**
 * Off Canvas
 * since 1.0
 */
(function() {
    var bodyEl      = document.body,
    content         = document.querySelector( '.c-site-wrapper' ),
    openbtn         = document.getElementById( 'open-off-canvas' ),
    closebtn        = document.getElementById( 'c-off-canvas__close' ),
    isOpen          = false,
    morphEl         = document.getElementById( 'c-off-canvas__morph-shape' ),
    s               = Snap( morphEl.querySelector( 'svg' ) );
    var path        = s.select( 'path' );
    var initialPath = path.attr('d'),
    steps           = morphEl.getAttribute( 'data-morph-open' ).split(';');
    var stepsTotal  = steps.length;
    var isAnimating = false;

    function init() {
        initEvents();
    }

    function initEvents() {
        openbtn.addEventListener( 'click', toggleMenu );
                
        if ( closebtn ) {
            closebtn.addEventListener( 'click', toggleMenu );
        }

        // close the menu element if the target it´s not the menu element or one of its descendants..
        content.addEventListener( 'click', function(ev) {
            var target = ev.target;
            
            if( isOpen && target !== openbtn && $(target).closest('.c-off-canvas').length === 0 ) {
                toggleMenu();
            }
        });
    }

    var scrollTop;
    var scrollTopClasses       = ['is-scrolled', 'is-scrolled--ready', 'is-scrolled--to-top'];
    var scrollTopClassesBackup = [];
            
    function saveScrollTop() {
        scrollTop = $(window).scrollTop();
                
        $.each(scrollTopClasses, function(i ,v) {
            if ( $('html').is('.' + v) ) {
                scrollTopClassesBackup.push(v);
            }
        });
    }
            
    function repairScrollTop() {
        setTimeout(function() {
            $(window).scrollTop(scrollTop);
                    
            setTimeout(function(){
                $('html').addClass(scrollTopClassesBackup.join(' '));
            }, 300);
        }, 10);
    }

    function toggleMenu() {
        if( isAnimating ) return false;
                
        isAnimating = true;
                
        if ( isOpen ) {
            classie.remove( bodyEl, 'show-off-canvas' );
            repairScrollTop();
                    
            // animate path
            setTimeout( function() {
                // reset path
                path.attr( 'd', initialPath );
                
                isAnimating = false;
            }, 300 );
                    
        } else {
            saveScrollTop();
            classie.add( bodyEl, 'show-off-canvas' );
                    
            // animate path
            var pos  = 0,
            nextStep = function( pos ) {
                if ( pos > stepsTotal - 1 ) {
                    isAnimating = false;
                    
                    return;
                }
                            
                path.animate( { 'path' : steps[pos] }, pos === 0 ? 400 : 500, pos === 0 ? mina.easein : mina.elastic, function() { nextStep(pos); } );
                pos++;
            };

            nextStep(pos);
        }
                
        isOpen = !isOpen;
    }

    init();

})();

$('document').ready(function() {
    $('.modal').each(function(){
        var $this    = $(this);
        var id       = $this.attr('id');
        var $trigger = $('<a class="u-hidden js-modal-trigger" data-toggle="modal" href="#'+id+'"></a>');
        
        if ( id && $('#' + id + ' .js-modal-trigger').length < 1 ) {
            $trigger.prependTo($this);
            $this.on('show.shopping.modal', function(){
                $trigger.trigger('click');
            });
            
            $this.on('hide.shopping.modal', function(){
                $.magnificPopup.close();
            });
        }
    });
    
    $('[data-toggle="modal"]').each(function(){
        var $modal  = $(this);
        var defaults = {
            modal: true,
            type: 'inline',
            removalDelay: 300,
            mainClass: 'mfp-shopping',
            preloader: false
        };
        var data = $modal.data();
        var options = $.extend({}, defaults, data);
        
        $modal.magnificPopup(options)
        .on('mfpOpen', function (e) {
             $('html').addClass('html--lock');
        })
        .on('mfpBeforeClose', function (e) {
             $('html').addClass('html--unlocking');
        })
        .on('mfpClose', function (e) {
             $('html').removeClass('html--lock html--unlocking');
        });
    });

    $(document).on('click', '[data-dismiss="modal"]', function(){
        $(this).closest('.modal').trigger('hide');
    });
});
</script>

<?php if (count($js_inline) > 0) : ?>
<script type="text/javascript">

<?php foreach ($js_inline as $jsc) : ?>
<?php echo $jsc; ?>
<?php endforeach; ?>

</script>
<?php endif; ?>
</html>
