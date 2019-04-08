<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <label for="nm_usuario">Nome completo: </label>
            <input type="text" name="nm_usuario"><br>
            <label for="email">E-mail: </label>
            <input type="email" name="email"><br>
            <label for="login">Login: </label>
            <input type="text" name="login"><br>
            <label for="password">Senha: </label>
            <input type="password" name="password"><br>
            <label for="password_confirmation">Confirme a senha: </label>
            <input type="password" name="password_confirmation"><br>
            <button type="submit">Enviar</button>
        </form>
    </body>
</html>
    