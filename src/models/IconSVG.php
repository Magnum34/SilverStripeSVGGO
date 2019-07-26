<?php
/**
 * Created by PhpStorm.
 * User: magnum34
 * Date: 21.07.2019
 * Time: 21:29
 */

namespace Magnum34\SilverStripeSVGGO\Models;

use SilverStripe\Assets\File;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
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

    protected $cssClass;


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', TextField::create('Title', 'Title'));
        $fields->addFieldToTab('Root.Main',
            UploadField::create('Image', 'Image Icon')
                ->setAllowedExtensions(['svg', 'png', 'jpg', 'jpeg'])
                ->setRightTitle('(only .svg , .jpg, .png, .jpeg)'));

        return $fields;
    }

    public function getCMSValidator()
    {
        return new RequiredFields([
            'Title'
        ]);
    }


    private static $summary_fields = [
        'Title' => 'Title',
        'Thumbnail' => 'Thumbnail'
    ];

    /**
     * @param string $class
     * @return $this
     */
    public function setCSSClass($class)
    {
        $this->cssClass = $class;
        return $this;
    }

    /**
     * Gets the classes for this link.
     *
     * @return array|string
     */
    public function getClasses()
    {
        $classes = explode(' ', $this->cssClass);
        $this->extend('updateClasses', $classes);
        $classes = implode(' ', $classes);
        return $classes;
    }


    public function getThumbnail()
    {
        if ($this->Image()->ID) {

            if ($this->Image()->getExtension() == 'svg') {
                $obj = DBHTMLText::create();
                $obj->setValue(file_get_contents(BASE_PATH . $this->Image()->Link()));
                return ($obj);
            } else {
                return $this->Image()->CMSThumbnail();
            }
        } else {
            return '(No Image)';
        }
    }

    public function getTitleThumbnail()
    {

        if ($this->Image()->ID) {
            $image = $this->Image()->Link();
            return LiteralField::create('TitleThumbnail', "<p>$this->Title</p><img src=\"$image\" width=\"50\" height=\"50\" ");

        } else {
            return $this->Title;
        }
    }


    /**
     * Renders an HTML anchor tag for this link
     *
     * @return DBHTMLText|string
     */
    public function forTemplate()
    {
        $link = $this->Image()->URL;
        $svg = $this->Image()->SVGRAW();
        $extraClass = $this->getClasses();
        if ($this->Image->isSVG()) {
            return "<div class=\"$extraClass\">$svg</div>";
        } else {
            return "<img class=\"$extraClass \" src=\"$link\" alt=\"icon avatar\">";
        }

    }


    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = array())
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}
