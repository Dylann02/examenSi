
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Formulaire modif epargne Client</h1>
    <form action="<?= site_url('client/traitementEpargne')?>" method="post">
        <input type="number" name="pourcentage_epargne">
        <input type="hidden" name="id_client" value="<?= $id_client ?>">
        <input type="submit" value="Valider">
    </form>
</body>
</html>