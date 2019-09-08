<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ema Rosas</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="bg-grey-lightest w-full sm:p-4">
            <div class="flex flex-row items-center w-full p-4">
                <img src="assets/logo.jpg" class="w-20 h-20">
                <div class="flex flex-col">
                    <h1 class="px-2 text-sm font-bold uppercase">Rosas, Victor Emanuel</h1>
                    <h1 class="p-2 text-lg font-bold">Minería de Texto: Generación de resumen automático</h1>
                </div>

                <span class="flex flex-1"></span>
                <div class="flex flex-col text-right text-gray-800 font-bold">
                    <h1>Ingeniería en Informática</h1>
                    <h1>Facultad de Ingeniería</h1>
                    <h1>UCASAL</h1>
                </div>
            </div>

            <div class="flex flex-row items-start w-full relative">
                <div class="flex flex-col w-1/3 h-auto m-3 sticky pin bg-white rounded border">
                    @foreach ($json as $idx => $noticias)
                        <a href="#noticia-{{$idx}}" class="p-2 text-blue-500 border-b">{{$noticias->first()['Titulo']}}</a>
                    @endforeach
                </div>
                <div class="flex flex-col w-full sm:w-4/5 md:w-2/3 mx-auto">
{{--                    Por cada noticia--}}
                    @foreach ($json as $idx => $noticias)
                        <div id="noticia-{{$idx}}" class="my-3 p-3 rounded border bg-white leading-loose">
{{--                            Por cada párrafo--}}
                            @foreach ($noticias as $noticia)
                                @if($loop->first)
{{--                                    Sólo si es el primer párrafo muestra el link, título y copete--}}
                                    <a href="{{$noticia['URL']}}" class="py-2 text-blue-500">{{$noticia['URL']}}</a>
                                    <h1 class="py-2 text-3xl font-bold leading-tight">{{$noticia['Titulo']}}</h1>
                                    <h2 class="mb-2 p-2 bg-gray-300 font-bold rounded">{{$noticia['Copete']}}</h2>
                                @endif

                                @if($noticia['prediction(Category)'] == 'resumen' || $noticia['Category'] == 'resumen')
{{--                                    Resalta y/o subraya--}}
                                    <span class="@if($noticia['prediction(Category)'] == 'resumen') bg-yellow-300 @endif @if($noticia['Category'] == 'resumen') border-b-4 border-blue-500 @endif" >{{$noticia['Texto']}}</span>
                                @else
{{--                                    Texto normal--}}
                                    <p>{{$noticia['Texto']}}</p>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>
