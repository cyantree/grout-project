<?php
use Grout\AppModule\Types\AppTemplateContext;

/** @var $this AppTemplateContext */

$ui = $this->ui();
?>

Welcome to your new grout project “<?= $this->q()->e($this->in->get('name')) ?>”!

<?= $ui->formStart($this->task->url) ?>
Enter your name:
<?= $ui->textInput('name', $this->in->get('name')) ?>
<?= $ui->submitButton('greet', 'Show greetings') ?>
<?= $ui->formEnd() ?>