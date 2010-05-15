<?php use_helper('I18N'); ?>

<?php if (isset($shorturl_object)): ?>
  <p class="notification">
    <?php
    $link = url_for('@sfShorturl?shorturl='.$shorturl_object->getShorturl(), true);
    echo __('The short url is available at %1%.', array('%1%' => link_to($link, $link)));
    ?>
  </p>
<?php endif; ?>


<form method="post" action="<?php echo url_for('@sfShorturl_homepage') ?>">
  <fieldset>
    <legend><?php echo __('URL details') ?></legend>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <div class="required">
      <?php
      $attributes = array();

      if ($form['longurl']->hasError())
      {
        $attributes['class'] = 'error';
      }
      ?>
      <?php echo __($form['longurl']->renderLabel()) ?>
      <?php echo $form['longurl']->render($attributes) ?>
      <?php echo $form['longurl']->renderError() ?>
    </div>
    <div>
      <?php echo __($form['shorturl']->renderLabel()) ?>
      http://<?php echo sfContext::getInstance()->getRequest()->getHost() ?> /<?php echo $form['shorturl']->render() ?>
      <?php echo $form['shorturl']->renderError() ?>
    </div>
    <div>
      <?php echo $form->renderHiddenFields() ?>
      <input type="submit" value="<?php echo __('shorten!') ?>" />
    </div>
  </fieldset>
</form>