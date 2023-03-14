<!DOCTYPE html>
<html lang="<?=$lang_iso?>">
<head>
<?php foreach ($meta_tags as $meta): ?>
    <meta <?=$meta['name']?>="<?=$meta['value']?>" content="<?=$meta['content']?>" />
<?php endforeach ?>
    <title><?=$page_title?></title>
<?php foreach ($html_links as $link): ?>
    <link <?php foreach ($link as $key => $value): ?><?=$key?>="<?=$value?>" <?php endforeach ?>/>
<?php endforeach ?>
</head>
<body basesrc="<?=$site_path?>" id="<?=$headmod?>" ses="<?=$csrf_token?>">
    <div id="container">
        <div id="body" class="maintxt container">
            <?=$this->section('content')?>
        </div><!--/ #body -->
    </div><!--/ #container -->
    <?php foreach($html_js as $js): ?>
        <?php if ($js['ext'] == 1): ?>
            <script type="text/javascript" src="<?=$js['content']?>"></script>
        <?php else: ?>
            <script type="text/javascript"><?=$js['content']?></script>
        <?php endif ?>
    <?php endforeach ?>
</body>
</html>
