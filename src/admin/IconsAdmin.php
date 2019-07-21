<?php

namespace Magnum34\SilverStripeSVGGO\Admin;

use SilverStripe\Admin\ModelAdmin;

/**
 * Created by PhpStorm.
 * User: mariusz
 * Date: 19.07.19
 * Time: 09:36
 */

class IconsAdmin extends ModelAdmin
{
  private static $url_segment = 'icons';

  private static $managed_models = [
    IconSVG::class,

  ];

  private static  $menu_icon_class  = 'font-icon-picture';

  private static $menu_title = 'Icons';

  public function getEditForm($id = null, $fields = null)
  {

    $form = parent::getEditForm($id, $fields);

    return $form;
  }

}
