<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>SCP - Login</title>
    <!-- CSS files -->
    <link rel="shortcut icon" href="../images/Faviconn.png" />
    <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" d-flex flex-column bg-white">
    <script src="./dist/js/demo-theme.min.js?1684106062"></script>
    <div class="row g-0 flex-fill">
        <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <h3 class="text-center">ACESSE COM SUAS CREDENCIAS</h3>
            <div class="container container-tight my-5 px-lg-5">
            <x-auth-session-status class="mb-4" :status="session('status')" />
                <form action="{{ route('login') }}" method="post" autocomplete="off" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Senha
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" />
                            <span class="form-check-label">Lembre-se de mim neste dispositivo</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-cyan w-100">Entrar</button>
                    </div>
                </form>
                <div class="flex items-center justify-center mt-4">
                    <div style="text-align:center;">
                        <img src="/images/teste.svg" alt="Logo do Sistema" style="display:block; margin:auto; width:180px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
            <!-- Photo -->
            <div class="bg-cover h-100 min-vh-100" style="background-image: url(/images/BACKGROUND.png)"></div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js?1684106062" defer></script>
    <script src="./dist/js/demo.min.js?1684106062" defer></script>
</body>

</html>