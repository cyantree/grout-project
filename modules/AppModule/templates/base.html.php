<?php
use Grout\AppModule\Types\AppTemplateContext;

/** @var $this AppTemplateContext */
?>
<!DOCTYPE html>
<html>
<head>
    <base href="<?= $this->app->url ?>">
    <meta charset="utf-8">
</head>
<body>
<?= $this->in->get('content') ?>
</body>
</html>
