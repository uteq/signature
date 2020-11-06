<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body class="bg-dark">


<form class="form mt-2 center" action="{{ route('signature.validate_password_route', $key) }}" method="post">
    <div class="form-group m-5 mx-auto" style="max-width: 300px">
        <label for="password-input" class="text-white">Please provide the given password</label>
        <input id="password-input" class="form-control" type="password" placeholder="Password" name="password"/>
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <input type="submit" class="form-control mt-2 btn btn-primary" value="Submit">
    </div>
    @csrf

</form>

</body>
</html>
