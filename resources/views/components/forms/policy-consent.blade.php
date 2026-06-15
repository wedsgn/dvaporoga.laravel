@props([
    'submitText' => 'Отправить',
    'policyUrl' => asset('docs/ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ.docx'),
    'consentUrl' => asset('docs/СОГЛАСИЕ НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ.docx'),
])

Я соглашаюсь с
<a href="{{ $policyUrl }}" target="_blank" rel="noopener noreferrer">
    политикой конфиденциальности
</a>
и даю
<a href="{{ $consentUrl }}" target="_blank" rel="noopener noreferrer">
    согласие
</a>
на обработку персональных данных
