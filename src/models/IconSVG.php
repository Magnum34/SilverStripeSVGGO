<?php
/**
 * Created by PhpStorm.
 * User: magnum34
 * Date: 21.07.2019
 * Time: 21:29
 */

namespace Magnum34\SilverStripeSVGGO\Models;

use SilverStripe\Assets\File;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;



class IconSVG extends DataObject
{
  private static $db = [
    'Title' => 'Varchar(255)',
  ];

  private static $has_one = [
    'Image' => File::class,
  ];

  private static $owns = [
    'Image'
  ];


  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->addFieldToTab('Root.Main',
      UploadField::create('Image','Image Icon')
        ->setAllowedExtensions(['svg', 'png', 'jpg','jpeg'])
        ->setRightTitle('(only .svg , .jpg, .png, .jpeg)'));

    return $fields;
  }


  private static $summary_fields = [
    'Title' => 'Title',
    'Thumbnail' => 'Thumbnail'
  ];




  public function getThumbnail(){
    if($this->Image()->ID){

      if($this->Image()->getExtension() == 'svg'){
        $obj= DBHTMLText::create();
        $obj->setValue(file_get_contents(BASE_PATH.$this->Image()->Link()));
        return ($obj);
      }else {
        return $this->Image()->CMSThumbnail();
      }
    }
    else
    {
      return '(No Image)';
    }
  }

  public function getTitleThumbnail(){

    if($this->Image()->ID){
      $image = $this->Image()->Link();
      return LiteralField::create('TitleThumbnail', "<p>$this->Title</p><img src=\"$image\" width=\"50\" height=\"50\" ") ;

    }
    else
    {
      return $this->Title;
    }
  }

  public function canView ($member = null)
  {
    return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
  }

  public function canCreate ($member = null, $context = array ())
  {
    return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
  }

  public function canEdit ($member = null)
  {
    return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
  }

  public function canDelete ($member = null)
  {
    return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
  }
}
