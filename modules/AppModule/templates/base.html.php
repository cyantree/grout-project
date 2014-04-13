<?php
use Grout\AppModule\Types\AppTemplateContext;

/** @var $this AppTemplateContext */
?>
<!doctype html>
<html>
<head>
    <base href="<?= $this->app->url ?>">
</head>
<body>
<?= $this->in->get('content') ?>
</body>
</html>