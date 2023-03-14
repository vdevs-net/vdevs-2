<?php
    $this->layout('layout');
?>
<div class="topmenu"><a href="<?=$search_url?>"><?=$lang['search']?></a><?php if ($loged): ?> | <a href="<?=$thread_online_url?>"><?=$lang['who_here']?></a> <span class="red">(<?=$online_users?>&#160;/&#160;<?=$online_guests?>)</span><?php endif ?><?php if ($forum_unread_count): ?> | <a href="<?=$forum_unread_url?>"><?=$lang['unread']?></a>&#160;<span class="red">(<b><?=$forum_unread_count?></b>)</span><?php endif ?></div>
<div class="menu"><h1 class="topic-name"><?php if ($thread_deleted): ?><img src="<?=$site_path?>/assets/images/forbidden.png" class="icon" alt="[*]" /><?php endif ?><?php if ($thread_closed): ?><img src="<?=$site_path?>/assets/images/tz.gif" class="icon" alt="[*]" /><?php endif ?><?php if ($thread_prefix): ?><span class="label label-<?=$thread_prefix?>"><?=$thread_prefix_name?></span><?php endif ?><?=$thread_name?></h1></div>
<div class="menu"><a href="<?=$facebook_share_url?>">Share</a></div>
<?php if ($pagination): ?>
    <div class="menu"><?=$pagination?></div>
<?php endif ?>
<?php if ($thread_deleted): ?>
    <div class="rmenu"><?=$lang['topic_delete_who']?>: <b><?=$thread_delete_user?></b></div>
<?php elseif ($thread_delete_user && $rights >= 7): ?>
    <div class="gmenu"><small><?=$lang['topic_delete_whocancel']?>: <b><?=$thread_delete_user?></b></small></div>
<?php endif ?>
<?php if ($thread_closed): ?>
    <div class="rmenu"><?=$lang['topic_closed']?></div>
<?php endif ?>
<?php if ($has_vote): ?>
<?php foreach ($votes as $vote): ?>
    <?php if ($vote['show_vote_result']): ?>
        <div class="gmenu">
            <h5><?=$vote['vote_name']?></h5>
            <small>
            <?php foreach ($vote['poll_options'] as $option): ?>
                <div><?=$option['text']?> [<?=$option['count']?>]</div>
                <div class="poll-result"><div class="barContainer"><div class="bar bg<?=$option['html_class']?>" style="width: <?=$option['percent']?>%" title="<?=$lang['rating']?>: <?=$option['percent']?>%"></div></div> <?=$option['percent']?>%</div>
            <?php endforeach ?>
            </small>
        </div>
        <div class="bmenu"><?=$lang['total_votes']?>: <?php if ($rights > 6): ?><a href="<?=$vote['vote_users_url']?>"><?=$vote['vote_count']?></a><?php else: ?><?=$vote['vote_count']?><?php endif ?></div>
        <?php if ($vote['can_vote']): ?>
            <div class="bmenu"><a href="<?=$vote['vote_url']?>"><?=$lang['vote']?></a></div>
        <?php endif ?>
    <?php else: ?>
        <div class="gmenu">
            <h5><?=$vote['vote_name']?></h5>
            <form action="<?=$vote['vote_form_url']?>" method="post">
            <?php foreach ($vote['poll_options'] as $option): ?>
                <p><input type="radio" value="<?=$option['id']?>" name="vote" /> <?=$option['text']?></p>
            <?php endforeach ?>
                <p><input type="submit" name="submit" value="<?=$lang['vote']?>"/> <a href="<?=$vote['vote_result_url']?>"><?=$lang['results']?></a></p>
            </form>
        </div>
    <?php endif ?>
<?php endforeach ?>
<?php endif ?>
<?php foreach ($posts as $post): ?>
    <div class="post clearfix">
        <div class="title" id="post<?=$post['id']?>"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><?=$post['time']?></td><td align="right"><a href="#post<?=$post['id']?>" title="Link to post" class="anchor">#<b><?=$post['position']?></b></a></td></tr></table></div>
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
                <div class="info gray p4"><small><?=$lang['edited']?> <b><?=$post['edit_user']?></b> (<?=$post['edit_time']?>)</small></div>
            <?php endif ?>
            <?php if ($post['has_attach']): ?>
                <div class="gray attach">
                    <div class="attach_file">
                        <div><strong><?=$lang['attached_file']?></strong></div>
                        <div><a href="<?=$post['attach_url']?>"><?=$post['attach_name']?></a> (<?=$post['attach_size']?> KB, <?=$post['attach_downloads']?> <?=$lang['downloads']?>)</div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($post['menu']): ?>
                <div class="tools right"><?php foreach ($post['menu'] as $menu): ?><a href="<?=$menu['url']?>"><?=$menu['name']?></a><?php endforeach?></div>
            <?php endif ?>
            <?php if ($post['likes']): ?>
                <div class="likes"><?=$post['likes']?></div>
            <?php endif ?>
            <?php if ($post['show_sub_info']): ?>
                <div class="sub p4">
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
<?php if ($can_reply): ?>
    <div class="gmenu">
        <form name="form" action="<?=$reply_form_action?>" method="post" enctype="multipart/form-data">
            <p><textarea rows="<?=$user['field_h']?>" name="msg"></textarea></p>
            <label class="checkbox"><input type="checkbox" name="addfiles" value="1" /> <?=$lang['add_file']?></label>
            <p><input type="submit" name="submit" value="<?=$lang['write']?>" style="width: 79px;"/> <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" style="width: 79px;"/> <input type="submit" name="preview" value="<?=$lang['preview']?>" style="width: 79px;" /></p>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        </form>
    </div>
<?php endif ?>
<div class="phdr"><?=$lang['total']?>: <?=$post_count?></div>
<?php if ($thread_tags): ?>
    <div class="menu">Tags: <?=$thread_tags?></div>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<?php if ($thread_moder_menu): ?>
    <?php $array = []; ?>
    <div class="menu">
    <?php foreach ($thread_moder_menu as $thread_moder_menu_item): ?>
        <?php $array[] = '<a href="' . $thread_moder_menu_item['value'] . '">' . $thread_moder_menu_item['name'] . '</a>'; ?>
    <?php endforeach ?>
    <?=(implode(' | ', $array))?>
    </div>
<?php endif ?>
<?php if ($readedUsers): ?>
    <div class="box box-readed-user">
        <div class="phdr">Người dùng đã xem (<?= count($readedUsers) ?>)</div>
        <div class="menu">
        <?php foreach ($readedUsers as $item): ?>
            <a href="<?=$item['profile_url']?>" class="<?=$item['html_class']?>"><?=$item['name']?></a>,
        <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
<?php if ($similar_threads): ?>
    <div class="box box-similar">
        <div class="phdr">Chủ đề tương tự</div>
        <?php foreach ($similar_threads as $item): ?>
            <div class="menu"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
        <?php endforeach ?>
    </div>
<?php endif ?>
