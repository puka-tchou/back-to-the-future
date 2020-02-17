<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./spectre.min.css">
    <title>Document</title>
</head>
<body>
<?php
require __DIR__ . '/../../../vendor/autoload.php';
use Stock\Stock;

$stock = new Stock();
$stockByPart = $stock->get(__DIR__ . '/../xml/setlist.template.txt');
if (count($stockByPart) > 0) : ?>
<table class="table">
  <thead>
    <tr>
      <th scope="col"></th>
      <th scope="col"><?php echo implode('</th><th>', array_keys(current($stockByPart))); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($stockByPart as $row => $value) : ?>
    <tr>
      <th scope="row"><?php echo $row; ?></td>
      <td><?php echo implode('</td><td>', $value); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
</body>
</html>
