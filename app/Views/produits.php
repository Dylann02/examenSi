<?php 
// var_dump($produit); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($produit)) {
        foreach ($produit as $d) { ?>
            <p><?= $d['nom']; ?></p>
            <p><?= $d['prix']; ?></p>
        <?php }
    } ?>

    <?php if(isset($produitId)){?>
        <p><?php echo $produitId['nom'];?></p>
         <p><?php echo $produitId['prix'];?></p>
    <?php } ?>
</body>

</html>