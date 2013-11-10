<ul>
    <?php
    foreach($oProducts as $oProduct)
    {
        $this->renderPartial('__xPRoduct',array(
            'oProduct' => (object)$oProduct
        ));
    }
    ?>
</ul>