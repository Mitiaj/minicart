<li>
    <h3 class="XproductTitle">
        <a href="<?php echo Yii::app()->createUrl('product/view/'.$oProduct->id.'/'.str_replace(" ","-",$oProduct->productName) ) ?>"><?php echo $oProduct->productName ?></a>
    </h3>
    <div class="XproductPrice">
        <?php echo $oProduct->price ?>
    </div>
</li>