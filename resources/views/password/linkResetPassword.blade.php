@component('mail::message')

Olá, {{ $customerName }}.

Recebemos uma solicitação para alteração de senha!

Se foi você, clique no botão abaixo para criar uma nova senha:

@component('mail::button', ['url' => $resetPasswordLink, 'color' => 'success'])
Gerar nova senha
@endcomponent

Caso contrário, apenas ignore.

@endcomponent