<?php 
class UsersController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add'); 
    }
    
    /**
     * Login page
     */
    public function login() 
    {   
        if($this->Auth->loggedIn())
        {
            $this->redirect(array('controller' => 'products', 'action' => 'page'));
            //return $this->redirect('posts/index');
        }  
        
        if ($this->request->is('post')) 
        {
            if ($this->Auth->login())
            {
                $data = $this->Auth->user();
                
                if($data['role'] === 'admin')
                    return $this->redirect('/products/index');
                    
                //return $this->redirect($this->Auth->loginRedirect);
                return $this->redirect('/products/user');
            } 
                
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }
    
    /**
     * Logout page
     */
    public function logout() 
    {    
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
        //return $this->redirect($this->Auth->logoutRedirect);
        //return $this->redirect(array('controller' => 'posts', 'action' => 'page'));
    }
    
    /**
     * Default page
     */
    public function index() 
    {        
        //Simple authentication for non-admin
        if($this->Auth->user('role') !== 'admin')
            $this->redirect(array('controller' => 'products', 'action' => 'page'));
                
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }
    
    /**
     * View
     */
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
    
    /**
     * Add
     */
    public function add() {
        
        //if logged in redirect to another
        if ($this->Auth->login())
            if($this->Auth->user('role') !== 'admin')
                return $this->redirect('/products/page');
            
        if ($this->request->is('post')) {
            
            //Check if username existed
            $conditions = array(
                'User.username' => $this->request->data['User']['username']
            );
            
            if ($this->User->hasAny($conditions)){
                $this->Session->setFlash(__('Username already existed. Please try again.'));
            }
            
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                
                //set login redirection
                if($this->Auth->user('role') !== 'admin')
                {
                    $this->Session->setFlash(__('Thank you for register. You may login.'));
                    return $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
                else
                {
                    $this->Session->setFlash(__('The user has been saved'));
                    return $this->redirect(array('controller' => 'users', 'action' => 'index'));
                }
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
    }
    
    /**
     * Edit
     */
    public function edit($id = null) 
    {    
        //Simple authentication for non-admin
        if($this->Auth->user('role') !== 'admin')
            if($this->Auth->user('id') !== $id)
                $this->redirect(array('controller' => 'products', 'action' => 'page'));
        
        $this->User->id = $id;
    
        if (!$this->User->exists()) 
            throw new NotFoundException(__('Invalid user'));
        
        if ($this->request->is('post') || $this->request->is('put')) 
        {
            
            //Check if username existed
            $conditions = array(
                'User.id !=' => $id,
                'User.username' => $this->request->data['User']['username']
            );
            if ($this->User->hasAny($conditions)){
                $this->Session->setFlash(__('Username already existed. Please try again.'));
            }

            //custom validate password edit
            if (!empty($this->request->data['User']['new_password']))
                $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
            
            if ($this->User->save($this->request->data))
                return $this->Session->setFlash(__('Profile sucessfully updated'));
            
            $this->Session->setFlash(__('Profile couldn\'t be updated. Please, try again.'));
        } 
        else 
        {
            $this->request->data = $this->User->read(null, $id);
            //unset($this->request->data['User']['password']);
        }
    }
    
    /**
     * Delete
     */
    public function delete($id = null) 
    {
        //Simple authentication for non-admin
        if($this->Auth->user('role') !== 'admin')
                $this->redirect(array('controller' => 'products', 'action' => 'page'));
        
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        
        $this->Session->setFlash(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }
    
    /**
     * Hash password
     */
     
    function hashPasswords($data) 
    {
        if (!empty($this->data['User']['password'])) {
            $this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
        }
        return $data;
    }
}