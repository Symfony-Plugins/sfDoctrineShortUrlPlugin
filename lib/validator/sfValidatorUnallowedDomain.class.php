<?php

/**
 * sfValidatorUnallowedDomain validates that the value is not one of the unallowed domains
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage validator
 * @author     Xavier Lacot <xavier@lacot.org>
 */
class sfValidatorUnallowedDomain extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('protocols', array('http', 'https', 'ftp', 'ftps'));
    $this->addMessage('domain_not_found', 'The domain name could not be found and validated.');
    $this->addMessage('invalid', 'This domain name is not allowed.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $pattern = sprintf(sfShortUrl::REGEX_URL_FORMAT, implode('|', $this->getOption('protocols')));
    preg_match($pattern, $value, $matches);
    $domain = isset($matches[2]) ? strtolower($matches[2]) : null;

    if (!$domain)
    {
      throw new sfValidatorError($this, 'domain_not_found', array('value' => $value));
    }

    if (in_array($domain, sfConfig::get('app_sfDoctrineShortUrlPlugin_unallowed_domains', array('xa.vc', 'xav.cc'))))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $value;
  }
}