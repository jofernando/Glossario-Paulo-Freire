@extends('layouts.app')

@section('content')
<div style="padding-top: 80px;">
    <div class="imagem_fundo" style="background-image: url({{ asset('imagens/img_grande_50.png') }})">
        <div class="card-body menu_glossario">
            <div class="menu_glossario_design">
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
                    <div class="col-md-12" style="margin-top: 5px;">
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
<div class="row">
    @foreach ($letras as $letra)
    <div class="col-md-12">
    <div class="row">
        <div class="col-md-1 letra">{{$letra->l}}</div>
        <div class="col-md-10" style="margin-left: 1rem; margin-top: 10%; margin-bottom: 1rem;">
                <ul class="list-group">
                @foreach ($verbetes as $verbete)
                    @if (str_split(\App\Http\Controllers\ListarTrechoController::tirarAcentos($verbete->descricao))[0] == $letra->l || str_split(\App\Http\Controllers\ListarTrechoController::tirarAcentos($verbete->descricao))[0] == strtolower($letra->l))
                    <a href="{{ route('verbete', ['id' => $verbete->id]) }}">
                        <li class="list-group-item lista_item" >
                                <div class="row">
                                    <div class="col-md-12"><label >{{$verbete->descricao}}</label></div>
                                        <div class="col-md-12">
                                            <div class="btn-group">
                                                <div style="margin-right: 1rem;">
                                                    <img src="{{ asset('icones/trechos.svg') }}" alt="Logo" width="25" height="25"/><label class="campo_compartilhar_texto">{{$verbete->count}}</label>
                                                </div>
                                                <!-- <div style="margin-right: 1rem;">
                                                    <img src="{{ asset('icones/video.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                                    <label class="campo_compartilhar_texto">20.123</label>
                                                </div>
                                                <div>
                                                    <img src="{{ asset('icones/audio.svg') }}" alt="Logo" width="22,12" height="14,41" />
                                                    <label class="campo_compartilhar_texto">20.123</label>
                                                </div> -->
                                            </div>
                                        </div>
                                </div>
                        </li>
                    </a>
                    @endif
                @endforeach
        </div>
    </div>
    </div>
    @endforeach
</div>
@endsection
