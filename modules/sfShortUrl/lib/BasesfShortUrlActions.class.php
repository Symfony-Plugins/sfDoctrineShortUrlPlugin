<?php

/**
 * shorturl actions.
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage sfShortUrl
 * @author     Xavier Lacot <xavier@lacot.org>
 */
class BasesfShortUrlActions extends sfActions
{
  public function executeDecode(sfWebRequest $request)
  {
    $form = new sfShortUrlDecodeForm();
    $parameters = $request->getParameter($form->getName());
    $form->setDefaults($parameters);

    if ($request->getMethod() === sfRequest::POST)
    {
      $form->bind(
        $request->getParameter($form->getName())
      );

      if ($form->isValid())
      {
        $this->shorturl_object = $form->getShortUrl();
      }
    }

    $this->form = $form;
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $form = new sfShortUrlForm();

    if ($request->getMethod() === sfRequest::POST)
    {
      $form->bind(
        $request->getParameter($form->getName())
      );

      if ($form->isValid())
      {
        $this->shorturl_object = $form->save();
      }
    }

    $this->form = $form;
  }

  public function executeShorturl(sfWebRequest $request)
  {
    $shorturl = Doctrine::getTable('sfShortUrl')->findOneByShorturl($request->getParameter('shorturl'));
    $this->forward404Unless($shorturl && $shorturl->getIsEnabled());
    $shorturl->setViewcount($shorturl->getViewcount() + 1);
    $shorturl->setLastVisitedAt(date('Y-m-d H:i:s', time()));
    $shorturl->save();
    $this->redirect($shorturl->getLongurl(), 301);
  }
}
