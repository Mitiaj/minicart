<li>
    <h3 class="productTitle">
        <a href="<?php echo Yii::app()->createUrl('product/view/'.$oProduct->id.'/'.str_replace(" ","-",$oProduct->productName) ) ?>"><?php echo $oProduct->productName ?></a>
    </h3>
    <div class="productDescription">
        <?php echo $oProduct->productDescription ?>
    </div>
    <div class="productPrice">
        <?php echo $oProduct->price ?>
    </div>
</li>