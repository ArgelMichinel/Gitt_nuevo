<div id="header" class="header">
    
    <div id="header" class="header1">
            <div class="hero-image">
                <div class="logo-space">
                    <a class="brand" href="{{ route('login')}}" title="Giff Services"
                        aria-label="Giff Services"><img src="{{ asset('images/97543029_280123276722929_1855283596785352704_n.png') }}" alt=""></a>
                </div>
            </div>

    </div>
    
</div>

<header>
	<div class="header">
		<div id="encabezado">
            <div id="header">
                <div class="hero-image">
                    <div class="logo-space">
                        <a class="brand" href="{{ route('login')}}" title="Giff Services"
                            aria-label="Giff Services"><img src="{{ asset('images/97543029_280123276722929_1855283596785352704_n.png') }}" alt=""></a>
                    </div>
                </div>

                    
            </div>
        </div>
		
	</div>
    <div id="expandible" class="topnav-responsive">
        <div id="under5"><a class="underH" href="javascript:void(0)" onclick="menuenvios()">Envíos</a></div>
        <div id="under6"><a class="underH" href="javascript:void(0)" onclick="menuclientes()">Clientes</a></div>
        <div id="under7"><a class="underH" href="javascript:void(0)" onclick="menucadetes()">Cadetes</a></div>
        <div id="under8"><a class="underH" href="javascript:void(0)" onclick="menuadminist()">Adm. Usuarios</a></div>
        <div><a class="underH" href="{{ route('logout')}}">Logout</a></div>
    </div>
</header>


<div id="header1" class="header1">
    <h2><?=$title?></h2>
    
    <div class="topnav" id="myTopnav">

            <a class="underH" href="javascript:void(0)" onclick="menuenvios()">Envíos</a>
            <a class="underH" href="javascript:void(0)" onclick="menuclientes()">Clientes</a>
            <a class="underH" href="javascript:void(0)" onclick="menucadetes()">Cadetes</a>
            <a class="underH" href="javascript:void(0)" onclick="menuadminist()">Adm. Usuarios</a>
            <a class="underH" href="logout.php">Logout</a>

            <a href="javascript:void(0);" class="icon" onclick="FunctionTopnav()">
            <i id="i-menu" class="fa fa-bars"></i>
            </a>
    </div>
</div>