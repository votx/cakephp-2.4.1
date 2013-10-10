<?php
class Product extends AppModel {
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty'
        ),
        'description' => array(
            'rule' => 'notEmpty'
        ),
        'price' => array(
            //'rule' => array('decimal', 2),
            'rule'    => array('money', 'left'),
            'allowEmpty' => true,
            'message' => 'Invalid price',
            'maxLength' => array( 
                'rule' => array('maxLength', 10), 
                'message' => 'Price can not be longer than 10 digit.' 
            )
        )
    );
     
    public function isOwnedBy($post, $user) 
    {
        return $this->field('id', array('id' => $post, 'user_id' => $user)) === $post;
    }
}