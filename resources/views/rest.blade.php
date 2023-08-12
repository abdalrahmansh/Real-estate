<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,600;0,700;0,800;0,900;0,1000;1,500;1,600;1,700;1,800;1,900;1,1000&family=Tajawal:wght@200;300;400;500;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('style.css') }}" />
    <title>اعادة تعيين كلمة المرور</title>
</head>

<body>
    <div class="container">
        <div class="login-content">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('new.password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ request('token') }}">
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <span class="title">اعادة تعيين كلمة المرور : </span>
                    <div class="div">
                        <input type="password" name="password" placeholder="كلمة المرور الجديدة" required
                            className=" form-control w-75" />
                        <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" required
                            className=" form-control w-75" />
                    </div>
                    <button type="submit" class="btn" value="Login">
                        تاكيد
                    </button>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('main.css') }}"></script>
</body>

</html>
