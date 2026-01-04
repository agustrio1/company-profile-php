<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Company Profile' ?></title>
    
    <?= vite() ?>
</head>
<body class="bg-neutral-primary">
    
    <?= $content ?? '' ?>

</body>
</html>