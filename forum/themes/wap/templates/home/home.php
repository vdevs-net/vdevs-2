<?php
    $this->layout('layout');
?>
<?php if ($news['items'] || $rights >= RIGHTS_SUPER_MODER): ?>
<div class="box box-news">
<div class="phdr"><a href="<?=$news['url']?>"><?=$lang['news']?></a></div>
<?php if ($news['items']): ?>
    <?php foreach ($news['items'] as $item): ?>
    <div class="menu">
        <?php if ($item['title']): ?><div><strong><?=$item['title']?></strong></div><?php endif ?>
        <?php if ($item['content']): ?><div><?=$item['content']?></div><?php endif ?>
        <?php if ($item['comment_url']): ?><div><a href="<?=$item['comment_url']?>"><?=$lang['discuss']?></a> (<?=$item['comment_count']?>)</div><?php endif ?>
    </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif?>
</div>
<?php endif ?>
<?php if ($forum_open): ?>
    <div class="box box-recent"><div class="phdr"><a href="<?=$forum_url?>"><b><?=$lang['forum']?></b></a><?php if ($forum_unread): ?> | <a href="<?=$forum_unread_url?>"><?=$lang['unread']?></a>&#160;<span class="red">(<b><?=$forum_unread?></b>)</span><?php endif ?> | <a href="<?=$forum_new_url?>">New Threads</a> | <a href="<?=$forum_recent_url?>">Recent Threads</a></div>
    <?php if ($recent_threads): ?>
        <?php foreach ($recent_threads as $item): ?>
            <div class="<?=$item['class']?>"><?php if ($item['icons']): ?><?php foreach ($item['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($item['prefix']): ?><span class="label label-<?=$item['prefix']?>"><?=$item['prefix_name']?></span><?php endif ?><a href="<?=$item['url']?>"><?=$item['name']?></a> (<span class="red"><?=$item['post_count']?></span>) [<a href="<?=$item['last_user_url']?>"><?=$item['last_user_name']?></a>]</div>
        <?php endforeach ?>
    <?php else: ?>
        <div class="menu"><?=$lang['list_empty']?></div>
    <?php endif ?>
    </div>

    <?php if ($portal['items']): ?>
        <div class="box box-portal">
        <?php foreach ($portal['items'] as $item): ?>
            <div class="portal-item"><div class="phdr"><h4><a href="<?=$item['url']?>"><?=$item['name']?></a></h4></div><div class="topmenu">Đăng bởi: <a href="<?=$item['author_url']?>"><?=$item['author_name']?></a>. Ngày đăng: <?=$item['time']?></div><div class="menu"><?=$item['content']?></div><div class="menu center"><img src="<?=$item['thumb']?>" /></div><div class="topmenu" style="text-align:right"><a href="<?=$item['url']?>"><?=$lang['read_more']?></a></div></div>
        <?php endforeach ?>
        <?php if ($portal['next_url'] || $portal['prev_url']): ?>
            <table width="100%"><tr><td align="left" width="50%"><?php if ($portal['prev_url']): ?><a href="<?=$portal['prev_url']?>" class="btn">&lt; Previous page</a><?php endif ?></td><td align="right" width="50%"><?php if ($portal['next_url']): ?><a href="<?=$portal['next_url']?>" class="btn">Next page &gt;</a><?php endif ?></td></tr></table>
        <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ($sticked_threads): ?>
        <div class="box box-sticked"><div class="phdr">Sticked Threads</div>
        <?php foreach ($sticked_threads as $item): ?>
            <div class="menu bg-notif"><?php if ($item['icons']): ?><?php foreach ($item['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($item['prefix']): ?><span class="label label-<?=$item['prefix']?>"><?=$item['prefix_name']?></span><?php endif ?><a href="<?=$item['url']?>"><?=$item['name']?></a> (<span class="red"><?=$item['post_count']?></span>)<?php if ($item['last_page_url']): ?> <a href="<?=$item['last_page_url']?>">&raquo;</a><?php endif ?> [<a href="<?=$item['last_user_url']?>"><?=$item['last_user_name']?></a>]</div>
        <?php endforeach ?>
        </div>
    <?php endif ?>
<?php endif ?>

<div class="box box-menu"><div class="phdr"><b>MENU</b></div>
<?php if ($show_users_link): ?>
    <div class="menu"><a href="users/"><?=$lang['users']?></a></div>
<?php endif ?>
<div class="menu"><a href="tools/">Tools</a></div>
<?php if ($loged): ?>
    <div class="menu"><a href="chat/">Chatbox</a> (<span id="total"><?=$unread_chat?></span>)</div>
    <div class="menu"><a href="game/">Game</a></div>
<?php endif ?>
<div class="menu"><a href="misc/help"><?=$lang['information_faq']?></a></div>
</div>

<div class="box box-stats"><div class="phdr">Thống kê</div>
<div class="menu" id="last_search">Tìm kiếm gần đây: <?php echo implode(', ', $stats['last_search']); ?></div>
<div class="menu">Có <b class="red"><?=$stats['forum']['messages']?></b> bài viết và <b class="red"><?=$stats['forum']['files']?></b> tập tin trong <b class="red"><?=$stats['forum']['threads']?></b> chủ đề</div>
<div class="menu"><a href="users/"><?=$lang['users']?></a>: <b><?=$stats['count_users']?></b>. Mới nhất: <a href="<?=$stats['last_user_url']?>"><?=$stats['last_user_name']?></a></div>
<div class="menu">Có <a href="<?=$users_online_url?>"><?=$stats['total_online']?> người trực tuyến</a>, <?=$stats['users_online']?> thành viên, <?=$stats['guests_online']?> khách, <?=$stats['robots_online']?> robots</div>
<div class="menu"><?=$stats['online_list']?></div>
</div>
