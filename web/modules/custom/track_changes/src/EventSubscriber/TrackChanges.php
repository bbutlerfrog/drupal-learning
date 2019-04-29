<?php

namespace Drupal\track_changes\EventSubscriber;


use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event Subscriber MyEventSubscriber.
 */
class TrackChanges implements EventSubscriberInterface {
 
 /**
   * Code that should be triggered on event specified 
   */
  public function onRespond(FilterResponseEvent $event) {
    $user = \Drupal::currentUser();
    $uid = $user->id();
     //see if there are any changes the current user has not seen
    $query = \Drupal::entityQuery('change')
        ->condition('uid', $uid)
        ->condition('was_seen', 0);
    $entity_ids = $query->execute();
    if ($entity_ids) {
        $link = \Drupal\Core\Url::fromRoute('track_changes.show')->toString();
        //display a message on each page until this user dismisses it
        drupal_set_message(t('There have been some changes since the last time you logged in. 
            <a href="@link">Click here</a> to see them.', array('@link' => $link)));
    }
  }

   /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    return $events;
  }
}
