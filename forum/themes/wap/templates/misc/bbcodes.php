<?php
    $this->layout('layout');
?>
<div class="menu">
      <table cellpadding="3" cellspacing="0">
            <tr><td align="center"><h3>BBcode</h3></td><td></td></tr>
            <tr><td>[php]...[/php]</td><td><?=$lang['tag_code']?></td></tr>
            <tr><td>[url=http://site_url]<span style="color:blue"><?=$lang['tags_link_name']?></span>[/url]</td><td><a href="#"><?=$lang['link']?></a></td></tr>
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