<?php

class sfShortUrlDecodeForm extends sfForm
{
  public function setup()
  {
    parent::setup();

    $this->setWidgets(array(
      'shorturl'        => new sfWidgetFormInputText(),
    ));

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
        ),
        new sfValidatorRegex(
          array(
            'pattern'    => '~^([A-Za-z0-9_-\s]+)$~ix',
            'must_match' => true
          ),
          array(
            'invalid'  => 'This shorturl is not allowed.'
          )
        )
      ),
      array('required' => true)
    );

    $this->widgetSchema->setLabels(array(
      'shorturl'  => 'Decode the following short url'
    ));

    $this->widgetSchema->setNameFormat('sf_short_url[%s]');
  }

  public function getShortUrl()
  {
    return Doctrine::getTable('sfShortUrl')->findOneByShortUrl($this->values['shorturl']);
  }
}