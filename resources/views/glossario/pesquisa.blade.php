@extends('layouts.app')

@section('content')
<div style="padding-top: 80px;">
    <div class="imagem_fundo" style="background-image: url({{ asset('imagens/img_grande_50.png') }}); padding-bottom: 4rem;">
        <div class="card-body menu_glossario">
            <div class="menu_glossario_design">
            <form method="POST" action="{{ route('pesquisa.nova') }}">
            @csrf
                <div class="row" style="margin: 1rem">
                    <div class="col-md-12">
                        <div class="row container">
                            <div class="col-xs-2 link-menu-glossario">
                                <a href="{{ route('glossario') }}">
                                    <div onmousemove="this.children[0].src='{{ asset('icones/dicionario_azul.png') }}'" onmouseout="this.children[0].src='{{ asset('icones/dicionario_cinza.png')}}'">
                                        <img src="{{ asset('icones/dicionario_cinza.png') }}" alt="" width="22" height="auto" style="margin-right: 4px;">@lang('mensagens.Índices')
                                    </div>
                                </a>
                            </div>
                            &nbsp;&nbsp;
                            <div class="col-xs-2 link-menu-glossario" style="margin-top: 0.3%;">
                                <a href="{{ route('pesquisa') }}">
                                    <div onmousemove="this.children[0].src='{{ asset('icones/icone_pesquisar_azul.png') }}'" onmouseout="this.children[0].src='{{ asset('icones/icone_pesquisar_cinza.png')}}'">
                                        <img src="{{ asset('icones/icone_pesquisar_cinza.png') }}" alt="" width="22" height="auto">@lang('mensagens.Busca')
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 5px;">
                            <div class="row" style="margin-top: 3rem; margin-bottom: 1rem; justify-content: center; ">
                                <input class="col-sm-9 form-control" type="text" id="boxBuscar" name="buscaTodos" value="{{$resultado ?? ''}}" aria-label="Search" style="margin-right: 3px; background-color: white;">
                                <button id="buscar_botao" onclick="buscarTodos(buscar_botao)" class="col-sm-2 btn btn-outline-danger">@lang('mensagens.Busca')</button>
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
                            <button id="todas_botao" class="btn" onclick="buscarTodos(todas_botao)" style="color: #acabab;">
                                <img src="{{ asset('icones/search.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxTodas" value="" type="hidden" name="buscaTodos">
                                @lang('mensagens.Todas')
                            </button>
                            <button id="audio_botao" class="btn" onclick="buscarAudios(audio_botao)" style="color: #acabab;">
                                <img src="{{ asset('icones/audio.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxAudio" value="" type="hidden" name="buscaAudio">
                                @lang('mensagens.Áudio')
                            </button>
                            <button id="video_botao" class="btn" onclick="buscarVideos(video_botao)" style="color: #acabab;">
                                <img src="{{ asset('icones/video.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                <input id="boxVideo" value="" type="hidden" name="buscaVideo">
                                @lang('mensagens.Vídeo')
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <x-select-idioma/>
                            </div>
                            <div class="col-md-6">
                                <div style="float: right">
                                    @auth<a href="{{ route('verbete.add') }}">@lang('mensagens.Adicionar Verbete')</a> &nbsp;
                                    @endauth
                                    <a href=" {{ route('listarPalavras') }} ">@lang('mensagens.Listar todas as palavras')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@if (Route::currentRouteName() === 'pesquisa.nova' || Route::currentRouteName() === 'pesquisa.id')
<div class="container">
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px; margin-top: 25px;">
            {{-- <div style="margin-left: 12px;"><a id="titulo_busca">@lang('mensagens.Áudios')</a></div> --}}
            <div style="margin-left: 12px;"><a id="subtitulo_busca">@lang('mensagens.Resultado'): {{$resultado}}</a><output id="letraSelecionada"></output></div>
        </div>
    </div>
    <div class="row">
        {{-- <div class="col-sm-12" style="margin-bottom: 25px; margin-top: 25px;">
            <div style="margin-left: 12px;"><a id="titulo_busca">@lang('mensagens.Áudios')</a></div>
                <br>
            <div style="margin-left: 12px; margin-top: -35px;"><a id="subtitulo_busca">@lang('mensagens.Resultado'): {{$resultado}}</a><output id="letraSelecionada"></output></div>
        </div> --}}
        <div class="col-sm-12">
            <ul class="list-group">
                @foreach ($trechosAudios as $trecho)
                    @if($trecho->tipo_recurso == "áudio")
                    <!-- Modal -->
                    <div class="modal fade" id="excluirTrechoAudioModal_{{$trecho->id}}" tabindex="-1" role="dialog" aria-labelledby="excluirTrechoModalAudioLabel{{$trecho->id}}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="excluirVerbeteModalAudioLabel{{$trecho->id}}">@lang('mensagens.Confirmar')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @lang('mensagens.Confirmacao')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('mensagens.Nao')</button>
                                <a href="{{ route('trecho.del', ['id' => $trecho->id]) }}"><button type="button" class="btn btn-primary">@lang('mensagens.Sim')</button></a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <li class="list-group-item div_container">
                    <div class="row">
                        <div class="col-sm-5">
                            @if ($trecho->arquivo_sd != '')
                            <div style="height: 150px; width: 100%;">
                                <div id="my_midia_{{ $trecho->id }}" class="audio-container" style="background-image: url('{{ asset('player-audio/gifs/giphy_stop.png')}}'); background-size: 450px 105px; padding-bottom: 68px;" onclick="contarView('{{ $trecho->id }}', '{{ url( route('contarView', ['id' => $trecho->id ]) ) }}')">

                                    <!-- Chamar elemento audio com class player-audio -->
                                    {{-- ATENÇÃO: os formatos e a ordem dos inputs influenciam no gif de fundo e nos botes de mudar qualidade --}}
                                    <audio class="player-audio" >
                                        <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/mp3">
                                        <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/mp4">
                                        <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/m4a">
                                        <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/ogg">
                                        <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="audio/flac">

                                        <!-- Qualidades do aúdio -->
                                        <input id="audioHD" type="hidden" value="{{ asset('storage/' . $trecho->arquivo_sd) }}">
                                        <input id="audioSD" type="hidden" value="{{ asset('storage/' . $trecho->arquivo_sd) }}">

                                        <!-- Imagens do background quando der play e pause -->
                                        <input id="gif"     type="hidden" value="url('{{ asset('player-audio/gifs/giphy.gif')}}') 450px105px 126px">
                                        <input id="gifStop" type="hidden" value="url('{{ asset('player-audio/gifs/giphy_stop.png')}}') 450px105px 68px">
                                    </audio>

                                </div>
                            </div>
                            <input id="confirmacao{{ $trecho->id }}" type="hidden" value="0">
                            @else
                                <img src="{{ asset('imagens/imagem_audio.png') }}" alt="paper" style="width: auto; max-width: 100%">
                            @endif

                        </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-12" style="padding-top: 1rem;">
                                <output style="width: 100%; word-wrap: break-word;">{{$trecho->texto}}</output>
                                <span class="subtitulo_container">{{$trecho->titulo_video}}</span>
                            </div>
                            <div class="col-sm-12">
                                <a class="subtitulo_container" href="{{$trecho->endereco_video}}" style="position: relative;left: 0px">@lang('mensagens.Áudio completo')</a>
                            </div>
                            <div class="col-sm-12" style="padding: 1rem;">
                                <output class="campo_contador">
                                    <img src="{{ asset('icones/eye.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                    <label class="campo_compartilhar_texto">{{$trecho->quant_views}}</label>
                                </output>
                                <span class="dropdown">
                                    <button button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color: #acabab;"><img src="{{ asset('icones/share.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                        @lang('mensagens.Compartilhar')
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                        <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                        <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                    </div>
                                </span>
                                @auth
                                    <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color: #acabab;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" />@lang('mensagens.Editar')</button></a>
                                    @if (Auth()->user()->email === "admin@ufape.edu.br")
                                        <a href="" class="btn" data-toggle="modal" data-target="#excluirTrechoAudioModal_{{$trecho->id}}" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color: #acabab;"><img src="{{ asset('icones/excluir.svg') }}" alt="Logo" width="auto" height="25" />@lang('mensagens.Excluir')</a>
                                    @endif
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
        {{-- <div class="col-sm-12" style="margin-bottom: 25px; margin-top: 25px;">
            <div style="margin-left: 12px;"><a style="font-size: 25px; font-family:arial;">@lang('mensagens.Vídeos')</a></div>
                <br>
            <div style="margin-left: 12px; margin-top: -35px;"><a style="font-family:sans-serif; color: #aaaaaa;">@lang('mensagens.Resultado'): {{$resultado}} </a><output id="letraSelecionada"></output></div>
        </div> --}}
        <div class="col-sm-12">
            <ul class="list-group">
                @foreach ($trechosVideos as $trecho)
                    @if($trecho->tipo_recurso=="vídeo")
                     <!-- Modal -->
                     <div class="modal fade" id="excluirTrechoVideoModal_{{$trecho->id}}" tabindex="-1" role="dialog" aria-labelledby="excluirTrechoVideoModalLabel{{$trecho->id}}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="excluirVerbeteVideoModalLabel{{$trecho->id}}">@lang('mensagens.Confirmar')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @lang('mensagens.Confirmacao')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('mensagens.Nao')</button>
                                <a href="{{ route('trecho.del', ['id' => $trecho->id]) }}"><button type="button" class="btn btn-primary">@lang('mensagens.Sim')</button></a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <li class="list-group-item div_container">
                    <div class="row">
                        <div class="col-sm-5">
                            @if ($trecho->arquivo_hd != '' || $trecho->arquivo_hd != '')
                            <div id="my_midia_{{ $trecho->id }}" class="video-container" onclick="contarView('{{ $trecho->id }}', '{{ url( route('contarView', ['id' => $trecho->id ]) ) }}')">
                                <!-- Chamar elemento vídeo com class jlplayer-video -->
                                <video preload="none" class="jlplayer-video" poster="{{ asset('storage/' . $trecho->arquivo_sd) }}">
                                    <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="video/mp4">
                                    <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="video/mkv">
                                    <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="video/ogv">
                                    <source src="{{ asset('storage/' . $trecho->arquivo_sd) }}" type="video/webm">

                                    <input id="videoHD" type="hidden" value="{{asset('storage/' . $trecho->arquivo_hd)}}">
                                    <input id="videoSD" type="hidden" value="{{asset('storage/' . $trecho->arquivo_sd)}}">
                                </video>

                            </div>
                            <input id="confirmacao{{ $trecho->id }}" type="hidden" value="0">
                        @else
                            <img src="{{ asset('imagens/imagem_video.png') }}" alt="paper" style="position: relative; height: auto; width: 100%; top: 1rem; padding-right: 0.2rem;">
                        @endif

                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-sm-12" style="padding-top: 1rem;">
                                    <output style="width: 100%; word-wrap: break-word;">{{$trecho->texto}}</output>
                                    <span  class="subtitulo_container" >{{$trecho->titulo_video}}</span>
                                </div>
                                <div class="col-sm-12">
                                    <a class="subtitulo_container" href="{{$trecho->endereco_video}}" style="position: relative; left: 0px;">@lang('mensagens.Vídeo completo')</a>
                                </div>
                                <div class="col-sm-12" style="padding: 1rem;">
                                    <output class="campo_contador">
                                        <img src="{{ asset('icones/eye.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                        <label class="campo_compartilhar_texto">{{$trecho->quant_views}}</label>
                                    </output>
                                    <span class="dropdown">
                                        <button button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color: #acabab;"><img src="{{ asset('icones/share.svg') }}" alt="Logo" width="16,74" height="18,34" />
                                            @lang('mensagens.Compartilhar')
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                            <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                            <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                            <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                        </div>
                                    </span>
                                    @auth
                                        <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" /><label class="campo_compartilhar_texto">@lang('mensagens.Editar')</label></button></a>
                                        @if (Auth()->user()->email === "admin@ufape.edu.br")
                                            <a href="" class="btn" data-toggle="modal" data-target="#excluirTrechoVideoModal_{{$trecho->id}}" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white;"><img src="{{ asset('icones/excluir.svg') }}" alt="Logo" width="auto" height="25" /><label class="campo_compartilhar_texto">@lang('mensagens.Excluir')</label></a>
                                        @endif
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

    function contarView(id, url) {
        var midia = document.getElementById('my_midia_' + id).children[0].children[0];
        var confirmacao = document.getElementById('confirmacao' + id);
        if (midia.paused != true && confirmacao.value == "0") {
            confirmacao.value = "1";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }
    }
</script>
@endsection
