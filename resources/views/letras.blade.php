@extends('layouts.app')

@section('content')
<div class="container">
    <div style="padding-top: 80px;">
        <div class="imagem_fundo" style="background-image: url(imagens/img_grande_50.png)">
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
                        @if(session('mensagem'))
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="alert alert-success">
                                <p>{{session('mensagem')}}</p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <div class="row justify-content-between" style="margin-top: 5px;">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
