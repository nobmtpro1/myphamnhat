<?php

defined('ABSPATH') || exit;

if (\ShopEngine\Core\Builders\Action::is_edit_with_gutenberg($this->prod_tpl_id)) {
    shopengine_pro_content_render(do_blocks(get_the_content(null, false, $this->prod_tpl_id)));
} else {
    \ShopEngine\Core\Page_Templates\Hooks\Base_Content::instance()->load_content_designed_from_builder();
}
