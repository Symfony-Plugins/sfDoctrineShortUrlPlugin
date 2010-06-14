<?php use_helper('I18N'); ?>
<div>
  <h1><?php echo __('Decode a short URL') ?></h1>

  <?php if (isset($shorturl_object)): ?>
    <?php if (false !== $shorturl_object): ?>
      <p class="notification">
        <?php
        $link = $shorturl_object->getLongurl();
        echo __('Here is the original url:<br /> %1%', array('%1%' => link_to($link, $link)));
        ?>
      </p>
    <?php else: ?>
      <p class="notification error">
        <?php echo __('This short url does not exist!') ?>
      </p>
    <?php endif; ?>
  <?php endif; ?>


  <form method="post" action="<?php echo url_for('@sfShortUrl_decode') ?>">
    <fieldset>
      <legend><?php echo __('URL details') ?></legend>
      <?php if ($form->hasGlobalErrors()): ?>
        <?php echo $form->renderGlobalErrors() ?>
      <?php endif; ?>

      <div class="required">
        <?php echo __($form['shorturl']->renderLabel()) ?>
        http://<?php echo sfContext::getInstance()->getRequest()->getHost() ?> /<?php echo $form['shorturl']->render() ?>
        <?php echo $form['shorturl']->renderError() ?>
      </div>
      <div>
        <?php echo $form->renderHiddenFields() ?>
        <input type="submit" value="<?php echo __('decode!') ?>" />
      </div>
    </fieldset>
  </form>
</div>