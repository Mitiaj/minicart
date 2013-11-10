<header>
<?php $this->renderPartial('_header') ?>
</header>
<div class = "main">
    <h1>All prooducts</h1>
    <div class = "productList">
        <?php $this->renderPartial('_productList',array(
            'oProducts' => $oProducts
        )) ?>

    </div>
</div>
<footer>
    <?php $this->renderPartial('_footer') ?>

</footer>