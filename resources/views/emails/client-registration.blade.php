@component('mail::message')
# Finalisez votre inscription

Bonjour {{ $client->nom }},

Suite à votre demande de visite, un compte a été créé pour vous sur notre plateforme. Pour finaliser votre inscription et suivre l'état de votre demande, veuillez cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => $completionUrl])
Finaliser mon inscription
@endcomponent

Lors de la finalisation, vous pourrez définir votre propre mot de passe pour accéder à votre compte.

Si vous n'avez pas effectué cette demande, vous pouvez ignorer cet email.

Merci,<br>
{{ config('app.name') }}
@endcomponent