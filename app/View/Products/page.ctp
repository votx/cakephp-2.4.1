<?php $this->Html->script('jquery', array('inline' => false, 'fullBase' => true)); ?>
<?php $this->Html->script('jquery.dataTables', array('inline' => false, 'fullBase' => true)); ?>
<h1 style="font-weight: bold;">Product List</h1>
<?php if(!empty($products)): ?>
<table class="display dTable" id="listProduct">
    <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
    <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php foreach ($products as $product): ?>
    <tr>
        <td>
            <?php 
                echo $this->Html->link(
                    $product['Product']['title'],
                    array(
                        'controller' => 'products', 
                        'action' => 'view', 
                        $product['Product']['id']
                    )
                ); 
            ?>
        </td>
        <td><?php echo $product['Product']['price']; ?></td>
        <td><?php echo $product['Product']['created']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php unset($product); ?>
    </tbody>
</table>
<style>
#listProduct{
    margin-bottom: 20px;
}
#listProduct th{
    cursor: pointer;
}

#listProduct_filter{
    float: left;
}

#listProduct_paginate{
    float: right;
}

#listProduct_paginate a{
    border: 1px solid grey;
    padding: 5px;
    margin: 5px;
}
</style>
<script>
    $(function(){
        //Initialise datatables
        var oTable = $('#listProduct').dataTable({
            //optional
            "aoColumnDefs": [
                { 'bSortable': true, 'aTargets': [  ] }
            ],
            "bJQueryUI": false,
    		"sPaginationType": "full_numbers",
    		"sDom": '<""l>t<"F"fp>'
        });
    });
</script>
<?php else: ?>
    <div style="margin: 10px; border: 1px solid grey; padding: 10px;">
        No Result.
    </div>
<?php endif; ?>