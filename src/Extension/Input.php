<?php
declare(strict_types = 1);

namespace Soliant\FormidableBootstrap\Extension;

use DASPRiD\Formidable\Field;
use DASPRiD\Formidable\FormError\FormErrorSequence;
use DASPRiD\Formidable\Helper\ErrorFormatter;
use DASPRiD\Formidable\Helper\ErrorList;
use DASPRiD\Formidable\Helper\InputCheckbox;
use DASPRiD\Formidable\Helper\InputPassword;
use DASPRiD\Formidable\Helper\InputText;
use DASPRiD\Formidable\Helper\Select;
use DASPRiD\Formidable\Helper\Textarea;
use DOMDocument;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

final class Input implements ExtensionInterface
{
    /**
     * @var ErrorFormatter
     */
    private $errorFormatter;

    public function __construct(ErrorFormatter $errorFormatter)
    {
        $this->errorFormatter = $errorFormatter;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('formErrors', [$this, 'formErrors']);
        $engine->registerFunction('inputText', [$this, 'inputText']);
        $engine->registerFunction('inputPassword', [$this, 'inputPassword']);
        $engine->registerFunction('textarea', [$this, 'textarea']);
        $engine->registerFunction('select', [$this, 'select']);
        $engine->registerFunction('inputCheckbox', [$this, 'inputCheckbox']);
        $engine->registerFunction('formGroup', [$this, 'formGroup']);
    }

    public function formErrors(FormErrorSequence $formErrors) : string
    {
        if ($formErrors->isEmpty()) {
            return '';
        }

        $errorListHtml = (new ErrorList($this->errorFormatter))->__invoke($formErrors);

        $document = new DOMDocument('1.0', 'utf-8');
        $alertElement = $document->createElement('div');
        $alertElement->setAttribute('class', 'alert alert-danger');
        $document->appendChild($alertElement);

        $errorListDocument = new DOMDocument('1.0', 'utf-8');
        $errorListDocument->loadHTML($errorListHtml);

        foreach ($errorListDocument->getElementsByTagName('body')->item(0)->childNodes as $childNode) {
            $errorListNode = $document->importNode($childNode);
            $alertElement->appendChild($errorListNode);
        }

        return $document->saveHTML($alertElement);
    }

    public function inputText(string $label, Field $field, string $type = 'text') : string
    {
        return $this->formGroup(
            $label,
            $field,
            (new InputText())->__invoke($field, ['type' => $type, 'class' => 'form-control'])
        );
    }

    public function inputPassword(string $label, Field $field) : string
    {
        return $this->formGroup(
            $label,
            $field,
            (new InputPassword())->__invoke($field, ['class' => 'form-control'])
        );
    }

    public function textarea(string $label, Field $field) : string
    {
        return $this->formGroup(
            $label,
            $field,
            (new Textarea())->__invoke($field, ['class' => 'form-control'])
        );
    }

    public function select(string $label, Field $field, array $options, $multiple = false) : string
    {
        $htmlAttributes = ['class' => 'form-control'];

        if ($multiple) {
            $htmlAttributes['multiple'] = 'multiple';
        }

        return $this->formGroup(
            $label,
            $field,
            (new Select())->__invoke($field, $options, $htmlAttributes)
        );
    }

    public function inputCheckbox(string $label, Field $field) : string
    {
        return $this->formGroup(
            $label,
            $field,
            (new InputCheckbox())->__invoke($field, ['class' => 'form-control'])
        );
    }

    public function formGroup(string $label, Field $field, string $inputHtml) : string
    {
        $formGroupClasses = ['form-group'];

        if ($field->hasErrors()) {
            $formGroupClasses[] = 'has-error';
        }

        $document = new DOMDocument('1.0', 'utf-8');
        $formGroupElement = $document->createElement('div');
        $formGroupElement->setAttribute('class', implode(' ', $formGroupClasses));
        $document->appendChild($formGroupElement);

        $labelElement = $document->createElement('label');
        $labelElement->setAttribute('for', 'input.' . $field->getKey());
        $labelElement->appendChild($document->createTextNode($label));
        $formGroupElement->appendChild($labelElement);

        $inputDocument = new DOMDocument('1.0', 'utf-8');
        $inputDocument->loadHTML($inputHtml);

        foreach ($inputDocument->getElementsByTagName('body')->item(0)->childNodes as $childNode) {
            $inputNode = $document->importNode($childNode);
            $formGroupElement->appendChild($inputNode);
        }

        if ($field->hasErrors()) {
            // Only render out the first error
            $error = iterator_to_array($field->getErrors(), false)[0];
            $errorFormatter = $this->errorFormatter;

            $helpElement = $document->createElement('span');
            $helpElement->setAttribute('class', 'help-block');
            $helpElement->appendChild($document->createTextNode($errorFormatter(
                $error->getMessage(),
                $error->getArguments()
            )));
            $formGroupElement->appendChild($helpElement);
        }

        return $document->saveHTML($formGroupElement);
    }
}
