<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class FormHelpers
{
    public function getArrErrors(FormInterface $form): array
{
    $errors = [];

    // Ошибки на текущем уровне (без детей)
    foreach ($form->getErrors(false) as $error) {
        $errors[] = $error->getMessage();
    }

    foreach ($form->all() as $child) {
        $childErrors = $this->getArrErrors($child);
        if (!empty($childErrors)) {
            // Если у ребенка ошибки — добавляем их к текущему ключу с именем ребенка
            $errors[$child->getName()] = $childErrors;
        }
    }

    // Убираем дубликаты ошибок
    if (!empty($errors) && isset($errors[0])) {
        $errors = array_unique($errors);
        // Если кроме ошибок без ключа есть и вложенные поля — лучше разделить плоские ошибки и вложенные поля
        if (count($errors) === 1 && empty($errors[1])) {
            // Если ошибок ровно одна — просто возвращаем массив с одной ошибкой
            $errors = array_values($errors);
        }
    }

    return $errors;
}

}
