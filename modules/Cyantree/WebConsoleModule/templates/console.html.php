<?php
use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Grout\BootstrapModule\GlobalFactory;

/** @var $this TemplateContext */

$g = GlobalFactory::get($this->app);
$q = $g->appQuick();
?>
<!doctype>
<html>
<head>
    <title>WebConsole</title>
    <meta charset="UTF-8" />
</head>
<body>
<form action="<?=$q->e($q->p('WebConsoleModule::console'))?>" method="post">
    <input type="text" name="command" maxlength="255" size="150" style="font-family: Consolay, Courier New, Courier" value="<?=$q->e($this->in->get('command'))?>" /><br />
    <input type="submit" name="submit" /><br />
    <textarea name="result" cols="150" rows="10" readonly="readonly"><?=$q->e($this->in->get('result'))?></textarea>
</form>
</body>
</html>