<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="clearfix margin-bottom">
    <div class="pull-right">
        <a href="<?=$search_url?>" class="btn btn-sm btn-primary"><?=$lang['search']?></a>
        <?php if ($forum_unread_count): ?><a href="<?=$forum_unread_url?>" class="btn btn-danger btn-sm margin-left"><?=$lang['unread']?> <span class="badge"><b><?=$forum_unread_count?></b></span></a><?php endif ?>
        <?php if ($loged): ?><a href="<?=$thread_online_url?>" class="btn btn-sm btn-success margin-left"><?=$lang['who_here']?> <span class="badge"><?=$online_users?>&#160;/&#160;<?=$online_guests?></a></span><?php endif ?>
    </div>
</div>
<div class="panel-group">
<div class="panel panel-default">
<div class="panel-heading"><h1 class="panel-title topic-name"><?php if ($thread_deleted): ?><span class="fa fa-ban text-danger"></span> <?php endif ?><?php if ($thread_closed): ?><span class="fa fa-lock text-warning"></span> <?php endif ?><?php if ($thread_prefix): ?><span class="label label-<?=$thread_prefix?>"><?=$thread_prefix_name?></span><?php endif ?><?=$thread_name?></h1></div>
<div class="panel-body">
    <div class="row">
        <div class="col-xs-6"><?=$post_count?> bài đăng</div>
        <?php if ($thread_moder_menu): ?>
        <div class="col-xs-6 dropdown">
            <div class="dropdown-toggle pull-right">
            <button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">Thread moderator <span class="caret"></span></button>
            <ul class="dropdown-menu">
            <?php foreach ($thread_moder_menu as $thread_moder_menu_item): ?>
                <li><a href="<?=$thread_moder_menu_item['value']?>"><?=$thread_moder_menu_item['name']?></a></li>
            <?php endforeach ?>
            </ul>
            </div>
        </div>
        <?php endif ?>
    </div>
    <?php if ($thread_tags): ?>
        <div class="tagBlock">Tags: <?=$thread_tags?></div>
    <?php endif ?>
    <?php if ($thread_deleted): ?>
        <div class="text-danger"><?=$lang['topic_delete_who']?>: <b><?=$thread_delete_user?></b></div>
    <?php elseif ($thread_delete_user && $rights >= 7): ?>
        <div class="text-info"><?=$lang['topic_delete_whocancel']?>: <b><?=$thread_delete_user?></b></div>
    <?php endif ?>
</div>
</div>
<?php if ($has_vote): ?>
<?php foreach ($votes as $vote): ?>
    <div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title"><?=$vote['vote_name']?></h2>
    </div>
    <?php if ($vote['show_vote_result']): ?>
        <div class="list-group list-group-sm text-sm">
            <?php foreach ($vote['poll_options'] as $option): ?>
                <div class="list-group-item">
                    <div><?=$option['text']?> [<?=$option['count']?>]</div>
                    <div class="poll-result"><div class="barContainer"><div class="bar bg<?=$option['html_class']?>" style="width: <?=$option['percent']?>%" title="<?=$lang['rating']?>: <?=$option['percent']?>%"></div></div> <?=$option['percent']?>%</div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6"><?php if ($rights >= 7): ?><a href="<?=$vote['vote_users_url']?>" class="btn btn-success btn-sm"><?=$lang['total_votes']?>: <?=$vote['vote_count']?></a><?php else: ?><?=$lang['total_votes']?>: <?=$vote['vote_count']?><?php endif ?></div>
                <?php if ($vote['can_vote']): ?>
                    <div class="col-xs-6 text-right"><a href="<?=$vote['vote_url']?>" class="btn btn-primary btn-sm"><?=$lang['vote']?></a></div>
                <?php endif ?>
            </div>
        </div>
    <?php else: ?>
        <div class="panel-body">
        <form action="<?=$vote['vote_form_url']?>" method="post">
            <?php foreach ($vote['poll_options'] as $option): ?>
            <div class="form-group">
                <div class="radio margin-top-none"><label><input type="radio" value="<?=$option['id']?>" name="vote" /> <?=$option['text']?></label></div>
            </div>
            <?php endforeach ?>
            <div class="form-group"><div class="pull-right"><input type="submit" name="submit" value="<?=$lang['vote']?>" class="btn btn-primary btn-sm" /><a href="<?=$vote['vote_result_url']?>" class="btn btn-success btn-sm margin-left"><?=$lang['results']?></a></div></div>
        </form>
        </div>
    <?php endif ?>
    </div>
<?php endforeach ?>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="clearfix margin-top margin-bottom"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>
<div class="panel panel-primary">
<?php foreach ($posts as $post): ?>
    <div class="post clearfix">
        <div class="title" id="post<?=$post['id']?>">
            <div class="row">
                <div class="col-xs-6"><?=$post['time']?></div>
                <div class="col-xs-6 text-right"><a href="#post<?=$post['id']?>" title="Link to post" class="anchor">#<b><?=$post['position']?></b></a></div>
            </div>
        </div>
        <div class="postprofile" itemscope="itemscope" itemtype="http://data-vocabulary.org/Person">
            <div class="postprofileInner">
                <div class="profileAvatar"><img src="<?=$post['author_avatar']?>" width="32" height="32" alt="<?=$post['author_name']?>" /></div>
                <div class="profileInfo">
                    <div class="author"><a href="<?=$post['author_profile_url']?>" class="<?=$post['author_html_class']?>"><b itemprop="name"><?=$post['author_name']?></b></a> <img src="<?=$site_path?>/assets/images/o<?php if ($post['author_online']): ?>n.gif" alt="[ON<?php else: ?>ff.gif" alt="[OFF<?php endif ?>]" /></div>
                    <div>Bài đăng: <?=$post['author_postforum']?></div>

                </div>
                <div class="profileMoreInfo">
                <?php if ($post['author_group']): ?>
                    <div><b itemprop="title"><?=$post['author_group']?></b></div>
                <?php endif ?>
                <?php if ($post['author_status']): ?>
                    <div class="status"><?=$post['author_status']?></div>
                <?php endif ?>
                </div>
            </div>
        </div>
        <div class="postbody">
            <div class="content<?=$post['html_class']?>">
                <?=$post['content']?>
            </div>
            <?php if ($post['edited']): ?>
                <div class="info gray"><small><?=$lang['edited']?> <b><?=$post['edit_user']?></b> (<?=$post['edit_time']?>)</small></div>
            <?php endif ?>
            <?php if ($post['has_attach']): ?>
                <div class="gray attach">
                    <div class="attach_file">
                        <div><strong><?=$lang['attached_file']?></strong></div>
                        <div><a href="<?=$post['attach_url']?>" class="noPusher"><?=$post['attach_name']?></a> (<?=$post['attach_size']?> KB, <?=$post['attach_downloads']?> <?=$lang['downloads']?>)</div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($post['menu']): ?>
                <div class="tools right"><?php foreach ($post['menu'] as $menu): ?><a href="<?=$menu['url']?>" class="btn btn-default btn-xs margin-left-sm"><?=$menu['name']?></a><?php endforeach?></div>
            <?php endif ?>
            <?php if ($post['likes']): ?>
                <div class="likes"><?=$post['likes']?></div>
            <?php endif ?>
            <?php if ($post['show_sub_info']): ?>
                <div class="sub">
                <?php if ($post['deleted']): ?>
                    <div class="red"><?=$lang['who_delete_post']?>: <b><?=$post['delete_user']?></b></div>
                <?php elseif ($post['delete_user']): ?>
                    <div class="green"><?=$lang['who_restore_post']?>: <b><?=$post['delete_user']?></b></div>
                <?php endif ?>
                <?php if ($rights == RIGHTS_MODER_FORUM || $rights >= RIGHTS_SUPER_MODER): ?>
                    <?php if ($post['ip_via_proxy']): ?>
                        <div class="gray"><b class="red"><a href="<?=$post['search_ip_url']?>"><?=$post['ip']?></a></b> - <a href="<?=$post['search_ip_via_proxy_url']?>"><?=$post['ip_via_proxy']?></a> - <?=$post['browser']?></div>
                    <?php else: ?>
                        <div class="gray"><a href="<?=$post['search_ip_url']?>"><?=$post['ip']?></a> - <?=$post['browser']?></div>
                    <?php endif ?>
                <?php endif ?>
                </div>
            <?php endif ?>
        </div>
    </div>
<?php endforeach ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top margin-bottom"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>
<?php if ($can_reply): ?>
<div class="panel panel-primary">
    <div class="panel-body">
        <form name="form" action="<?=$reply_form_action?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <?=$bbcode_editor?>
                <p><textarea rows="<?=$user['field_h']?>" name="msg" class="form-control"></textarea></p>
            </div>
            <div class="form-group">
                <div class="checkbox"><label><input type="checkbox" name="addfiles" value="1" /> <?=$lang['add_file']?></label></div>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="<?=$lang['write']?>" class="btn btn-primary" />
                <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" class="btn btn-success margin-left" />
                <input type="submit" name="preview" value="<?=$lang['preview']?>" class="btn btn-info margin-left" />
            </div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        </form>
    </div>
</div>
<?php endif ?>
</div>
<?php if ($readedUsers): ?>
    <div class="panel panel-primary margin-top">
        <div class="panel-heading">Người dùng đã xem (<?= count($readedUsers) ?>)</div>
        <div class="panel-body">
        <?php foreach ($readedUsers as $item): ?>
            <a href="<?=$item['profile_url']?>" class="<?=$item['html_class']?>"><?=$item['name']?></a>,
        <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
<?php if ($similar_threads): ?>
    <div class="panel panel-primary margin-top">
        <div class="panel-heading">Chủ đề tương tự</div>
        <div class="list-group list-group-sm">
        <?php foreach ($similar_threads as $item): ?>
            <div class="list-group-item"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
        <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
