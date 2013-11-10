<ul class="productList">
    <?php
    foreach($oProducts as $oProduct)
    {
        $this->renderPartial('__product',array(
            'oProduct' => $oProduct
        ));
    }
    ?>
    <div class="clear"></div>
</ul>