<?php
namespace Drupal\track_changes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\track_changes\Entity\Change;



/**
 * Controller for class that will return changes to authenciated users upon login
 * 
 */

 class TrackChangesController {
     
    /**
     * Show changes from {site_root}/changelog.md
     *
     * @return array
     */
   public function show() {
      //$doc = new DOMDocument();
      //$changelog = $doc->loadHTMLFile
      $user = \Drupal::currentUser();
      $uid = $user->id();
      $query = \Drupal::entityQuery('change')
                ->condition('uid', $uid)
                ->condition('was_seen', 0);
      $entity_ids = $query->execute();
      //mark these changes saved, get rid of notice      
      foreach ($entity_ids as $key=>$id) {
         $change = Change::load($id);
         $change->was_seen->value = true;
         $change->save();
      }
      $changelog = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . '/changelog.md');
      drupal_get_messages();
      return [
        '#type' => 'markup',
        '#markup' => $changelog
      ];
   }

   public function close () {
      $user = \Drupal::currentUser();
      $uid = $user->id();
      $query = \Drupal::entityQuery('change')
                ->condition('uid', $uid)
                ->condition('was_seen', 0);
      $entity_ids = $query->execute();
      foreach ($entity_ids as $key=>$id) {
         //mark each message as dismissed
         $change = Change::load($id);
         $change->dismissed->value = true;
         $change->save();
      }      
      drupal_get_messages();
      $destination = Url::fromUserInput(\Drupal::destination()->get());
      return $this->redirect($destination);
   }

}


