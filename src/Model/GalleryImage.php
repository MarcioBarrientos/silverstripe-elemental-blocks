<?php

namespace Dynamic\Elements\Model;

use Dynamic\Elements\Elements\ElementPhotoGallery;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;

/**
 * Class GalleryImage.
 */
class GalleryImage extends BaseElementObject
{
    /**
     * @var string
     */
    private static $singular_name = 'Gallery Image';

    /**
     * @var string
     */
    private static $plural_name = 'Gallery Images';

    /**
     * @var array
     */
    private static $db = array(
        'SortOrder' => 'Int',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'PhotoGallery' => ElementPhotoGallery::class,
        // Image is covered by BaseElementObject
    );

    /**
     * @var array
     */
    private static $owns = array(
        'Image',
    );

    /**
     * @var array
     */
    private static $summary_fields = array(
        'Image.CMSThumbnail' => 'Image',
        'Title' => 'Title',
    );

    /**
     * @var array
     */
    private static $searchable_fields = array(
        'Title',
        'Content',
    );

    /**
     * @var string
     */
    private static $table_name = 'GalleryImage';

    /**
     * @return FieldList
     *
     * @throws \Exception
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(array(
            'SortOrder',
            'PhotoGalleryID',
            'ElementLinkID',
        ));
        $image = $fields->dataFieldByName('Image')->setFolderName('Uploads/Elements/PhotoGallery/');
        $fields->insertBefore($image, 'Content');

        // so if anything depends on PageLink it doesn't flake out
        $fields->replaceField('PageLink', new LiteralField('PageLink', ''));

        return $fields;
    }

    /**
     * @return null
     */
    public function getPage()
    {
        $page = null;

        if ($this->PhotoGallery()) {
            if ($this->PhotoGallery()->hasMethod('getPage')) {
                $page = $this->PhotoGallery()->getPage();
            }
        }

        return $page;
    }
}
