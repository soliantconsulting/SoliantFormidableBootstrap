# Formidable Bootstrap

This package supplies a selection of view helpers to ease rendering of forms with
[Formidable](https://github.com/DASPRiD/Formidable) and [Twitter Bootstrap 3](https://getbootstrap.com/).
By default, it ships with a factory to use the helpers in [Plates](http://platesphp.com/), but the view helpers can also
be used in other template engines which support callables.

The supplied view helpers have a very opinionated HTML output. If you need a different kind of output, this library is
not for you, and you should write your own helpers.

## Installation

Install via composer:

```bash
$ composer require soliant/formidable-bootstrap
```

## Usage

Here is a simple example for using the different view helpers in your templates:

```html
<form method="POST">
    <?php echo $this->formErrors($form->getGlobalErrors()); ?>

    <?php echo $this->inputText('Name', $form->getField('name')); ?>
    <?php echo $this->inputText('Color', $form->getField('color'), 'color'); ?>
    <?php echo $this->inputPassword('Password', $form->getField('password')); ?>
    <?php echo $this->textarea('Notes', $form->getField('notes')); ?>
    <?php echo $this->select('Role', $form->getField('role'), ['user' => 'User', 'admin' => 'Admin']); ?>
    <?php echo $this->select('Groups', $form->getField('role'), ['alpha' => 'Alpha', 'beta' => 'Beta'], true); ?>
    <?php echo $this->select('Country', $form->getField('country'), [
        'Europe' => [
            'de' => 'Germany',
            'fr' => 'France',
        ],
        'America' => [
            'ca' => 'Canada',
            'us' => 'United States',
        ],
    ]); ?>
    <?php echo $this->checkbox('Active', $form->getField('active')); ?>
</form>
```

As you can see, some view helpers take additional optional parameters. The `inputText()` helper takes an type as third
parameter, By default, it will render a generic `text` input, but you can change it to any other input type.

The `select()` view helper takes a boolean as last argument, which indicates whether it should be `multiple` or not. The
mandatory `options` array can either be a simple string to string map, or contain nested arrays like in the country
example to generate option groups, which can be nested indefinitely.

If you need to separate your form into fieldsets, you can do that manually. There is no need for any additional helpers
there. When you have a child object in your form which can get errors assigned, so that they are not associated with a
specific field, you can pull the errors from a pseudo field and pass them to the `formErrors()` view helper:

```html
<fieldset>
    <legend>Address</legend>

    <?php echo $this->formErrors($form->getField('address')->getErrors()); ?>

    <?php echo $this->inputText('Street', $form->getField('address.street')); ?>
    <?php echo $this->inputText('City', $form->getField('address.city')); ?>
    <?php echo $this->inputText('Zipcode', $form->getField('address.zipcode')); ?>
</fieldset>
```

## Custom error messages

By default, the view helpers use error messages supplied by Formidable itself. If you want to use different messages or
have your own constraints with custom error messages, you add additional messages via configuration:

```php
return [
    soliant_formidable_bootstrap => [
        'messages' => [
            'error.custom' => 'Some custom error',
        ],
    ],
];
```

The messages follow the same pattern used by Formidable's
[`ErrorFormatter`](https://formidable.readthedocs.io/en/latest/built-in-helpers/#errorformatter).
