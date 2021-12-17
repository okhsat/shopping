<?php
/**
 * Class Controller
 * 
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

use Models\User;
use Models\Product;
use Models\Rating;
use Models\Shopping;
use Models\Order;

class Controller
{
    /**
     *
     * @var array
     */
    public $view              = [];
    
    /**
     *
     * @var string
     */
    protected $errorMessage   = '';
    
    /**
     *
     * @var string
     */
    protected $warningMessage = '';
    
    /**
     *
     * @var string
     */
    protected $noticeMessage  = '';
    
    /**
     * Method to pay for shopping cart
     *
     * @return void
     * @since  1.0
     */
    public function payCartAction()
    {
        try {
            if ( !$this->view['isLoggedIn'] ) {
                $this->setException('Please login firstly to pay and check out!', 'notice');
                $this->redirect('login?return_url=cart');
            }
            
            if ( !isset($_POST['total']) ) {
                throw new Exception('The shopping cart total value has not benn given!');
            }
            
            $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : '';
            
            if ( empty($cargo) ) {
                throw new Exception('You have not chosen your cargo preference!');
            }
            
            $user = $this->view['user'];
            
            if ( !is_object($user) ) {
                throw new Exception('Could not find the user data!');
            }
            
            $_POST['total'] = (float) $_POST['total'];
            $session_id     = session_id();
            $productsR      = Product::find();
            $shopping       = Shopping::find("session_id = '".$session_id."' AND order_id = 0");
            $products       = [];
            $total          = 0;
            
            if ( count($productsR) ) {
                foreach ( $productsR as $p ) {
                    $products[$p->id] = $p;
                }
            }
            
            if ( count($shopping) < 1 ) {
                $shopping = Shopping::find("user_id = '".$user->id."' AND order_id = 0");
            }
            
            if ( count($shopping) ) {
                foreach ( $shopping as $item ) {
                    $total += $item->quantity * $products[$item->product_id]->price;
                }
            }
            
            $total          = round($total, 2);
            $_POST['total'] = round($_POST['total'], 2);
            
            if ( $total != $_POST['total'] ) {
                throw new Exception('There is a mismatching problem between your cart and the total value you want to pay! Please try again.');
            }
            
            if ( $total > $user->balance ) {
                throw new Exception('You do not have anough balance to pay and check out your cart!');
            }
            
            if ( $total <= 0 ) {
                throw new Exception('The total price is zero!');
            }
            
            $user->balance = $user->balance - $total;
            $order         = new Order();
            $i_data        = [
                'user_id'        => $user->id,
                'price'          => $total,
                'payment_status' => 1,
                'status'         => 0,
                'paid'           => date('Y-m-d H:i:s', time())
            ];
            
            if ( !$order->bind($i_data) ) {
                throw new Exception('Could not bind the data to the object!', 1, $order->getCurExc());
            }
            
            if ( !$order->check() ) {
                throw new Exception('The data are not valid to be stored!', 2, $order->getCurExc());
            }
            
            if ( !$order->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $order->getCurExc());
            }
            
            if ( count($shopping) ) {
                foreach ( $shopping as $item ) {
                    $item->order_id = $order->id;
                    $item->user_id  = $user->id;
                    
                    $item->save();
                }
            }
            
            if ( !$user->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $user->getCurExc());
            }
            
            $user_data = $user->toArray();
            
            unset($user_data['password']);
            
            $this->setSessionVar('user', $user_data);
            $this->setException('Your payment was successful!', 'success');
            $this->redirect('orders');
            return;
            
        } catch (Exception $e) {
            $this->_err($e);
            $this->cartView();
            echo $this->render('cart');
            return;
        }
    }
    
    /**
     * Method to remove a cart item
     *
     * @return void
     * @since  1.0
     */
    public function removeCartItemAction()
    {
        try {
            $_POST['id'] = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            
            if ( $_POST['id'] < 1 ) {
                throw new Exception('No item id given to be removed!');
            }
            
            $user       = $this->view['user'];
            $session_id = session_id();
            $shopping   = Shopping::findFirst("id = '".$_POST['id']."' AND session_id = '".$session_id."' AND order_id = 0");
            
            if ( !is_object($shopping) && is_object($user) ) {
                $shopping = Shopping::findFirst("id = '".$_POST['id']."' AND user_id = '".$user->id."' AND order_id = 0");
            }
            
            if ( !is_object($shopping) ) {
                throw new Exception('Could not find the item to remove!');
            }
            
            if ( !$shopping->delete() ) {
                throw new Exception('Could not delete the item data from the database!', 3, $shopping->getCurExc());
            }
            
        } catch (Exception $e) {
            $this->_err($e);
        }
        
        return $this->redirect('cart');
    }

    /**
     * Method to add a cart item
     *
     * @return void
     * @since  1.0
     */
    public function addCartItemAction()
    {
        try {
            $data['status'] = true;
            $product_id     = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
            $quantity       = isset($_POST['quantity'])   ? (float) $_POST['quantity'] : 0;
            
            if ( $product_id < 1 ) {
                throw new Exception('The product has not been specified!');
            }
            
            if ( $quantity <= 0 ) {
                throw new Exception('The quantity has not been specified!');
            }
            
            $product = Product::findFirst("id = ".$product_id);
            
            if ( !is_object($product) ) {
                throw new Exception('Could not find the product data!');
            }
            
            if ( !in_array($product->unit, ['G', 'KG']) && $quantity != (int) $quantity ) {
                throw new Exception('Non-integer quantity values are not accepted for this product: '.$product->name.'!');
            }
            
            $data['product'] = $product;
            $user            = $this->view['user'];
            $session_id      = session_id();
            $shopping        = Shopping::findFirst("session_id = '".$session_id."' AND product_id = ".$product->id." AND order_id = 0");
            
            if ( !is_object($shopping) ) {
                $shopping = new Shopping();
            }
            
            $s_data = [
                'session_id' => $session_id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
                'user_id'    => (is_object($user) ? $user->id : 0)
            ];
            
            if ( !$shopping->bind($s_data) ) {
                throw new Exception('Could not bind the data to the object!', 1, $shopping->getCurExc());
            }
            
            if ( !$shopping->check() ) {
                throw new Exception('The data are not valid to be stored!', 2, $shopping->getCurExc());
            }
            
            if ( !$shopping->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $shopping->getCurExc());
            }
            
            $data['shopping']       = Shopping::find("session_id = '".$session_id."' AND order_id = 0");
            $data['shopping_total'] = 0;
            $productsR              = Product::find();
            $products               = [];
            
            if ( count($productsR) ) {
                foreach ( $productsR as $p ) {
                    $products[$p->id] = $p;
                }
            }
            
            if ( count($data['shopping']) < 1 && is_object($user) ) {
                $data['shopping'] = Shopping::find("user_id = '".$user->id."' AND order_id = 0");
            }
            
            if ( count($data['shopping']) ) {
                foreach ( $data['shopping'] as $item ) {
                    $data['shopping_total'] += $item->quantity * $products[$item->product_id]->price;
                }
            }
            
            $data['shopping_total'] = round($data['shopping_total'], 2);
            
        } catch (Exception $e) {
            $data['status'] = false;
            $data['msg']    = $this->_err($e);
        }
        
        exit(json_encode($data));
    }
    
    /**
     * Method to rate a product
     *
     * @return void
     * @since  1.0
     */
    public function rateProductAction()
    {
        try {
            $data['status'] = true;
            $product_id     = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
            $rate           = isset($_POST['rate'])       ? (int) $_POST['rate']       : 0;
            
            if ( $product_id < 1 ) {
                throw new Exception('The product has not been specified!');
            }
            
            if ( $rate < 1 ) {
                throw new Exception('The rating value has not been specified!');
            }
                        
            $product = Product::findFirst("id = ".$product_id);
            
            if ( !is_object($product) ) {
                throw new Exception('Could not find the product data!');
            }
            
            $data['product'] = $product;
            $user            = $this->view['user'];
            $session_id      = session_id();
            $rating          = Rating::findFirst("session_id = '".$session_id."' AND product_id = ".$product->id);
            $r_data          = [
                'session_id' => $session_id,
                'user_id'    => 0,
                'product_id' => $product->id,
                'rate'       => $rate
            ];
            
            if ( is_object($rating) ) {
                throw new Exception('You have already rated this product: '.$product->name.'!');
            }
            
            if ( is_object($user) ) {
                $r_data['user_id'] = $user->id;
                $rating            = Rating::findFirst("user_id = '".$user->id."' AND product_id = ".$product->id);
                
                if ( is_object($rating) ) {
                    throw new Exception('You have already rated this product!');
                }
            }
            
            $rating = new Rating();
            
            if ( !$rating->bind($r_data) ) {
                throw new Exception('Could not bind the data to the object!', 1, $rating->getCurExc());
            }
            
            if ( !$rating->check() ) {
                throw new Exception('The data are not valid to be stored!', 2, $rating->getCurExc());
            }
            
            if ( !$rating->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $rating->getCurExc());
            }
            
        } catch (Exception $e) {
            $data['status'] = false;
            $data['msg']    = $this->_err($e);
        }
        
        exit(json_encode($data));
    }
    
    /**
     * Method to update user
     *
     * @return void
     * @since  1.0
     */
    public function updateUserAction()
    {
        try {
            if ( !$this->view['isLoggedIn'] ) {
                $this->setException('Please login to view and edit your account and profile data!', 'notice');
                $this->redirect('login');
            }
            
            $user = $this->view['user'];
            
            if ( !is_object($user) ) {
                throw new Exception('Could not find the user data!');
            }
            
            if ( isset($_POST['password']) && !empty(trim($_POST['password'])) && isset($_POST['password_confirm']) && $_POST['password'] != $_POST['password_confirm'] ) {
                throw new Exception('The password and its confirmation are not the same!');
            }
            
            $_POST['password'] = trim($_POST['password']);
            
            if ( !$user->bind($_POST) ) {
                throw new Exception('Could not bind the data to the object!', 1, $user->getCurExc());
            }
            
            if ( !$user->check() ) {
                throw new Exception('The data are not valid to be stored!', 2, $user->getCurExc());
            }
            
            if ( !$user->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $user->getCurExc());
            }
            
        } catch (Exception $e) {
            $this->_err($e);
            $this->userView();
            echo $this->render('user');
            return;
        }
        
        $this->setException('Your account was updated successfully!', 'success');
        $this->redirect('user');
        return;
    }
    
    /**
     * Method to register
     *
     * @return void
     * @since  1.0
     */
    public function registerAction()
    {
        try {
            if ( $this->view['isLoggedIn'] ) {
                $this->setException('You are already logged in as a registered user!', 'notice');
                $this->redirect('');
            }
            
            if ( isset($_POST['password_confirm']) && $_POST['password'] != $_POST['password_confirm'] ) {
                throw new Exception('The password and its confirmation are not the same!');
            }
            
            if ( !isset($_POST['tos_pp_aggreed']) || (int) $_POST['tos_pp_aggreed'] < 1 ) {
                throw new Exception('You did not accept our terms of service and privacy policy!');
            }
            
            // Check if the user exist
            $user = User::findFirst("email = '".$_POST['email']."'");
            
            if ( is_object($user) ) {
                throw new Exception('A user with this email address already exists in the system!');
            }
            
            $user = new User();
            
            if ( !$user->bind($_POST) ) {
                throw new Exception('Could not bind the data to the object!', 1, $user->getCurExc());
            }
            
            $user->balance = 100;
            
            if ( !$user->check() ) {
                throw new Exception('The data are not valid to be stored!', 2, $user->getCurExc());
            }
            
            if ( !$user->save() ) {
                throw new Exception('Could not save the data into the database!', 3, $user->getCurExc());
            }
            
        } catch (Exception $e) {
            $this->_err($e);
            $this->registerView();
            echo $this->render('register');
            return;
        }
        
        $this->setException('Your registration was successful!', 'success');
        $this->redirect('login');
        return;
    }
    
    /**
     * Method to login
     *
     * @return void
     * @since  1.0
     */
    public function loginAction()
    {
        try {
            if ( $this->view['isLoggedIn'] ) {
                $this->setException('You are already logged in as a registered user!', 'notice');
                $this->redirect('');
            }
            
            $username   = isset($_POST['email'])      ? trim($_POST['email'])      : null;
            $password   = isset($_POST['password'])   ? trim($_POST['password'])   : null;
            $return_url = isset($_POST['return_url']) ? trim($_POST['return_url']) : '';
            
            if (empty($username) || empty($password)) {
                throw new Exception('Both username and password are required for login!', 0);
            }
            
            // Check if the user exist
            $user = User::findFirst("email = '".$username."'");
            
            if ( !is_object($user) ) {
                throw new Exception('Wrong username given for login!');
            }
            
            if ( !password_verify($password, $user->password) ) {
                throw new Exception('Wrong password given for login!');
            }
            
            $user_data = $user->toArray();
            
            unset($user_data['password']);
            
            $this->setSessionVar('user', $user_data);
            $this->redirect($return_url);
            
        } catch (Exception $e) {
            $this->_err($e);
            return $this->redirect('login');
        }
    }    
    
    /**
     * Method to logout
     *
     * @return void
     * @since  1.0
     */
    public function logoutAction()
    {
        $this->setSessionVar('user', null);
        $this->redirect('');
    }
    
    /**
     * Method to render a view
     *
     * @return string
     * @since  1.0
     */
    public function indexAction()
    {
        GLOBAL $routes;
        
        $view = isset($routes[URL_PATH]) ? $routes[URL_PATH] : 'not_found';
        
        if ( method_exists($this, $view.'View') ) {
            $this->{$view.'View'}();
        }
        
        echo $this->render($view);
    }
    
    /**
     * Method to prepare the orders view
     *
     * @return void
     * @since  1.0
     */
    public function ordersView()
    {
        try {
            if ( !$this->view['isLoggedIn'] ) {
                $this->setException('Please login to view your orders data!', 'notice');
                $this->redirect('login');
            }
            
            $this->view['title'] = 'My Orders';
            $user                = $this->view['user'];
            
            if ( !is_object($user) ) {
                throw new Exception('Could not find the user data!');
            }
            
            $this->view['orders']     = Order::find("user_id = '".$user->id."'");
            $this->view['order_data'] = [];
            $this->view['shoppings']  = [];
            $this->view['products']   = [];
            $order_ids                = [];
            $product_ids              = [];
            
            if ( count($this->view['orders']) ) {
                foreach ( $this->view['orders'] as $o ) {
                    $order_ids[]                                       = (int) $o->id;
                    $this->view['shoppings'][(int) $o->id]             = [];
                    $this->view['order_data'][(int) $o->id]['paid']    = Order::dateToReadable($o->paid);
                    $this->view['order_data'][(int) $o->id]['created'] = Order::dateToReadable($o->created);
                }
                
                $shoppings = Shopping::find("order_id IN (".implode(',', $order_ids).")");
                
                if ( count($shoppings) ) {
                    foreach ( $shoppings as $s ) {
                        $product_ids[]                                 = (int) $s->product_id;
                        $this->view['shoppings'][(int) $s->order_id][] = $s;
                    }
                    
                    $products = Product::find("id IN (".implode(',', $product_ids).")");
                    
                    if ( count($products) ) {
                        foreach ( $products as $p ) {
                            $this->view['products'][(int) $p->id] = $p;
                        }
                    }
                }
            }
            
            $this->view['js_inline'][] = "$('document').ready(function() {
                $('button.view.items').on('click', function(e) {
                    e.preventDefault();
                    $('#dataBox .c-modal__header h3').text('Order Items');
                    $('#dataBox .c-modal__body').html( $('#items-' + $(this).attr('order_id')).html() );
                    $('#dataBox').trigger('show');
                });
            });";
        
        } catch (Exception $e) {
            $this->_err($e);
            return $this->redirect('');
        }
    }
    
    /**
     * Method to prepare the cart view
     *
     * @return void
     * @since  1.0
     */
    public function cartView()
    {
        try {
            $this->view['title']    = 'Shopping Cart';
            $this->view['cargo']    = isset($_GET['cargo']) ? trim($_GET['cargo']) : '';
            $user                   = $this->view['user'];
            $session_id             = session_id();
            $products               = Product::find();
            $this->view['cargoes']  = Product::find("type = 'cargo'");
            $this->view['products'] = [];
            $cargo_ids              = [];
            
            if ( count($products) ) {
                foreach ( $products as $p ) {
                    $this->view['products'][$p->id] = $p;
                }
            }
            
            if ( count($this->view['cargoes']) ) {
                foreach ( $this->view['cargoes'] as $c) {
                    $cargo_ids[] = $c->id;
                }
            }
            
            $cargo_shopping = Shopping::find("product_id IN (".implode(',', $cargo_ids).") AND session_id = '".$session_id."' AND order_id = 0");
            $cargo_added    = $this->view['cargo'] == 'pickup' ? true : false;
            $cargo_id       = 0;
            
            if ( count($cargo_shopping) ) {
                foreach ( $cargo_shopping as $cs ) {
                    if ( $this->view['cargo'] == 'pickup' || isset($this->view['products'][(int) $this->view['cargo']]) ) {
                        if ( (int) $cs->product_id != (int) $this->view['cargo']  ) {
                            if ( !$cs->delete() ) {
                                throw new Exception('Could not remove the cargo item from your cart!');
                            }
                            
                        } else {
                            $cargo_id    = (int) $cs->product_id;
                            $cargo_added = true;
                        }
                        
                    } else if ( (int) $this->view['cargo'] < 1 ) {
                        $cargo_id    = (int) $cs->product_id;
                        $cargo_added = true;
                    }
                }
            }
            
            $this->view['cargo'] = $cargo_id > 0 ? $cargo_id : $this->view['cargo'];
            
            if ( !$cargo_added ) {
                if ( isset($this->view['products'][(int) $this->view['cargo']]) ) {
                    $cargo_item = $this->view['products'][(int) $this->view['cargo']];
                    $shopping   = new Shopping();
                    $s_data     = [
                        'session_id' => $session_id,
                        'product_id' => $cargo_item->id,
                        'quantity'   => 1,
                        'price'      => $cargo_item->price,
                        'user_id'    => (is_object($user) ? $user->id : 0)
                    ];
                    
                    if ( !$shopping->bind($s_data) ) {
                        throw new Exception('Could not bind the data to the object!', 1, $shopping->getCurExc());
                    }
                    
                    if ( !$shopping->check() ) {
                        throw new Exception('The data are not valid to be stored!', 2, $shopping->getCurExc());
                    }
                    
                    if ( !$shopping->save() ) {
                        throw new Exception('Could not save the data into the database!', 3, $shopping->getCurExc());
                    }
                    
                    $this->view['cargo'] = $cargo_item->id;
                    
                } else {
                    $this->view['cargo'] = '';
                }
            }
            
            $this->view['shopping'] = Shopping::find("session_id = '".$session_id."' AND order_id = 0");
            
            if ( is_object($user) && count($this->view['shopping']) < 1 ) {
                $this->view['shopping'] = Shopping::find("user_id = '".$user->id."' AND order_id = 0");
            }
            
            $this->view['js_inline'][] = "$('document').ready(function() {
                $('a.remove').click(function(){
                    $('form#remove-form input[name=id]').val( $(this).attr('item_id') );
                    $('form#remove-form').submit();
                });

                $('select#cargo').change(function(){
                    $('form#cargo-form').submit();
                });
            });";
            
        } catch (Exception $e) {
            $this->_err($e);
            return $this->redirect('');
        }
    }
    
    /**
     * Method to prepare the products view
     *
     * @return void
     * @since  1.0
     */
    public function productsView()
    {
        $user                         = $this->view['user'];
        $session_id                   = session_id();
        $this->view['title']          = 'Products';
        $this->view['ratings']        = [];
        $this->view['average_rating'] = [];
        $this->view['shopping_total'] = 0;
        $this->view['products']       = Product::find("type IS NULL OR type != 'cargo'");
        $this->view['shopping']       = Shopping::find("session_id = '".$session_id."' AND order_id = 0");
        $ratings                      = Rating::find();
        
        if ( count($ratings) ) {
            foreach ( $ratings as $r ) {
                $this->view['sum_ratings'][$r->product_id]  = isset($this->view['sum_ratings'][$r->product_id]) ? $this->view['sum_ratings'][$r->product_id] : 0;
                $this->view['ratings'][$r->product_id]      = isset($this->view['ratings'][$r->product_id])     ? $this->view['ratings'][$r->product_id]     : [];
                $this->view['ratings'][$r->product_id][]    = $r->rate;
                $this->view['sum_ratings'][$r->product_id] += $r->rate;
            }
        }
        
        if ( count($this->view['products']) ) {
            foreach ($this->view['products'] as $p) {
                $this->view['average_rating'][$p->id] = 0;
                $this->view['ratings'][$p->id]        = isset($this->view['ratings'][$p->id]) ? $this->view['ratings'][$p->id] : [];
                
                if ( isset($this->view['sum_ratings'][$p->id]) && isset($this->view['ratings'][$p->id]) && count($this->view['ratings'][$p->id]) > 0 ) {
                    $this->view['average_rating'][$p->id] = round($this->view['sum_ratings'][$p->id]/count($this->view['ratings'][$p->id]), 2);
                }
            }
        }
        
        if ( is_object($user) && count($this->view['shopping']) < 1 ) {
            $this->view['shopping'] = Shopping::find("user_id = '".$user->id."' AND order_id = 0");
        }
        
        if ( count($this->view['shopping']) ) {
            $productsR = Product::find();
            $products  = [];
            
            if ( count($productsR) ) {
                foreach ( $productsR as $p ) {
                    $products[$p->id] = $p;
                }
            }
            
            foreach ( $this->view['shopping'] as $item ) {
                $this->view['shopping_total'] += $item->quantity * $products[$item->product_id]->price;
            }
            
            $this->view['shopping_total'] = round($this->view['shopping_total'], 2);
        }
        
        $this->view['js_inline'][] = "$('document').ready(function() {
            $('select.rating').change(function(){
                $.ajax(
                    {
                        url:    '/action/rateProduct',
                        method: 'POST',
                        data:   {product_id: $(this).attr('product_id'), rate: $(this).val()}
                    }
            
                ).done(function(server_data) {
	                var result;
            
	                if ( server_data !== null && typeof server_data === 'object' ) {
		                result = server_data;
            
	    	        } else {
	     	         	result = $.parseJSON( $.trim(server_data) );
	             	}

                    $('select.rating').val('0');
            
                    if ( result !== null && typeof result === 'object' && typeof result.status !== 'undefined' ) {
	                    if ( result.status === true && typeof result.product.id !== 'undefined' && result.product.id > 0 ) {
		                    message('success', 'Rating was successful for the product ' + result.product.name + '.');

                            time_out = setTimeout(function(){ location.reload(); }, 3000);
            
                        } else {
	                        message('error', 'Rating the product failed! ' + result.msg);
	        	            console.log('Rating the product failed! ' + result.msg);
                        }
            
                    } else {
                        message('error', 'Rating the product failed! ' + JSON.stringify(result));
	        	        console.log('Rating the product failed! ' + JSON.stringify(result));
                    }
            
                }).fail(function(xhr) {
                    $('select.rating').val('0');
                    message('error', 'An error occured: ' + xhr.status + ' ' + xhr.statusText);
	                console.log('An error occured: ' + xhr.status + ' ' + xhr.statusText);
                });
            });

            $('button.add').click(function(){
                $.ajax(
                    {
                        url:    '/action/addCartItem',
                        method: 'POST',
                        data:   {product_id: $(this).attr('product_id'), quantity: $('input[type=text][product_id=' + $(this).attr('product_id') + '].to-add').val()}
                    }
            
                ).done(function(server_data) {
	                var result;
            
	                if ( server_data !== null && typeof server_data === 'object' ) {
		                result = server_data;
            
	    	        } else {
	     	         	result = $.parseJSON( $.trim(server_data) );
	             	}
            
                    if ( result !== null && typeof result === 'object' && typeof result.status !== 'undefined' ) {
	                    if ( result.status === true && typeof result.product.id !== 'undefined' && result.product.id > 0 ) {
		                    message('success', 'Successfully added to your cart the product ' + result.product.name + '.');
                            $('#shopping-count .value').text(result.shopping.length);
                            $('#shopping-total .value').text(result.shopping_total);
            
                        } else {
	                        message('error', 'Adding the product failed! ' + result.msg);
	        	            console.log('Adding the product failed! ' + result.msg);
                        }
            
                    } else {
                        message('error', 'Adding the product failed! ' + JSON.stringify(result));
	        	        console.log('Adding the product failed! ' + JSON.stringify(result));
                    }
            
                }).fail(function(xhr) {
                    message('error', 'An error occured: ' + xhr.status + ' ' + xhr.statusText);
	                console.log('An error occured: ' + xhr.status + ' ' + xhr.statusText);
                });
            });
        });";
    }
    
    /**
     * Method to prepare the user view
     *
     * @return void
     * @since  1.0
     */
    public function userView()
    {
        try {
            if ( !$this->view['isLoggedIn'] ) {
                $this->setException('Please login to view and edit your account and profile data!', 'notice');
                $this->redirect('login');
            }
            
            $this->view['title'] = 'My Account';
            $user                = $this->view['user'];
            
            if ( !is_object($user) ) {
                throw new Exception('Could not find the user data!');
            }
            
            if ( isset($_POST) && !empty($_POST) ) {
                if ( !$user->bind($_POST) ) {
                    throw new Exception('Could not bind the data to the object!', 1, $user->getCurExc());
                }
            }
            
        } catch (Exception $e) {
            $this->_err($e);
            return $this->redirect('');
        }
    }
    
    /**
     * Method to prepare the register view
     *
     * @return void
     * @since  1.0
     */
    public function registerView()
    {
        if ( $this->view['isLoggedIn'] ) {
            $this->setException('You are already logged in as a registered user!', 'notice');
            $this->redirect('');
        }
        
        $this->view['title'] = 'Register';
        $this->view['data']  = $_POST;
    }
    
    /**
     * Method to prepare the login view
     *
     * @return void
     * @since  1.0
     */
    public function loginView()
    {
        if ( $this->view['isLoggedIn'] ) {
            $this->setException('You are already logged in as a registered user!', 'notice');
            $this->redirect('');
        }
        
        $this->view['title']      = 'Login';
        $this->view['return_url'] = isset($_GET['return_url']) ? $_GET['return_url'] : '';
    }
    
    /**
     * Method to render a view
     *
     * @param  string      $view         The view name
     * @return string
     * @since  1.0
     */
    public function render($view = 'not_found')
    {
        GLOBAL $config;
        
        $view                    = !empty(trim($view))                                      ? trim($view) : 'not_found';
        $view                    = is_file($config['application']['viewsDir'].$view.'.php') ? $view       : 'not_found';
        $this->view['exception'] = $this->getSessionVar('exception');
        
        $this->setSessionVar('exception', []);
        extract($this->view);
        
        ob_start();
        include_once $config['application']['viewsDir'].'partials/header.php';
        include_once $config['application']['viewsDir'].$view.'.php';
        include_once $config['application']['viewsDir'].'partials/footer.php';
        
        return ob_get_clean();
    }
    
    /**
     * Method to initialize the controller
     *
     * @return void
     * @since  1.0
     */
    public function __construct()
    {
        $user                      = $this->getSessionVar('user');
        $this->view['title']       = 'Basic Shopping';
        $this->view['app_title']   = 'Basic Shopping';
        $this->view['isLoggedIn']  = is_array($user) && isset($user['id']) && (int) $user['id'] > 0 ? true                                            : false;
        $this->view['logged_user'] = is_array($user)                                                ? $user                                           : [];
        $this->view['user']        = $this->view['isLoggedIn']                                      ? User::findFirst("id = '".(int) $user['id']."'") : null;
        $this->view['isLoggedIn']  = is_array($user) && isset($user['id']) && (int) $user['id'] > 0 ? true                                            : false;
        $this->view['js']          = [
            'snap.svg-min',
            'classie',
            'vue.min',
            'jquery-3.1.1.min',
            'jquery.magnific-popup.min'
        ];
        $this->view['js_inline']  = [];
    }
    
    /**
     * Method to send a request
     *
     * @param  array       $url              The request url
     * @param  array       $data             The request data
     * @param  array       $type             The request type
     * @param  array       $headers          The request headers
     * @param  boolean     $json             The request format is JSON?
     * @param  string      $username         The request username
     * @param  string      $password         The request password
     * @return object                        The result in object format
     * @since  2.0
     */
    protected function sendRequest($url = null, $data = [], $type = null, $headers = [], $json = false, $username = null, $password = null)
    {
        $result = CoreModel::sendRequest($url, $data, $type, $headers, $json, $username, $password);
        
        if ( $result === false ) {
            $e = CoreModel::getCurSExc();
            
            if ( is_object($e) ) {
                $this->_err($e);
            }
            
            return false;
        }  
        
        return $result;
    }
        
    /**
     * Method to display all the trace errors
     *
     * @param  object     $e      The given error
     * @return string             The full error message
     * @since  2.0
     */
    protected function _err($e = null)
    {
        $error = '';
        
        while ( is_object($e) ) {
            if ( !empty($error) ) {
                $error .= '<br/>';
            }
            
            $error .= $e->getMessage();
            $e      = $e->getPrevious();
        }
        
        $this->errorMessage             = $error;
        $this->view['message']['error'] = $error;
        
        $this->setException($error, 'error');
        
        return $error;
    }    
    
    /**
     * Method to set an exception
     *
     * @param  string     $message      The exception message
     * @param  string     $type         The exception type
     * @return void
     * @since  2.0
     */
    protected function setException($message, $type = 'error')
    {
        $$message = trim($message);
        
        if ( !empty($message) ) {
            $exception   = $this->getSessionVar('exception');
            $exception   = !empty($exception) ? (array) $exception : [];
            $exception[] = [
                'type'    => $type,
                'message' => $message
            ];
            
            $this->setSessionVar('exception', $exception);
        }
    }
    
    /**
     * Method to redirect
     *
     * @param  string      $url         The redirect url
     * @return string
     * @since  1.0
     */
    public function redirect($url = '', $permanent = false)
    {
        header('Location: ' . BASE_URL.'/'.$url, true, $permanent ? 301 : 302);
        exit();
    }
    
    /**
     * Method to get the session value of a given variable name
     *
     * @param  string     $name      The given variable name
     * @return mixed                 The session value of a given variable name
     * @since  2.0
     */
    protected function getSessionVar($name)
    {
        $name  = trim($name);
        $value = !empty($name) && isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        
        return $value;
    }
    
    /**
     * Method to set a session value for a given variable name
     *
     * @param  string     $name        The given variable name
     * @param  mixed      $value       The new value given for the variable
     * @return mixed                   The previous session value of the given variable name
     * @since  2.0
     */
    protected function setSessionVar($name, $value)
    {
        $name    = trim($name);
        $p_value = !empty($name) && isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        
        if ( !empty($name) ) $_SESSION[$name] = $value;
        
        return $p_value;
    }
}
