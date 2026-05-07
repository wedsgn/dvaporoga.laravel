@props([
    'submitText' => 'Отправить',
    'policyUrl' => asset('docs/ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ.docx'),
    'consentUrl' => asset('docs/СОГЛАСИЕ НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ.docx'),
])

Даю
<a href="{{ $consentUrl }}" target="_blank" rel="noopener noreferrer">
    согласие
</a>
на обработку персональных данных.
Подробнее — в
<a href="{{ $policyUrl }}" target="_blank" rel="noopener noreferrer">
    Политике
</a>.
