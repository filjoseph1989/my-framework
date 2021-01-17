<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-witdh, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Default Page</title>
        <link rel="stylesheet" href="/css/main.css?v=0.2">
    </head>

    <body>
        <div class="main">
            <div class="bg-teal-700 navigation">
                <div class="h-full navigation-container">
                    <div class="brand w-full">
                        <strong>Personal Framework</strong>
                    </div>
                </div>
            </div>

            <div class="bg-teal-600 content">
                <div class="content-container pb-5">
                    <div class="post">
                        <div class="bg-gray-100 card p-3 rounded shadow">
                            <h1 class="text-2xl">Welcome</h1>
                            <p class="mb-2">A framework documentation</p>
                            <ul>
                                <li>Version: {{ asset_version() }}</li>
                                <li>Author: Fil Joseph Beluan</li>
                                <li>Email: fil joseph 22 @ gmail dot com</li>
                            </ul>
                        </div>

                        @include('docs.requirement')

                        @include('docs.controller')

                        @include('docs.model')

                        @include('docs.helpers')
                    </div>
                </div>
            </div>
        </div>

        <script src="/js/main.js?v=64" charset="utf-8"></script>
    </body>
</html>
