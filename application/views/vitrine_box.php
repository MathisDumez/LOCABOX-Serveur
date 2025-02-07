<!DOCTYPE html>
<html lang="fr">
<head>
    <title>LOCABOX</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
</head>
<body>
    <h1>Test</h1>
    <ul>
        <?php foreach ($resultats as $row): ?>
            <li><?= $row->nom_colonne; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>