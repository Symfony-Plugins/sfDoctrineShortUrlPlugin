<?php

/**
 * PluginsfShortUrl form.
 *
 * @package    sfDoctrineShortUrlPlugin
 * @subpackage form
 * @author     Xavier Lacot <xavier@lacot.org>
 */
abstract class PluginsfShortUrlForm extends BasesfShortUrlForm
{
  public function setup()
  {
    parent::setup();
    unset($this['id']);
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['viewcount']);
    unset($this['is_enabled']);
    unset($this['last_visited_at']);
    $this->setWidget('longurl', new sfWidgetFormInputText());

    $this->validatorSchema['longurl'] = new sfValidatorAnd(
      array(
        new sfValidatorUrl(
          array('required' => true),
          array(
            'invalid'  => 'This is not a valid url',
            'required' => 'Please type in a url'
          )),
        new sfValidatorRegex(
          array(
            'pattern'  => '~^(?:(?!('.sfContext::getInstance()->getRequest()->getHost().')).)*$~ix'
          ),
          array(
            'invalid'  => 'This url is not allowed.'
          )
        )
      ),
      array('required' => true),
      array(
        'required' => 'Please type in a url'
      )
    );
    $this->validatorSchema['shorturl'] = new sfValidatorAnd(
      array(
        new sfValidatorString(),
        new sfValidatorRegex(
          array(
            'pattern'    => sprintf(
              '~^((%s)|(\s+)(.*)|(.*)(\s+)|(\s+)(.*)(\s+))$~ix',
              implode('|', sfConfig::get('app_sfDoctrineShortUrlPlugin_forbidden_keywords', array('about')))
            ),
            'must_match' => false
          ),
          array(
            'invalid'  => 'This shorturl is not allowed.'
          )
        )
      ),
      array('required' => false)
    );

    $this->widgetSchema->setLabels(array(
      'longurl'   => 'Enter a long url',
      'shorturl'  => 'Optionnaly, define your own short url'
    ));

    $this->setDefault('longurl', 'http://');

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(
        array(
          'required' => false,
          'model'    => 'sfShortUrl',
          'column'   => 'shorturl',
        ),
        array(
          'invalid'  => 'This short url is already used!'
        )
      )
    );
  }

  protected function doSave($con = null)
  {
    $this->object = Doctrine::getTable('sfShortUrl')->generate(
      $this->getValue('longurl'),
      $this->getValue('shorturl')
    );
    return $this->object;
  }
}
