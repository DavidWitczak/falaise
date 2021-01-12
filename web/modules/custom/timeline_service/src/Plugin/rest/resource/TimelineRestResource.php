<?php

namespace Drupal\timeline_service\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\media_entity\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "timeline_rest_resource",
 *   label = @Translation("Timeline rest resource"),
 *   uri_paths = {
 *     "canonical" = "/rest/timeline/{theme}"
 *   }
 * )
 */
class TimelineRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new TimelineRestResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('timeline_service'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @param string $payload
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($theme) {
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'objet');
    $query->condition('field_theme_collection', $theme);
    $result = $query->execute();

    $response = [];
    $i = 0;
    foreach ($result as $key => $value) {
      $node = Node::load($value);

      $media = $this->getMediaAttr($node->field_medias[0]->target_id);
      $url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $node->get('nid')->value);

      $response['events'][$i]['media']['caption'] = $node->getTitle();
      $response['events'][$i]['media']['thumbnail'] = $media['url'];
      $response['events'][$i]['media']['url'] = $media['url'];
      $response['events'][$i]['media']['link'] = $url;

      $response['events'][$i]['start_date']['month'] = $node->field_mois->value;
      $response['events'][$i]['start_date']['day'] = $node->field_jour->value;
      $response['events'][$i]['start_date']['year'] = $node->field_annee->value;

      $response['events'][$i]['text']['headline'] = $node->getTitle();
      $response['events'][$i]['text']['text'] = $node->field_chapo->value;
      $response['events'][$i]['unique_id'] = $node->id();
      $i++;
    }


    //desactive le cache
    //      $build = [];
    $build = array('#cache' => array('max-age' => 0,),);

    return (new ResourceResponse($response))->addCacheableDependency($build);
  }

  public function getMediaAttr($media_id) {
    $media_attr = array();
    //      $media = Media::load($media_id);
    $media = \Drupal::entityTypeManager()->getStorage('media')->load($media_id);
    $bundle = $media->bundle[0]->target_id;

    if ($bundle == 'image') {
      $img_entity = $media->get('field_media_image');
      $file = File::load($img_entity->target_id);
      $media_attr['url'] = file_url_transform_relative(ImageStyle::load('large')->buildUrl($file->uri->value));
    }

    return $media_attr;
  }

}
