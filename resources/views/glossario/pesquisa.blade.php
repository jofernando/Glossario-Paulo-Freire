@extends('layouts.app')

@section('content')
<div style="padding-top: 80px;">
    <div class="imagem_fundo" style="background-image: url({{ asset('imagens/img_grande_50.png') }})">
        <div class="card-body menu_glossario">
            <div class="menu_glossario_design">
            <form method="POST" action="{{ route('pesquisa.nova') }}">
            @csrf
                <div class="row" style="margin: 1rem">
                    <div class="col-md-12">
                        <div class="row container">
                            <div class="col-xs-2">
                                <a href="{{ route('glossario') }}" style="margin: 5px;">Glossário</a>
                            </div>
                            <div class="col-xs-2">
                                <a href="{{ route('pesquisa') }}" style="margin: 5px;">Pesquisa</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 5px;">
                            <div class="row" style="margin-top: 3rem; margin-bottom: 1rem; justify-content: center; ">
                                <input class="col-sm-9 form-control" type="text" id="boxBuscar" name="buscaTodos" value="{{$resultado ?? ''}}" aria-label="Search" style="margin-right: 3px; background-color: white;">
                                <button id="buscar_botao" onclick="buscarTodos(buscar_botao)" class="col-sm-2 btn btn-outline-danger">Buscar</button>
                            </div>
                    </div>
                    @if ($errors->any())
                        <div class="col-md-12" style="margin-top: 5px;">
                            <ul class="row" style="margin-top: 0.05rem; margin-bottom: 0.1rem; justify-content: center;">
                                @foreach ($errors->all() as $error)
                                    <li class="alert alert-danger" role="alert">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-md-12" style="margin-bottom: 1px;">
                        <div class="row" style="margin-top: 1rem; margin-bottom: 1rem; justify-content: center; ">
                            <button id="todas_botao" class="btn" onclick="buscarTodos(todas_botao)">
                                <img src="{{ asset('icones/search.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxTodas" value="" type="hidden" name="buscaTodos">
                                <label class="campo_compartilhar_texto">Todas</label>
                            </button>
                            <button id="audio_botao" class="btn" onclick="buscarAudios(audio_botao)">
                                <img src="{{ asset('icones/audio.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxAudio" value="" type="hidden" name="buscaAudio">
                                <label class="campo_compartilhar_texto">Áudio</label>
                            </button>
                            <button id="video_botao" class="btn" onclick="buscarVideos(video_botao)">
                                <img src="{{ asset('icones/video.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxVideo" value="" type="hidden" name="buscaVideo">
                                <label class="campo_compartilhar_texto">Vídeo</label>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top: 5px;">
                        <div style="float: right"><a href="{{ route('listarPalavras') }}">Listar todas as palavras</a></div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@if (Route::currentRouteName() === 'pesquisa.nova' || Route::currentRouteName() === 'pesquisa.id')
<div class="row">
    <div class="col-sm-12" style="margin-bottom: 25px; margin-top: 25px;">
        <div style="margin-left: 12px;"><a id="titulo_busca">Áudios</a></div>
            <br>
        <div style="margin-left: 12px; margin-top: -35px;"><a id="subtitulo_busca">Resultado: {{$resultado}}</a><output id="letraSelecionada"></output></div>
    </div>
    <div class="col-sm-12">
        <ul class="list-group">
            @foreach ($trechosAudios as $trecho)
                @if($trecho->tipo_recurso == "áudio")
                <li class="list-group-item div_container">
                <div class="row">
                    <div class="col-sm-5">
                        <img src="{{ asset('imagens/imagem_audio.png') }}" alt="paper" style="width: auto; max-width: 100%">
                        @if ($trecho->arquivo_sd != '')
                        <audio id="my_audio_{{$trecho->id}}" class="col-sm-12" controls style="height: 35px;">
                            <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/mp3">
                            <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/flac">
                            <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/mp4">
                            <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/ogg">
                        </audio>
                        <input id="confirmacao" type="hidden" value="0"></input>
                        <script>
                            var audio = document.getElementById("my_audio_{{$trecho->id}}");
                            audio.onplay = function() {
                                var confirmacao = document.getElementById('confirmacao').value;
                                if (confirmacao = "0") {
                                    document.getElementById('confirmacao').value = "1";
                                    var xmlhttp = new XMLHttpRequest();
                                    var url = "{{ url( route('contarView', ['id' => $trecho->id ]) ) }}";
                                    xmlhttp.open("GET", url, true);
                                    xmlhttp.send();
                                }
                            };
                        </script>
                        @endif
                        <a class="subtitulo_container" href="{{$trecho->endereco_video}}" style="position: relative;left: 10px">Áudio completo</a>
                    </div>
                <div class="col">
                    <div class="row">
                        <div class="col-sm-12" style="padding-top: 1rem;">
                            <output style="width: 100%; word-wrap: break-word;">"{{$trecho->texto}}"</output>
                            <span class="subtitulo_container">{{$trecho->titulo_video}}</span>
                        </div>
                        <div class="col-sm-12" style="padding: 1rem;">
                            <output class="campo_contador">
                                <img src="{{ asset('icones/eye.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                <label class="campo_compartilhar_texto">{{$trecho->quant_views}}</label>
                            </output>
                            <span class="dropdown">
                                <button button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/share.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                    <label class="campo_compartilhar_texto">Compartilhar</label>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                    <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                    <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                    <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                </div>
                            </span>
                            @auth
                                <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" /><label class="campo_compartilhar_texto">Editar</label></button></a> 
                            @endauth 
                        </div>
                    </div>
                </div>
            </div>
            </li>
                @endif
            @endforeach
        </ul>
            <!-- <div class="div_mais_resultados"> 
                <div >
                    <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">1</output>
                    <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">2</output>
                    <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">3</output>
                    <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">4</output>
                    <a href="">Ver todos.</a>
                </div>
            </div> -->
    </div>
</div>
<div class="row">
    <div class="col-sm-12" style="margin-bottom: 25px; margin-top: 25px;">
        <div style="margin-left: 12px;"><a style="font-size: 25px; font-family:arial;">Vídeos</a></div>
            <br>
        <div style="margin-left: 12px; margin-top: -35px;"><a style="font-family:sans-serif; color: #aaaaaa;">Resultado: {{$resultado}} </a><output id="letraSelecionada"></output></div>
    </div>
    <div class="col-sm-12">
        <ul class="list-group">
            @foreach ($trechosVideos as $trecho)
                @if($trecho->tipo_recurso=="vídeo")
                <li class="list-group-item div_container">
                <div class="row">
                    <div class="col-sm-5">
                    @if ($trecho->arquivo_hd != '' || $trecho->arquivo_hd != '')
                        <div id="videojs" style="position: relative; height: 250px; max-width: 100%;">
                            <video id="my_video_{{ $trecho->id }}" class="video-js vjs-default-skin" onclick="contarView()" poster="{{ asset('imagens/imagem_video.png') }}" style="max-height: 100%; max-width: 100%">
                            </video>
                            <input id="confirmacao" type="hidden" value="0"></input>
                            <script>
                                videojs('my_video_{{ $trecho->id }}', {
                                controls: true,
                                plugins: {
                                videoJsResolutionSwitcher: {
                                    default: 'low', // Default resolution [{Number}, 'low', 'high'],
                                    dynamicLabel: true,
                                }
                                }
                                }, function(){
                                    var player = this;
                                    window.player = player
                                    player.updateSrc([
                                    {
                                        src: "{{ asset('storage/' . $trecho->arquivo_sd) }}",
                                        type: 'video/mp4',
                                        label: 'SD',
                                        res: 360
                                    },
                                    {
                                        src: "{{ asset('storage/' . $trecho->arquivo_hd) }}",
                                        type: 'video/mp4',
                                        label: 'HD',
                                        res: 720
                                    },
                                    ])
                                    player.on('resolutionchange', function(){
                                        console.info('Source changed to %s', player.src())
                                    })
                                })

                                function contarView() {
                                    var video = videojs('my_video_{{ $trecho->id }}');
                                    var confirmacao = document.getElementById('confirmacao').value;
                                    if (video.currentTime() == 0 && confirmacao == "0") {
                                        document.getElementById('confirmacao').value = "1";
                                        var xmlhttp = new XMLHttpRequest();
                                        var url = "{{ url( route('contarView', ['id' => $trecho->id ]) ) }}";
                                        xmlhttp.open("GET", url, true);
                                        xmlhttp.send();
                                    }
                                }
                            </script>
                        </div>
                    @else
                        <img src="{{ asset('imagens/imagem_video.png') }}" alt="paper" style="width: auto; max-width: 100%">
                    @endif
                        <a class="subtitulo_container" href="{{$trecho->endereco_video}}" style="position: relative; left: 10px;">Vídeo completo</a>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-12" style="padding-top: 1rem;">
                                <output style="width: 100%; word-wrap: break-word;">"{{$trecho->texto}}"</output>
                                <span  class="subtitulo_container" >{{$trecho->titulo_video}}</span>
                            </div>
                            <div class="col-sm-12" style="padding: 1rem;">
                                <output class="campo_contador">
                                    <img src="{{ asset('icones/eye.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                    <label class="campo_compartilhar_texto">{{$trecho->quant_views}}</label>
                                </output>
                                <span class="dropdown">
                                    <button button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/share.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                        <label class="campo_compartilhar_texto">Compartilhar</label>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                        <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                        <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                    </div>
                                </span>
                                @auth
                                    <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" /><label class="campo_compartilhar_texto">Editar</label></button></a> 
                                @endauth 
                            </div>
                        </div>
                    </div>
                </div>
                </li>
                @endif
            @endforeach
        </ul>
        <!-- <div class="div_mais_resultados">
            <div >
                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">1</output>
                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">2</output>
                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">3</output>
                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">4</output>
                <a href="">Ver todos.</a>
            </div>
        </div> -->
    </div>
</div>
@endif
            
<script type="text/javascript">
    function buscarTodos(id) {
        var formulario = id.id;
        var inputBox = document.getElementById("boxBuscar");

        document.getElementById("boxTodas").value = inputBox.value;
    }
    function buscarVideos(id) {
        var formulario = id.id;
        var inputBox = document.getElementById("boxBuscar");

        document.getElementById("boxVideo").value = inputBox.value;
    } 
    function buscarAudios(id) {
        var formulario = id.id;
        var inputBox = document.getElementById("boxBuscar");

        document.getElementById("boxAudio").value = inputBox.value;
    } 
</script>
<script type="text/javascript">
    function shareFacePopUp(url){
        window.open("https://www.facebook.com/sharer/sharer.php?u=" + url,  "minhaJanelaFB", "height=1000,width=1000");
    }

    function shareWhatsPopUp(url){
        window.open(" https://api.whatsapp.com/send?text=" + url,  "minhaJanelaWa", "height=1000,width=1000");
    }

    function shareTwitterPopUp(url){
        window.open("https://twitter.com/intent/tweet?url=" + url,  "minhaJanelaTw", "height=1000,width=1000");
    }
</script>
@endsection