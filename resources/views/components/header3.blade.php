@props(['title', 'dat_user'])
<header>
	<div id="header" class="header">
        <div id="encabezado">
        <div id="header">
                <div class="hero-image">
                    <div class="logo-space">
                        <a class="brand" href="{{ route('login') }}" title="Giff Services"
                            aria-label="Giff Services"><img src="{{ asset('images/97543029_280123276722929_1855283596785352704_n.png') }}" alt=""></a>
                    </div>
                </div>

                
        </div>
        </div>
		
	</div>
    <div id="expandible" class="topnav-responsive">
        <div><a class="underH" href="{{ route('logout')}}">Logout</a></div>
    </div>
</header>  

<div id="header1" class="header1">
    <h2><?=$title?></h2>
    
    <div class="topnav" id="myTopnav">
        <a class="underH" href="{{ route('incluirEnvioGittCli')}}">Agregar envío Gitt</a>
        @if (!$dat_user['id_MELI'])
            <a class="underH" href="{{ route('integrar_MELI')}}">Agregar integración con MELI</a>
        @endif
        
        @if (!$dat_user['id_TN'])
            <a class="underH" href="{{ route('integrar_NUBE')}}">Agregar integración con TN</a>
        @endif
        
        <a class="underH" href="{{ route('logout')}}">Logout</a>

        <a href="javascript:void(0);" class="icon" onclick="FunctionTopnav()">
        <i id="i-menu" class="fa fa-bars"></i>
        </a>
    </div>
</div>