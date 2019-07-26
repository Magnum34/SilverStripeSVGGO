<?php

namespace SilverStripeSVGGO\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Created by PhpStorm.
 * User: magnum34
 * Date: 21.07.2019
 * Time: 21:26
 */

class SVGExtension extends DataExtension
{
  /**
   * Check file extension SVG
   * @return boolean
   */
  public function isSVG(){
    if($this->owner->getExtension()=='svg') return true;
    return false;
  }

  /**
   *
   * SVG RAW LINE
   * @return string|boolean
   */
  public function SVGRAW(){
    if($this->owner->getExtension() != 'svg'){
      return false;
    }
    $metadata = $this->owner->File->getMetadata();
    if(array_key_exists('path', $metadata)){
      $filePath = ASSETS_PATH . DIRECTORY_SEPARATOR . $metadata['path'];
      if (file_exists($filePath)) {

        $data = file_get_contents($filePath);
        if(strpos($data, 'svg') !== false){
          $buffer = trim(preg_replace( "/\r|\n/", "",$data));
          $html = DBHTMLText::create();
          $html->setValue($buffer);
          return $html;
        }
      }

    }
    return false;
  }


  public function ImageOrSVG(){
    if($this->SVGRAW()){
      return $this->SVGRAW();
    }
    return $this->owner->File->getURL();
  }

  public function getThumbnail(){
    if($this->owner->ID){

      if($this->owner->getExtension() == 'svg'){
        $obj= DBHTMLText::create();
        $obj->setValue(file_get_contents(BASE_PATH.$this->owner->Link()));
        return ($obj);
      }else {
        return $this->owner->CMSThumbnail();
      }
    }
    else
    {
      return '(No Image)';
    }
  }
}
