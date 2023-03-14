<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th>Input</th><th>Result</th></tr></thead>
            <tr><td>[php]...[/php]</td><td><?=$lang['tag_code']?></td></tr>
            <tr><td>[url=<?=$lang['link']?>]<span style="color:blue"><?=$lang['tags_link_name']?></span>[/url]</td><td><a href="javascript:void()"><?=$lang['tags_link_name']?></a></td></tr>
            <tr><td>[b]...[/b]</td><td><b><?=$lang['tag_bold']?></b></td></tr>
            <tr><td>[i]...[/i]</td><td><i><?=$lang['tag_italic']?></i></td></tr>
            <tr><td>[u]...[/u]</td><td><u><?=$lang['tag_underline']?></u></td></tr>
            <tr><td>[s]...[/s]</td><td><strike><?=$lang['tag_strike']?></strike></td></tr>
            <tr><td>[red]...[/red]</td><td><span style="color:red"><?=$lang['tag_red']?></span></td></tr>
            <tr><td>[green]...[/green]</td><td><span style="color:green"><?=$lang['tag_green']?></span></td></tr>
            <tr><td>[blue]...[/blue]</td><td><span style="color:blue"><?=$lang['tag_blue']?></span></td></tr>
            <tr><td>[color=]...[/color]</td><td><?=$lang['color_text']?></td></tr>
            <tr><td>[quote]...[/quote]</td><td><span class="quote"><?=$lang['tag_quote']?></span></td></tr>
            <tr><td valign="top">[*]...[/*]</td><td><span class="bblist"><?=$lang['tag_list']?></span></td></tr>
            <tr><td valign="top">[spoiler=<?=$lang['title']?>]<?=$lang['text']?>[/spoiler]</td><td>Spoiler</td></tr>
        </table>
    </div>
</div>