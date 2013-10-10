<?php 
class ProductsController extends AppController {
    //var $helpers = array('Cropimage'); 
    //var $components = array('JqImgcrop'); 
    //var $uses = array('Productimages', 'Product'); 
    
    /**
     * Authorize function
     */
    public function isAuthorized($user) 
    {   
        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }
    
        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete'))) {
            $productId = $this->request->params['pass'][0];
            if ($this->Product->isOwnedBy($productId, $user['id'])) {
                return true;
            }
        }
    
        return parent::isAuthorized($user);
    }

    /**
     * Public list page
     */
    /**
     * Listing page
     */
    public function page() 
    {
        $this->set('products', $this->Product->find('all'));
    }

    /**
     * Listing page
     */
    public function index() 
    {
        if($this->Auth->user('role') !== 'admin')
            return $this->redirect('/products/user');
            
        $this->set('products', $this->Product->find('all'));
    }
    
    /**
     * Listing page
     */
    public function user() 
    {
        if($this->Auth->user('role') !== 'author')
            return $this->redirect('/products');
        
        $this->set('products', $this->Product->find('all', array(
            'conditions' => array('Product.user_id' => $this->Auth->user('id'))
        )));
    }
    
    /**
     * View Page
     */
    public function view($id = null) 
    {
        $this->loadModel('Productimages');
        $this->loadModel('Comment');
        
        if (!$id) 
            throw new NotFoundException(__('Invalid post'));

        $product = $this->Product->findById($id);
        
        if (!$product) 
            throw new NotFoundException(__('Invalid post'));
        
        
        if ($this->request->is('post')) 
        {      
            if(isset($this->request->data['delete_comment']) && !empty($this->request->data['delete_comment']))
            {
                $this->Comment->id = $this->request->data['delete_comment'];
                $this->Comment->delete();
                $this->Session->setFlash(__('Comment deleted.'));
            }
            else
            {
                $this->Comment->create();
            
                //set which user create this by ID
                $this->request->data['Comment']['product_id'] = $id;
                @$this->request->data['Comment']['created_dt'] = DboSource::expression('NOW()');
                
                if ($this->Comment->save($this->request->data)) 
                {
                    $this->Session->setFlash(__('You have successfully commented.'));
                }
                
                //$this->Session->setFlash(__('Unable to comment.'));
            }
        }
        
        $admin = $this->Product->find('first', array(
            'conditions' => array('Product.id' => $id, 'user_id' => $this->Auth->user('id'))
        ));
        
        $product_img = $this->Productimages->find('all', array(
            'conditions' => array('Productimages.product_id' => $id)
        ));
        
        $comments = $this->Comment->find('all', array(
            'conditions' => array('Comment.product_id' => $id)
        ));
        
        $this->set('images', $product_img);
        $this->set('product', $product);
        $this->set('comments', $comments);
        $this->set('admin', !empty($admin) ? TRUE : FALSE);
    }
    
    /**
     * Add Page
     */
    public function add() 
    {
        //Load necessary stuff
        App::uses('Folder', 'Utility');
        $this->loadModel('Productimages');
        
        if ($this->request->is('post')) 
        {      
            $this->Product->create();
            
            //set which user create this by ID
            $this->request->data['Product']['user_id'] = $this->Auth->user('id');
            
            if ($this->Product->save($this->request->data)) 
            {
                $productID = $this->Product->getInsertID();
                
                /**
                 * Image Upload
                 */
                $files = $_FILES;
                $cpt = count($_FILES['ProductImage']['name']);
                
                for($i = 0; $i < $cpt; $i++)
                {
                    if(!empty($files['ProductImage']['name'][$i]))
                    {
                        $_FILES['ProductImage']['name'] = $files['ProductImage']['name'][$i];
                        $_FILES['ProductImage']['type'] = $files['ProductImage']['type'][$i];
                        $_FILES['ProductImage']['tmp_name'] = $files['ProductImage']['tmp_name'][$i];
                        $_FILES['ProductImage']['error'] = $files['ProductImage']['error'][$i];
                        $_FILES['ProductImage']['size'] = $files['ProductImage']['size'][$i]; 
                        
                        $ext = substr(strtolower(strrchr($_FILES['ProductImage']['name'], '.')), 1); //get the extension
                        $arr_ext = array('jpg', 'jpeg', 'gif', 'png'); //set allowed extensions
                        
                        $_FILES['ProductImage']['ori_name'] = $_FILES['ProductImage']['name'];
                        $_FILES['ProductImage']['name'] = md5(microtime().time().uniqid()).".$ext";
                        
                        //only process if the extension is valid
                        if(in_array($ext, $arr_ext))
                        {
                            /*
                            //set upload path
                            $path = WWW_ROOT.'/img/upload/images/user_'.$this->Auth->user('id').'/product_'.$productID.'/';
                            $savePath = '/img/upload/images/user_'.$this->Auth->user('id').'/product_'.$productID.'/';
                            
                            /*
                            //1st step
                            if(!is_dir($path))
                            {
                                $dir = new Folder(WWW_ROOT.'/img/upload/', true, 0755);
                                
                                $path1 = WWW_ROOT.'/img/upload/user_'.$this->Auth->user('id').'/';
                                
                                if(!is_dir($path1))
                                {
                                    $dir1 = new Folder($path1, true, 0755);
                                    
                                    $path2 = WWW_ROOT.'/img/upload/user_'.$this->Auth->user('id').'/product_'.$productID.'/';
                                    
                                    if(!is_dir($path2))
                                    {
                                        $dir2 = new Folder($path2, true, 0755);
                                        move_uploaded_file($_FILES['ProductImage']['tmp_name'], $dir2->path.'/'.$_FILES['ProductImage']['name']);
                                    }
                                }
                            }
                            else
                            {
                                $saveDir = new Folder($path);
                                move_uploaded_file($_FILES['ProductImage']['tmp_name'], $saveDir->path.'/'.$_FILES['ProductImage']['name']);
                            }
                            */
                            
                            $path = WWW_ROOT.'/img/upload/';
                            $savePath = '/img/upload';
                            
                            //do the actual uploading of the file. First arg is the tmp name, second arg is 
                            //where we are putting it
                            move_uploaded_file($_FILES['ProductImage']['tmp_name'], $path.'/'.$_FILES['ProductImage']['name']);
                            
                            //TMP
                            $this->Productimages->create();             
                            //prepare the filename for database entry
                            $this->request->data['Productimage']['name'] = $_FILES['ProductImage']['ori_name'];
                            $this->request->data['Productimage']['filename'] = $_FILES['ProductImage']['name'];
                            $this->request->data['Productimage']['path'] = $savePath;
                            $this->request->data['Productimage']['product_id'] = $productID;                                                
                            $this->Productimages->save($this->request->data['Productimage']);                                                                                          
                        }
                    }
                }
                
                $this->Session->setFlash(__('Your product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            
            $this->Session->setFlash(__('Unable to add your post.'));
        }
    }
    
    /**
     * Edit page
     */
    public function edit($id = null) 
    {
        //Load necessary stuff
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        $this->loadModel('Productimages');
        
        if (!$id) {
            throw new NotFoundException(__('Invalid product'));
        }
        
        //if not admin find by user_id & id
        if($this->Auth->user('role') === 'admin')
            $product = $this->Product->findById($id);
        else
        {
            $product = $this->Product->find('first', array(
                'conditions' => array('Product.user_id' => $this->Auth->user('id'), 'Product.id' => $id)
            ));
        }
        
        //$product = $this->Product->findById($id);
        
        if (!$product) {
            throw new NotFoundException(__('Invalid product'));
        }
    
        if ($this->request->is('post') || $this->request->is('put')) {
            
            //Delete
            if(!empty($this->request->data['delete_img']))
            {
                $delete = $this->request->data['delete_img'];
                
                foreach($delete as $d)
                {
                    $img = $this->Productimages->find('all', array(
                        'conditions' => array('Productimages.id' => $d, 'Productimages.product_id' => $id)
                    ));
                    
                    foreach($img as $i)
                    {
                        $file = new File(WWW_ROOT . $i['Productimages']['path'].'/'.$i['Productimages']['filename'], false, 0777);
                        $file->delete();
                        $this->Productimages->id = $d;
                        $this->Productimages->delete();
                    }
                }
            }
            
            $this->Product->id = $id;
            
            if ($this->Product->save($this->request->data)) {
                
                $productID = $id;
                
                /**
                 * Image Upload
                 */
                $files = $_FILES;
                $cpt = count($_FILES['ProductImage']['name']);
                
                for($i = 0; $i < $cpt; $i++)
                {
                    if(!empty($files['ProductImage']['name'][$i]))
                    {
                        $_FILES['ProductImage']['name'] = $files['ProductImage']['name'][$i];
                        $_FILES['ProductImage']['type'] = $files['ProductImage']['type'][$i];
                        $_FILES['ProductImage']['tmp_name'] = $files['ProductImage']['tmp_name'][$i];
                        $_FILES['ProductImage']['error'] = $files['ProductImage']['error'][$i];
                        $_FILES['ProductImage']['size'] = $files['ProductImage']['size'][$i]; 
                        
                        $ext = substr(strtolower(strrchr($_FILES['ProductImage']['name'], '.')), 1); //get the extension
                        $arr_ext = array('jpg', 'jpeg', 'gif', 'png'); //set allowed extensions
                        
                        $_FILES['ProductImage']['ori_name'] = $_FILES['ProductImage']['name'];
                        $_FILES['ProductImage']['name'] = md5(microtime().time().uniqid()).".$ext";
                        
                        //only process if the extension is valid
                        if(in_array($ext, $arr_ext))
                        {   
                            $path = WWW_ROOT.'/img/upload/';
                            $savePath = '/img/upload';
                            
                            //do the actual uploading of the file. First arg is the tmp name, second arg is 
                            //where we are putting it
                            move_uploaded_file($_FILES['ProductImage']['tmp_name'], $path.'/'.$_FILES['ProductImage']['name']);
                            
                            //TMP
                            $this->Productimages->create();             
                            //prepare the filename for database entry
                            $this->request->data['Productimage']['name'] = $_FILES['ProductImage']['ori_name'];
                            $this->request->data['Productimage']['filename'] = $_FILES['ProductImage']['name'];
                            $this->request->data['Productimage']['path'] = $savePath;
                            $this->request->data['Productimage']['product_id'] = $productID;                                                
                            $this->Productimages->save($this->request->data['Productimage']);                                                                                          
                        }
                    }
                }
                
                $this->Session->setFlash(__('Your product has been updated.'));
                
                //if admin redirection changed
                if($this->Auth->user('role') === 'admin')
                    return $this->redirect(array('action' => 'index'));
                else
                    return $this->redirect('/products/user');
            }
            $this->Session->setFlash(__('Unable to update your product.'));
        }
    
        if (!$this->request->data) {
            $this->request->data = $product;
        }
        
        $product_img = $this->Productimages->find('all', array(
            'conditions' => array('Productimages.product_id' => $id)
        ));
        
        $this->set('images', $product_img);
    }
    
    /**
     * Delete Function
     */
    public function delete($id) 
    {
        App::uses('File', 'Utility');
        $this->loadModel('Productimages');
        
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        
        if($this->Auth->user('role') === 'admin')
        {
            if ($this->Product->delete($id)) {
                //Delete
                $img = $this->Productimages->find('all', array(
                    'conditions' => array('Productimages.product_id' => $id)
                ));
                
                foreach($img as $i)
                {
                    $file = new File(WWW_ROOT . $i['Productimages']['path'].'/'.$i['Productimages']['filename'], false, 0777);
                    $file->delete();
                    $this->Productimages->id = $i['Productimages']['id'];
                    $this->Productimages->delete();
                }
                
                $this->Session->setFlash(__('The product with id: %s has been deleted.', h($id)));
                return $this->redirect(array('action' => 'index'));
            }
        }
        else
        {
            $this->Product->id = $id;
            $this->Product->user_id = $this->Auth->user('id');
            
            if ($this->Product->delete()) {
                $this->Session->setFlash(__('The product with id: %s has been deleted.', h($id)));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }
}