<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Nhà kho';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/farm/', 'Nông trại');
$breadcrumb->add('Nhà kho');
$_breadcrumb = $breadcrumb->out();

$tabs = [
	['name' => 'Sản phẩm', 'url' =>  'warehouse', 'active' => ($mod != 'item')],
	['name' => 'Vật phẩm', 'url' =>  'warehouse?mod=item', 'active' => ($mod == 'item')],
];

$_tab_template = [
    'inactive'  => '<a href="{url}">{name}</a>',
    'active'    => '{name}',
    'delimiter' => ' | ',
    'container' => '%s'
];

function display_tabs($tabs) {
	global $_tab_template;

    $return = array();

    foreach ($tabs as $tab) {
        if ($tab['active']) {
            $return[] = str_replace(
                array('{url}', '{name}'),
                array($tab['url'], $tab['name']),
                $_tab_template['active']
            );
        } else {
            $return[] = str_replace(
                array('{url}', '{name}'),
                array($tab['url'], $tab['name']),
                $_tab_template['inactive']
            );
        }
    }

    return sprintf($_tab_template['container'], implode($_tab_template['delimiter'], $return));
}
echo '<div class="farm_wrapper">';
switch ($mod) {
	case 'item':
		echo '<div class="phdr">' . display_tabs($tabs) . '</div>';
		$stmt = mysql_query('SELECT `item_id`, `count` FROM `' . FARM_ITEMS_TABLE . '` WHERE `user_id` = "'. $user_id .'" AND `count` > 0');
		if (mysql_num_rows($stmt)) {
			while ($res_w = mysql_fetch_assoc($stmt)) {
				echo '<div class="menu"><img src="' . SITE_URL . '/assets/farm/item/i_'. $res_w['item_id'] .'.png" /> - '. $farm_items[$res_w['item_id']]['name'] .' ('. $res_w['count'] .')</div>';
			}
		} else {
			echo '<div class="menu">Chưa có gì trong kho vật phẩm!</div>';
		}
		break;

	default:
		if (isset($_SESSION['sell_success'])) {
			echo '<div class="alert alert-success">Bán thành công! Bạn nhận được ' . $_SESSION['sell_success'] . ' xu.</div>';
			unset($_SESSION['sell_success']);
		}

		$stmt = mysql_query('SELECT `product_id`, `count` FROM `' . FARM_WAREHOUSE_TABLE . '` WHERE `user_id` = "'. $user_id .'" AND `count` > 0');
		if (mysql_num_rows($stmt)) {
			if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
				$sell = isset($_POST['sell']) && is_array($_POST['sell']) ? $_POST['sell'] : [];
				if ($sell) {
					$coin_plus = 0;
					$sell_id = array();
					while ($res_w = mysql_fetch_assoc($stmt)) {
						if (in_array($res_w['product_id'], $sell)) {
							$sell_id[] = $res_w['product_id'];
							$coin_plus += $farm_products[$res_w['product_id']]['price'] * $res_w['count'];
						}
					}
					if (empty($sell_id)) {
						echo '<div class="alert alert-warning">Vật phẩm không tồn tại!<br/><a href="warehouse">' . $lng['back'] . '</a></div>';
					} else {
						mysql_query('UPDATE `' . FARM_WAREHOUSE_TABLE . '` SET `count` = "0" WHERE `product_id` IN ('. implode(', ', $sell_id) .') AND `user_id` = "'. $user_id .'"');
						mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = "'. ($datauser['coin'] + $coin_plus) .'" WHERE `id` = "'. $user_id .'"');
						$_SESSION['sell_success'] = $coin_plus;
						header('Location: warehouse'); exit;
					}
				} else {
					echo '<div class="alert alert-warning">Bạn chưa chọn vật phẩm cần bán!<br/><a href="warehouse">' . $lng['back'] . '</a></div>';
				}
			} else {
				$token = mt_rand(10000, 99999);
				$_SESSION['token'] = $token;
				echo '<form action="warehouse" method="post" class="farm"><div class="phdr">' . display_tabs($tabs) . '</div>';
				while ($res_w = mysql_fetch_assoc($stmt)) {
					echo '<div class="menu"><input type="checkbox" name="sell[]" value="'. $res_w['product_id'] .'" /><img src="' . SITE_URL . '/assets/farm/item/p_'. $res_w['product_id'] .'.png" /> - '. $farm_products[$res_w['product_id']]['name'] .' ('. $res_w['count'] .')<br />Giá bán: '. ($farm_products[$res_w['product_id']]['price'] * $res_w['count']) .' xu</div>';
				}
				echo '<div class="action"><input type="hidden" name="token" value="'. $token .'" /><input type="submit" name="submit" value=" Bán " class="btn btn-primary" /></div></form>';
			}
		} else {
			echo '<div class="phdr">' . display_tabs($tabs) . '</div>' .
				'<div class="menu">Chưa có gì trong kho sản phẩm!</div>';
		}
}
echo '</div>';

$tpl_data['rendered_content'] = ob_get_clean();
