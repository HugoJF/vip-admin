@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1> Welcome, {{ Auth::user()->name ? Auth::user()->name : Auth::user()->username }}</h1>
        @if(Auth::user()->accepted != true)
            <p>Essa plataforma administra todas as compras de slot VIP do meu servidor de Counter-Strike: Global Offensive no IP: <strong>177.54.147.159:27047</strong></p>

            <p><strong>Atualmente apenas compras com itens da Steam são automaticamente processados</strong>, pagamentos via MercadoPago ainda serão feitos manualmente <a href="http://steamcommunity.com/id/de_nerd">comigo via Steam.</a></p>

            <p><strong>1 mês de slot VIP no servidor custa R$ 4,00</strong>. No caso de itens da Steam, o preço considerado de cada item será do mercado OPSkins.</p>

            <br>
            <p>Caso você tenha <strong>REAL</strong> interesse em apoiar o servidor ou adquirir o VIP no servidor, <strong>leia atentamente os seguintes pontos</strong>:</p>
            <ul>
                <li>O VIP <strong>não te da imunidade a bans/kicks </strong>ou qualquer regra do servidor.</li>
                <li>O VIP não garante que o servidor estará online e estável 24/7/365, <strong>eventuais manutenções e instabilidades são inevitáveis.</strong></li>
                <li>O VIP <strong>não te da acesso</strong> a administração do servidor.</li>
                <li>Apesar de tudo ser automatizado, erros podem ocorrer, em qualquer caso, <strong>compensações serão feitas.</strong></li>
                <li><strong>Qualquer</strong> mudanca nas trade offers feitas pelo sistema, implicará em <strong>BAN permanente </strong>no servidor. </li>
                <li><strong>Qualquer</strong> tentativa de abuso do sistema implicará em <strong>BAN permanente </strong>no servidor.</li>
                <li>Todas as trade offers feitas por esses sistema <strong><a href="http://steamcommunity.com/id/de_nerd">serão da minha conta</a></strong>, sempre verifique isso antes de aceitar.</li>
                <li>Existe a possibilidade de alguns preços estarem incorretos, neste caso me reservo o direito de alterar qualquer confirmação que esteja com a duração incorreta.</li>
                <li>Esse sistema ainda esta em fase de desenvolvimento, caso voce acha algum bug, <strong>por favor <a href="http://steamcommunity.com/id/de_nerd">reporte diretamente comigo.</a></strong></li>
                <li><strong>Quaisquer problemas que você encontrar na primeira semana do seu VIP, por favor entre em contato diretamente pela <a href="http://steamcommunity.com/id/de_nerd">Steam.</a></strong></li>
            </ul>
            <br>
            <a href="{{ route('accept') }}" class="btn btn-lg btn-primary btn-block">Estou ciente de todas as observações feitas acima</a>
        @endif
    </div>
@endsection