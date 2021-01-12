<?php

namespace Drupal\twig_medias\TwigExtension;

use Drupal\image\Entity\ImageStyle;

/**
 * Class GetMediaInfoTwigExtension.
 */
class GetMediaInfoTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('getMediaInfos', array($this, 'getMediaInfos'), array('is_safe' => array('html'))),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'twig_medias.twig.extension';
  }

  public function getMediaInfos($media_id, $image_style = NULL) {
    $output = [];
    if ($media_id) {
      $media = \Drupal::entityTypeManager()->getStorage('media')->load($media_id);
      $bundle = $media->bundle();
      $output['name'] = $media->getName();
      $output['bundle'] = $bundle;

      if ($bundle == 'image') {
        $image = $media->get('field_media_image');
        $uri = $image->entity->getFileUri();
        $output['alt'] = $image->alt;
        $output['title'] = $image->title;

        if ($image_style != NULL) {
          $output['url'] = file_url_transform_relative(ImageStyle::load($image_style)->buildUrl($uri));
        }

        $output['url_full'] = file_url_transform_relative(ImageStyle::load('full_max')->buildUrl($uri));
      }
      elseif ($bundle == 'video') {
        $video = $media->get('field_media_oembed_video');
        $image = $media->get('thumbnail');
        $uri = $image->entity->getFileUri();

        if ($image_style != NULL) {
          $output['thumb'] = file_url_transform_relative(ImageStyle::load($image_style)->buildUrl($uri));
        }

        $output['url'] = 'https://player.vimeo.com/video/' . substr(parse_url($video->value, PHP_URL_PATH), 1);
      }
      elseif ($bundle == 'podcast') {
        $podacst = $media->get('field_media_soundcloud');
        $image = $media->get('thumbnail');
        $uri = $image->entity->getFileUri();

        if ($image_style != NULL) {
          $output['thumb'] = file_url_transform_relative(ImageStyle::load($image_style)->buildUrl($uri));
        }

        $output['url'] = $podacst->value;
      }
      elseif ($bundle == 'fichier') {
        $file = $media->get('field_media_file')->entity;
        $image = $media->get('field_vignette');
        $output["size"] = round($file->getSize() / (1024 * 1024), 2);

        $extension = explode("/", $file->getMimeType());
        $output["type"] = $extension[1];
        $uri = $image->entity->getFileUri();

        if ($image_style != NULL) {
          $output['thumb'] = file_url_transform_relative(ImageStyle::load($image_style)->buildUrl($uri));
        }

        $output['url'] = file_create_url($file->getFileUri());
      }
    }
    return $output;
  }
}
