<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($news['items'] || $rights >= RIGHTS_SUPER_MODER): ?>
<div class="panel panel-primary margin-bottom">
    <div class="panel-heading"><h4 class="panel-title"><a href="<?=$news['url']?>"><?=$lang['news']?></a></h4></div>
    <?php if ($news['items']): ?>
    <div id="news" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
        <?php $i = 0; foreach ($news['items'] as $item): ?>
        <div class="item<?php if (!$i) : ?> active<?php $i++; endif ?>">
      <div class="carousel-caption">
            <?php if ($item['title']): ?><h4 class="list-group-item-heading"><?=$item['title']?></h4><?php endif ?>
            <?php if ($item['content'] || $item['comment_url']): ?>
                <div class="list-group-item-text">
                <?php if ($item['content']): ?><div><?=$item['content']?></div><?php endif ?>
                <?php if ($item['comment_url']): ?><div><a href="<?=$item['comment_url']?>"><?=$lang['discuss']?></a> (<?=$item['comment_count']?>)</div><?php endif ?>
                </div>
            <?php endif ?>
      </div>
        </div>
        <?php endforeach ?>
  </div>
  <ol class="carousel-indicators">
<?php for ($i = 0, $j = count($news['items']); $i < $j; $i++) : ?>
    <li data-target="#news" data-slide-to="<?=$i?>"<?php if (!$i) : ?> class="active"<?php endif ?>><?php echo 'Tin tức ' . ($i + 1); ?></li>
<?php endfor; ?>
  </ol>
</div>
    <?php else: ?>
    <div class="panel-body">
        <p><?=$lang['list_empty']?></p>
    </div>
    <?php endif?>
</div>
<?php endif ?>
<?php if ($forum_open): ?>
    <div class="panel panel-primary margin-bottom">
        <div class="panel-heading clearfix">
            <div class="pull-right">
                <select id="fLoadMode">
                    <option selected value="recent">Chủ đề hoạt động</option>
                    <option value="lastest">Chủ đề mới</option>
                    <?php if ($forum_unread): ?><option value="unread">Chưa đọc</option><?php endif ?>
                </select>
                <i class="fa fa-refresh fa-fw" id="fLoadIcon"></i>
            </div>
            <div>Tình hình diễn đàn</div>
        </div>
        <div class="list-group list-group-sm" id="fLoadTarget">
        <?php if ($recent_threads): ?>
            <?php foreach ($recent_threads as $item): ?>
                <div class="list-group-item <?=$item['class']?>"><?php if ($item['icons']): ?><?php foreach ($item['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($item['prefix']): ?><span class="label label-<?=$item['prefix']?>"><?=$item['prefix_name']?></span><?php endif ?><a href="<?=$item['url']?>"><?=$item['name']?></a> (<span class="red"><?=$item['post_count']?></span>) [<a href="<?=$item['last_user_url']?>"><?=$item['last_user_name']?></a>]</div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="list-group-item"><?=$lang['list_empty']?></div>
        <?php endif ?>
        </div>
    </div>

    <?php if ($portal['items']): ?>
        <div class="panel panel-primary margin-bottom">
        <?php foreach ($portal['items'] as $item): ?>
            <div class="list-group-item">
                <div class="kmedia">
                    <a class="pull-left" href="<?=$item['url']?>" style="background-image: url('<?=$item['thumb']?>')"></a>
                    <div class="kmedia-body">
                        <a href="<?=$item['url']?>"><h4 class="kmedia-heading nowrap"><?=$item['name']?></h4></a>
                        <p class="gray">Đăng bởi: <a href="<?=$item['author_url']?>"><?=$item['author_name']?></a>. Ngày đăng: <?=$item['time']?></p>
                        <p class="description"><?=$item['content']?></p>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        <?php if ($portal['next_url'] || $portal['prev_url']): ?>
            <div class="panel-footer clearfix"><?php if ($portal['prev_url']): ?><a href="<?=$portal['prev_url']?>" class="btn btn-default pull-left">&lt; Previous page</a><?php endif ?><?php if ($portal['next_url']): ?><a href="<?=$portal['next_url']?>" class="btn btn-default pull-right">Next page &gt;</a><?php endif ?></div>
        <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ($sticked_threads): ?>
        <div class="panel panel-primary margin-bottom"><div class="panel-heading"><h4 class="panel-title">Sticked Threads</h4></div><div class="list-group list-group-sm">
        <?php foreach ($sticked_threads as $item): ?>
            <div class="list-group-item bg-notif"><?php if ($item['icons']): ?><?php foreach ($item['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($item['prefix']): ?><span class="label label-<?=$item['prefix']?>"><?=$item['prefix_name']?></span><?php endif ?><a href="<?=$item['url']?>"><?=$item['name']?></a> (<span class="red"><?=$item['post_count']?></span>)<?php if ($item['last_page_url']): ?> <a href="<?=$item['last_page_url']?>">&raquo;</a><?php endif ?> [<a href="<?=$item['last_user_url']?>"><?=$item['last_user_name']?></a>]</div>
        <?php endforeach ?>
        </div>
        </div>
    <?php endif ?>
<?php endif ?>

<div class="panel panel-primary xs-margin-bottom">
    <div class="panel-heading">Thống kê</div>
    <div class="panel-body">
        <p id="last_search">Tìm kiếm gần đây: <?php echo implode(', ', $stats['last_search']); ?></p>
        <div id="stats">
            <p>Có <b class="red"><?=$stats['forum']['messages']?></b> bài viết và <b class="red"><?=$stats['forum']['files']?></b> tập tin trong <b class="red"><?=$stats['forum']['threads']?></b> chủ đề</p>
            <p><a href="users/"><?=$lang['users']?></a>: <b><?=$stats['count_users']?></b>. Mới nhất: <a href="<?=$stats['last_user_url']?>"><?=$stats['last_user_name']?></a></p>
        </div>
        <div id="onlinelist">
            <p>Có <a href="<?=$users_online_url?>"><?=$stats['total_online']?> người trực tuyến</a>, <?=$stats['users_online']?> thành viên, <?=$stats['guests_online']?> khách, <?=$stats['robots_online']?> robots</p>
            <p>Danh sách online: <?=$stats['online_list']?></p>
        </div>
    </div>
</div>
