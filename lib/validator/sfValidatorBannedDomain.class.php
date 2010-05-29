<?php

/**
 * sfValidatorBannedDomain validates that the value is not one of the banned domains
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage validator
 * @author     Xavier Lacot <xavier@lacot.org>
 */
class sfValidatorBannedDomain extends sfValidatorBase
{
  const REGEX_URL_FORMAT = '~^
      (%s)://                                 # protocol
      (
        ([a-z0-9-]+\.)+[a-z]{2,6}             # a domain name
          |                                   #  or
        \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}    # a IP address
      )
      (:[0-9]+)?                              # a port (optional)
      (/?|/\S+)                               # a /, nothing or a / with something
    $~ix';

  /**
   * Configures the current validator.
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('protocols', array('http', 'https', 'ftp', 'ftps'));
    $this->addMessage('invalid', 'This domain name is not allowed.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $pattern = sprintf(self::REGEX_URL_FORMAT, implode('|', $this->getOption('protocols')));
    preg_match($pattern, $value, $matches);
    $domain = $matches[2];

    $query = Doctrine_Core::getTable('sfShortUrlBannedDomain')->createQuery()->where('domain = ?', $domain);

    if ($query->count())
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $value;
  }
}