<?php global $adwp; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php $adwp->head(); ?>
</head>

<body class="no-transition <?php echo isset($_GET['preview']) && 'true' === strtolower((string) $_GET['preview']) ? 'aos-disable-children' : ''; ?>">