<?php

namespace Drupal\carlyle_custom\Plugin\AudioPlayer;

use Drupal\audiofield\Plugin\AudioPlayer\DefaultMp3Player;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * @AudioPlayer (
 *   id = "carlyle_mp3_player",
 *   title = @Translation("Carlyle HTML5 mp3 player"),
 *   file_types = {
 *     "mp3",
 *   },
 *   description = "Carlyle-specific html5 player to play mp3 files."
 * )
 */
class CarlyleMp3Player extends DefaultMp3Player{

  /**
   * {@inheritdoc}
   */
  public function renderPlayer(FieldItemListInterface $items, $langcode, array $settings) {
    /** @var $url \Drupal\Core\Url */
    if (isset($items[0]) && $url = $this->getAudioRenderInfo($items[0])->url) {
      return [
        '#type' => 'html_tag',
        '#tag' => 'audio',
        '#attributes' => [
          'controls' => TRUE,
          'controlsList' => 'nodownload',
        ],
        '#value' => t("Your browser does not support the audio element."),
        'source' => [
          '#type' => 'html_tag',
          '#tag' => 'source',
          '#attributes' => [
            'src' => $url->toString(),
            'type' => 'audio/mpeg',
          ],
        ],
      ];
    }
    return [];
  }
}
