<?php function draw_header($title) { ?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <h1><?=$title?></h1>
    <main>
<?php } ?>

<?php function draw_footer($title) { ?>
    </main>
  </body>
  <h1><?=$title?></h1>
</html>
<?php } ?>