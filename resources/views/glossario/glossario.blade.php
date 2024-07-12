@extends('layouts.app')

@section('content')
<script>
    // funções para editar o verbete
    function editarVerbete(id) {
        document.getElementById('verbete_' + id).contentEditable = true;
        document.getElementById('button_salvar_' + id).style.display = 'inline';
        document.getElementById('button_cancelar_' + id).style.display = 'inline';
    }

    function cancelarEditarVerbete(id) {
        document.getElementById('verbete_' + id).contentEditable = false;
        document.getElementById('button_salvar_' + id).style.display = 'none';
        document.getElementById('button_cancelar_' + id).style.display = 'none';
    }

    function salvarVerbete(id) {
        var descricao = document.getElementById('verbete_' + id).text;
        var inputVerbete = document.getElementById('input_editar_verbete_' + id);

        inputVerbete.value = descricao;

        document.getElementById('verbete_' + id).contentEditable = false;
        document.getElementById('button_salvar_' + id).style.display = 'none';
        document.getElementById('button_cancelar_' + id).style.display = 'none';

        var formulario = document.getElementById('form_editar_verbete_' + id);
        formulario.submit();
    }
</script>

<div style="padding-top: 80px;">
    <div class="imagem_fundo" style="background-image: url({{asset('imagens/img_grande_50.png')}})">
        <div class="row card-body menu_glossario">
            <div class="menu_glossario_design">
                <div class="row" style="margin: 1rem;">
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
                        <div class="row">
                            @foreach ($letras as $letra)
                            <div class="menu_glossario_letra_borda">
                                @if($letra->count > 0)
                                <a href="{{ route('letra', ['l' => $letra->l]) }}">
                                <div class="col-xs-1 menu_glossario_letra">
                                {{$letra->l}}</div></a>
                                @else
                                <div class="col-xs-1 menu_glossario_letra">{{$letra->l}}</div>
                                @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(session('mensagem'))
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="alert alert-success">
                                <p>{{session('mensagem')}}</p>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 justify-content-between" style="margin-top: 5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <x-select-idioma/>
                            </div>
                            <div class="col-md-6">
                                <div style="float: right">
                                    @auth
                                        <a href="{{ route('verbete.add') }}">@lang('mensagens.Adicionar Verbete')</a> &nbsp;
                                    @endauth
                                        <a href=" {{ route('listarPalavras') }} ">@lang('mensagens.Listar todas as palavras')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-4" style="background-color: white; margin-left: 1rem; margin-right: 0.2rem; margin-bottom: 1rem; border: solid 2px; border-color: #d5d5d5; border-radius: 5px;">
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 10px; margin-top: 20px;">
            <div style="margin-left: 12px;"><a id="titulo_lista">@lang('mensagens.Lista')</a></div>
                <br>
            <div style="margin-left: 12px; margin-top: -35px; margin-bottom: 2rem;">
                <a id="subtitulo_lista">@lang('mensagens.Palavras com a letra') </a>
                <output id="subtitulo_lista" id="letraSelecionada">{{$letraSelecionada}}</output>
            </div>
        </div>
        <div class="col-sm-12" id="lista_menu">
            <ul class="list-group">
                @foreach ($verbetes as $verbete)
                <!-- Modal -->
                <div class="modal fade" id="excluirVerbeteModal_{{ $verbete->id }}" tabindex="-1" role="dialog" aria-labelledby="excluirVerbeteModalLabel_{{ $verbete->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="excluirVerbeteModalLabel_{{ $verbete->id }}">@lang('mensagens.Confirmar')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @lang('mensagens.Confirmacao')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('mensagens.Nao')</button>
                            <a href="{{ route('verbete.del', ['id' => $verbete->id]) }}"><button type="button" class="btn btn-primary">@lang('mensagens.Sim')</button></a>
                        </div>
                        </div>
                    </div>
                </div>
                @auth
                    <li class="list-group-item lista_item" >
                        <a id="verbete_{{$verbete->id}}" href="{{ route('verbete', ['id' => $verbete->id]) }}">{{$verbete->descricao}}</a>
                        <span class="dropdown">
                            <button button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right; border: none; background-color: white;"><img src="{{ asset('icones/menu_dot.svg') }}" alt="Logo" width="auto" height="20" />
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuOffset">
                                <a href="{{ route('trecho.add', ['id' => $verbete->id]) }}" class="dropdown-item" ><img width="22" height="22" src="{{ asset('icones/add.svg') }}" style="margin-right: 10px;"><span>@lang('mensagens.Adicionar trecho')</span></a>
                                <a href="javascript:editarVerbete('{{$verbete->id}}')" class="dropdown-item" ><img width="22" height="22" src="{{ asset('icones/edit.svg') }}" style="margin-right: 10px;"><span>@lang('mensagens.Editar veberte')</span></a>
                                @if (Auth()->user()->email === "admin@ufape.edu.br")
                                    <a href="" class="btn btn-primary dropdown-item" data-toggle="modal" data-target="#excluirVerbeteModal_{{ $verbete->id }}"><img width="25" height="25" src="{{ asset('icones/excluir.svg') }}" style="margin-right: 10px;"><span>@lang('mensagens.Excluir')</span></a>
                                @endif
                            </div>
                        </span>
                        <div>
                            <p>
                                <form id="form_editar_verbete_{{$verbete->id}}" method="POST" action="{{ route('verbete.edit', ['id' => $verbete->id]) }}">
                                    @csrf
                                    <input id="input_editar_verbete_{{$verbete->id}}" name="descricao" type="hidden">
                                    <a id="button_salvar_{{$verbete->id}}" class="btn" style="display: none; border-color:#d5d5d5; border-width:2px; height: 10px; background-color: white; color: #d5d5d5;" href="javascript:salvarVerbete('{{$verbete->id}}')">@lang('mensagens.Salvar')</a>
                                    <a id="button_cancelar_{{$verbete->id}}" class="btn" href="javascript:cancelarEditarVerbete('{{$verbete->id}}')" style="display: none; border-color:#d5d5d5; border-width:2px; height: 25px; background-color: white; color: #d5d5d5;">@lang('mensagens.Cancelar')</a>
                                </form>
                            </p>
                        </div>
                    </li>
                @else
                    <a id="verbete_{{$verbete->id}}" href="{{ route('verbete', ['id' => $verbete->id]) }}">
                        <li class="list-group-item lista_item" >
                            {{$verbete->descricao}}
                        </li>
                    </a>
                @endauth
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="col-md-7 div_resultado">
@if (Route::currentRouteName() === 'verbete')
    @if (count($trechosAudios) != 0)
    <div class="row">
            @if(count($trechosAudios) != 0)
            <div class="col-sm-12" style="margin-bottom: 25px; margin-top: -5px;">
                <div style="margin-left: 12px;"><a style="font-size: 25px; font-family: 'Segoe-ui', 'Segoe-ui-ie', serif;"><strong>@lang('mensagens.Verbete'):</strong> {{$verbeteSelecionado->descricao}}</a></div>
                    {{-- <br>
                <div style="margin-left: 12px; margin-top: -35px;"><a style="font-family:sans-serif; color: #aaaaaa;">@lang('mensagens.Resultado'): {{$verbeteSelecionado->descricao}}</a><output id="letraSelecionada"></output></div> --}}
            </div>
            @endif
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
                                <div class="col-sm-12">
                                    @if ($trecho->arquivo_sd != '')
                                        <div style="width: 100%; height: 140px">
                                            <div id="my_midia_{{ $trecho->id }}" class="audio-container" style="background-image: url('{{ asset('player-audio/gifs/giphy_stop.png')}}'); background-size: 600px; padding-bottom: 105px;" onclick="contarView('{{ $trecho->id }}', '{{ url( route('contarView', ['id' => $trecho->id ]) ) }}')">

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
                                                    <input id="gif"     type="hidden" value="url('{{ asset('player-audio/gifs/giphy.gif')}}') 600px140px 140px">
                                                    <input id="gifStop" type="hidden" value="url('{{ asset('player-audio/gifs/giphy_stop.png')}}') 600px140px 105px">
                                                </audio>

                                            </div>
                                        </div>
                                        <input id="confirmacao{{ $trecho->id }}" type="hidden" value="0">
                                    @else
                                        <img src="{{ asset('imagens/imagem_audio.png') }}" alt="paper" style="width: auto; max-width: 100%">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="padding-top: 1rem;">
                                    <span class="subtitulo_container">{{ $trecho->titulo_video }}</span>
                                </div>
                            </div>
                            <div class="row" style="font-size: 14px;">
                                <div class="col-sm-5" style="padding-top: 1rem;">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <img src="{{ asset('icones/eye_dark.svg') }}" width="22,12" height="auto"/>
                                            <span class="campo_compartilhar_texto">{{ $trecho->quant_views }}</span>
                                        </div>
                                        <div class="col-sm-7">
                                            <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="{{ asset('icones/share_dark.svg') }}" width="22,12" height="auto" />
                                                <span class="campo_compartilhar_texto">@lang('mensagens.Compartilhar')</span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font-size: 14px;">
                                                <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                                <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                                <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7" style="padding-top: 1rem; text-align: right;">
                                    <a href="{{ $trecho->endereco_video }}" target="_blank">@lang('mensagens.Clique aqui')</a><span  style="color: #8b8b8b;font-size: 8;"> @lang('mensagens.para ouvir o áudio completo').</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="padding: 0px 15px 15px 15px;">
                                <output style="width: 100%; word-wrap: break-word; text-align: justify; font-weight: normal;">
                                    {{ $trecho->texto }}
                                </output>
                            </div>
                            <div class="row" style="padding: 0px 15px 15px 15px; float: right;">
                                @auth
                                    @if (Auth()->user()->email === "admin@ufape.edu.br")
                                        <a href="" class="btn" data-toggle="modal" data-target="#excluirTrechoAudioModal_{{$trecho->id}}" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color:#acabab; margin-right: 10px;"><img src="{{ asset('icones/excluir.svg') }}" alt="Logo" width="auto" height="25" />@lang('mensagens.Excluir')</a>
                                    @endif
                                    <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color:#acabab;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" />@lang('mensagens.Editar')</button></a>
                                @endauth
                            </div>
                          </li>

                        <!-- <div class="div_mais_resultados">
                            <div >
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">1</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">2</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">3</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">4</output>
                                <a href="">Ver todos.</a>
                            </div>
                        </div> -->

                        @endif
                    @endforeach
                </ul>
            </div>
    </div>
    @endif
    @if (count($trechosVideos) != 0)
    <div class="row">
            @if(count($trechosAudios) == 0)
            <div class="col-sm-12" style="margin-bottom: 25px; margin-top: -5px;">
                <div style="margin-left: 12px;"><a style="font-size: 25px; font-family: 'Segoe-ui', 'Segoe-ui-ie', serif;"><strong>@lang('mensagens.Verbete'):</strong> {{$verbeteSelecionado->descricao}}</a></div>
                    {{-- <br>
                <div style="margin-left: 12px; margin-top: -35px;"><a style="font-family:sans-serif; color: #aaaaaa;">@lang('mensagens.Resultado'): {{$verbeteSelecionado->descricao}}</a><output id="letraSelecionada"></output></div> --}}
            </div>
            @endif
            <div class="col-sm-12">
                <ul class="list-group">
                    @foreach ($trechosVideos as $trecho)
                        @if($trecho->tipo_recurso == "vídeo")
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
                                <div class="col-sm-12">
                                    @if ($trecho->arquivo_hd != '' || $trecho->arquivo_hd != '')
                                        <div id="my_midia_{{ $trecho->id }}" class="video-container" onclick="contarView('{{ $trecho->id }}', '{{ url( route('contarView', ['id' => $trecho->id ]) ) }}')">
                                            <!-- Chamar elemento vídeo com class jlplayer-video -->
                                            <video preload="none" class="jlplayer-video" >
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
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="padding-top: 1rem;">
                                    <span class="subtitulo_container">{{ $trecho->titulo_video }}</span>
                                </div>
                            </div>
                            <div class="row" style="font-size: 14px;">
                                <div class="col-sm-5" style="padding-top: 1rem;">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <img src="{{ asset('icones/eye_dark.svg') }}" width="22,12" height="auto"/>
                                            <span class="campo_compartilhar_texto">{{ $trecho->quant_views }}</span>
                                        </div>
                                        <div class="col-sm-7">
                                            <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="{{ asset('icones/share_dark.svg') }}" width="22,12" height="auto" />
                                                <span class="campo_compartilhar_texto">@lang('mensagens.Compartilhar')</span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font-size: 14px;">
                                                <a class="dropdown-item" onclick="shareFacePopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/facebook.png') }}"><span>Facebook</span></a>
                                                <a class="dropdown-item" onclick="shareWhatsPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/whatsapp.svg') }}"><span>Whatsapp</span></a>
                                                <a class="dropdown-item" onclick="shareTwitterPopUp('{{ url( route('pesquisa.id', ['id' => $trecho->id])) }}')"><img width="25" height="25" src="{{ asset('icones/twitter.png') }}"><span>Twitter</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7" style="padding-top: 1rem; text-align: right;">
                                    <a href="{{ $trecho->endereco_video }}" target="_blank">@lang('mensagens.Clique aqui')</a><span  style="color: #8b8b8b;font-size: 8;"> @lang('mensagens.para assistir ao vídeo completo').</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="padding: 0px 15px 15px 15px;">
                                <output style="width: 100%; word-wrap: break-word; text-align: justify; font-weight: normal;">
                                    {{ $trecho->texto }}
                                </output>
                            </div>
                            <div class="row" style="padding: 0px 15px 15px 15px; float: right;">
                                @auth
                                    @if (Auth()->user()->email === "admin@ufape.edu.br")
                                        <a href="" class="btn" data-toggle="modal" data-target="#excluirTrechoVideoModal_{{$trecho->id}}" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color:#acabab; margin-right: 10px;"><img src="{{ asset('icones/excluir.svg') }}" alt="Logo" width="auto" height="25" />@lang('mensagens.Excluir')</a>
                                    @endif
                                    <a href="{{ Route('editar', ['id' => $trecho->id]) }}"><button type="button" class="btn" style="border-color:#d5d5d5; border-width:2px; height: 40px; background-color: white; color:#acabab;"><img src="{{ asset('icones/edit.svg') }}" alt="Logo" width="16,74" height="18,34" />@lang('mensagens.Editar')</button></a>
                                @endauth
                            </div>
                        </li>

                        <!-- <div class="div_mais_resultados">
                            <div >
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">1</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">2</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">3</output>
                                <output style="text-align: center;  border: 2px solid #d5d5d5; width: 39px; height: 39px; border-radius: 20px; padding-top: 5px; margin-right: 5px;">4</output>
                                <a href="">Ver todos.</a>
                            </div>
                        </div> -->

                        @endif
                    @endforeach
                </ul>
            </div>
    </div>
    @endif
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
@endif
</div>
@endsection
