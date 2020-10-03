<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-witdh, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Default Page</title>
        <link rel="stylesheet" href="/css/main.css?v=0.1">
    </head>

    <body>
        <div class="main">
            <div class="bg-teal-700 navigation">
                <div class="h-full navigation-container">
                    <div class="brand w-full text-white">Personal Framework</div>
                </div>
            </div>

            <div class="bg-teal-600 content">
                <div class="content-container pb-5">
                    <div class="post">
                        <div class="bg-gray-100 card p-3 rounded shadow">
                            <h1 class="text-2xl">Welcome to this default view</h1>
                            <p class="mb-2">A framework documentation</p>
                            <ul>
                                <li>Version: {{ asset_version() }}</li>
                                <li>Author: Fil Joseph Beluan</li>
                                <li>Email: fil joseph 22 @ gmail dot com</li>
                            </ul>
                        </div>

                        <div class="bg-gray-100 card p-3 mt-3 rounded shadow">
                            <h2 class="text-2xl">Requirements:</h2>
                            <ul>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://www.php.net/">php 7.4</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://xdebug.org/">xdebug</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://nodejs.org/en/">nodejs</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://www.npmjs.com/">npm</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://webpack.js.org/">webpack</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://postcss.org/">postcss</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://tailwindcss.com/">tailwind</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://getcomposer.org/">composer</a>
                                </li>
                                <li class="list-decimal ml-4">
                                    <a class="text-teal-500" target="_blank" href="https://fontawesome.com/">fontawesome</a>
                                </li>
                            </ul>
                        </div>

                        @include('docs.controller')

                        @include('docs.model')

                        @include('docs.helpers')

                        <div class="bg-gray-100 card p-3 mt-3 rounded shadow">
                            <h2 class="text-2xl">Wanted to have with in the documentation:</h2>
                            <ul>
                                <li class="list-decimal ml-4">Dynamically update this doc</li>
                                <li class="list-decimal ml-4">Guide for installing xdebug in ubuntu</li>
                            </ul>
                        </div>

                        <div class="bg-gray-100 card p-3 mt-3 rounded shadow">
                            <h2 class="text-2xl">Contributing and License:</h2>
                            <ul>
                                <li class="list-decimal ml-4">Refer to:</li>
                                <li class="list-decimal ml-4">License at:</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/js/main.js?v=64" charset="utf-8"></script>
    </body>
</html>
