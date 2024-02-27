<?php
/**
 * @file
 * Contains \Drupal\carlyle_custom\Plugin\Field\FieldFormatter\CarlyleCustomLabelTranslatedFormatter.
 *
 * purpose of this format is to provide translation of label based on Current Language.
 * 
 */

namespace Drupal\carlyle_custom\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\Exception\UndefinedLinkTemplateException;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;

/**
 * Plugin implementation of the 'entity reference label' formatter.
 *
 * @FieldFormatter(
 *   id = "carlyle_custom_term_translated",
 *   label = @Translation("Label (translated)"),
 *   description = @Translation("Display the label of the referenced entities appropriate translation."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class CarlyleCustomLabelTranslatedFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'link' => TRUE,
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements['link'] = array(
      '#title' => t('Link label to the referenced entity'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('link'),
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = $this->getSetting('link') ? t('Link to the referenced entity') : t('No link');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();
    $output_as_link = $this->getSetting('link');
    
	  $current_language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    foreach ($this->getEntitiesToView($items, $current_language) as $delta => $entity) {
      $label = $entity->label();
      
      // If the link is to be displayed and the entity has a uri, display a
      // link.
      if ($output_as_link && !$entity->isNew()) {
        try {
          $uri = $entity->toUrl();
        }
        catch (UndefinedLinkTemplateException $e) {
          // This exception is thrown by \Drupal\Core\Entity\Entity::toUrl()
          // and it means that the entity type doesn't have a link template nor
          // a valid "uri_callback", so don't bother trying to output a link for
          // the rest of the referenced entities.
          $output_as_link = FALSE;
        }
      }

      if ($output_as_link && isset($uri) && !$entity->isNew()) {
        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $label,
          '#url' => $uri,
          '#options' => $uri->getOptions(),
        ];

        if (!empty($items[$delta]->_attributes)) {
          $elements[$delta]['#options'] += array('attributes' => array());
          $elements[$delta]['#options']['attributes'] += $items[$delta]->_attributes;
          // Unset field item attributes since they have been included in the
          // formatter output and shouldn't be rendered in the field template.
          unset($items[$delta]->_attributes);
        }
      }
      else {
        $elements[$delta] = array('#plain_text' => $label);
      }
      $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
    }
    if ($output_as_link) {
    	usort($elements, 'carlyle_custom_sortByTitle');
    }
    else {
			usort($elements, 'carlyle_custom_sortByPlainText');
		}
    return $elements;
  }
}

