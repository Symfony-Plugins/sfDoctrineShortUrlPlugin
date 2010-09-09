<?php
/**
 * PluginsfShortUrlTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage model
 * @author     Xavier Lacot <xavier@lacot.org>
 */
class PluginsfShortUrlTable extends Doctrine_Table
{
  /**
   * return true if this shorturl is already used
   *
   * @param $shorturl  a shorturl alias
   * @return boolean   whether or not this shorturl alias is already in use
   */
  public function alreadyExists($shorturl)
  {
    $q = Doctrine_Query::create()
      ->select('count(u.id) as total')
      ->from('sfShortUrl u')
      ->where('u.shorturl = ?', $shorturl);
    $result = $q->execute(null, Doctrine::HYDRATE_NONE);
    return ($result[0][0] > 0);
  }

  /**
   * Creates a shorturl object, based on its long and short urls.
   *
   * @param $longurl   string  The longurl to shorten
   * @param $shorturl  string  The alias to use
   *
   * @return object   An sfShortUrl object
   */
  public function createShortUrl($longurl, $shorturl)
  {
    $shorturl_object = new sfShortUrl();
    $shorturl_object->setLongurl($longurl);
    $shorturl_object->setShorturl($shorturl);
    $shorturl_object->setIsEnabled(true);
    $shorturl_object->setViewcount(0);
    $shorturl_object->setIsExternal(false);
    $shorturl_object->save();
    return $shorturl_object;
  }

  /**
   * Generates an unused short url alias
   *
   * @return string   an unused short url alias
   */
  public function generateAlias()
  {
    $acceptable = false;

    while (!$acceptable)
    {
      $alias = substr(md5(microtime()), 0, 5);
      $acceptable = !$this->alreadyExists($alias);
    }

    return $alias;
  }

  /**
   * Generate a short url, based on a long url and optionnally a desired alias
   *
   * If the alias is provided:
   *  - either it exists and is associated to the same longurl, then this
   *    alias is kept,
   *  - either it exists but is not associated to the same longurl, then we
   *    try to find if a shorturl already exist for this long url, or we
   *    create a new one,
   *  - either it doesn't exist and is then attached to the long url.
   *
   * If no alias is provided, we try to find if a shorturl already exist for
   * this long url, or we create a new one.
   *
   * @param $longurl   string  The longurl to shorten
   * @param $shorturl  string  Optionnal, a wished alias
   *
   * @return object   An sfShortUrl object
   */
  public function generate($longurl, $shorturl = '')
  {
    if ($shorturl)
    {
      // search if this short url is already in use
      $objects = $this->findByShorturl($shorturl);

      if (isset($objects[0]))
      {
        // if it is the case, is it the same long url ?
        if ($longurl == $objects[0]->getLongurl())
        {
          $shorturl_object = $objects[0];
        }
      }
      else
      {
        // create new short url
        $shorturl_object = $this->createShortUrl($longurl, $shorturl);
      }
    }

    if (!isset($shorturl_object) || !$shorturl_object)
    {
      // try to retrieve a corresponding url within the public ones
      $shorturl_object = $this->findOneByLongurl($longurl);
    }

    if (!isset($shorturl_object) || !$shorturl_object)
    {
      // no acceptable object => create one
      $shorturl_object = $this->createShortUrl($longurl, $this->generateAlias());
    }

    return $shorturl_object;
  }

  public function generateWithRelShortlink($longurl, $shorturl = '')
  {
    // try to retrieve a corresponding url within the public ones
    $shorturl_object = $this->findOneByLongurl($longurl);

    if (!isset($shorturl_object) || !$shorturl_object)
    {
      // grab the page
      $browser = new sfWebBrowser();
      $selector = $browser->get($longurl)->getResponseDomCssSelector();

      // search for a shortlink in the page
      $link = $selector->matchSingle('head link[rel=shortlink]')->getNode();

      if ($link)
      {
        $extracted_shorturl = $link->getAttribute('href');
      }

      // if not found, generate our shorturl
      if (isset($extracted_shorturl) && $extracted_shorturl)
      {
        $shorturl_object = new sfShortUrl();
        $shorturl_object->setLongurl($longurl);
        $shorturl_object->setShorturl($extracted_shorturl);
        $shorturl_object->setIsEnabled(true);
        $shorturl_object->setViewcount(0);
        $shorturl_object->setIsExternal(true);
        $shorturl_object->save();
      }
      else
      {
        $shorturl_object = $this->generate($longurl, $shorturl);
      }
    }

    return $shorturl_object;
  }
}