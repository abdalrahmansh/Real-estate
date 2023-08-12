<!DOCTYPE html>
<html dir="rtl">
  <head>
    <title>هل نسيت كلمة المرور</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,600;0,700;0,800;0,900;0,1000;1,500;1,600;1,700;1,800;1,900;1,1000&family=Tajawal:wght@200;300;400;500;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="{{ asset('style.css') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  </head>
  <body>
    <div class="container">
      <div class="login-content">
        <form method="POST" action="{{ route('password.reset') }}">
          @csrf
          <span class="title">يرجى إدخال بريدك الإلكتروني للبحث عن حسابك.</span>
          <div class="div">
            <input
              type="email"
              name="email"
              placeholder="الايميل"
              required
              className=" form-control w-75"
            />
          </div>
          <button type="submit" class="btn" value="Login">
            ارسال
          </button>
        </form>
      </div>
    </div>
    <script type="text/javascript" src="{{ asset('main.css') }}"></script>
  </body>
</html>
