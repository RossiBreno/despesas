<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL::asset('images/fav.ico') }}" type="image/x-icon">
    <title>Dashboard</title>
</head>
<body>
    <div id="navbar-container">
        <div id="navbar">
            <div id='image-logo-container'>
                <image id='image-logo' src="{{ asset('images/logo-roxa.svg') }}" />
            </div>
            <button type="button" onclick="window.location='{{ route("user.logout") }}'">Sair</button>
        </div>
    </div>
    <div id="container">
        <div id="data-container">
            <div id="chart-container">  
                <h1>Gráfico</h1>
                @if(@$chart[amount] > 1)
                    <div id="chart"></div>
                @else
                    <div id="empty-chart">
                        <h2>Você precisa ter pelo menos 2 despesas para montar um gráfico</h2>
                    </div>
                @endif
            </div>
            <div id="info-container">
                <h1>Informações deste Mês</h1>
                <div id="info-card-container">
                    <div class="info-card">
                        <h4>Maior despesa</h4>
                        <h1>R${{@$infos[highest]}}</h1>
                    </div>
                    <div class="info-card">
                        <h4>Menor despesa</h4>
                        <h1>R${{@$infos[lowest]}}</h1>
                    </div>
                    <div class="info-card">
                        <h4>Despesa média</h4>
                        <h1>R${{@$infos[average]}}</h1>
                    </div>
                    <div class="info-card">
                        <h4>Soma das despesas</h4>
                        <h1>R${{@$infos[sum]}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="despesas">
            <div id="title">
                <h1>Minhas despesas:</h1>
                <button id="filter-button" class="purple-button" style="width: fit-content">Filtros</button>
            </div>
            <form id="form-filters" action="{{route('dashboard.index')}}" method="POST">
                @csrf
                <div id="filters-container">
                    <div class="filters-column">
                        <h3>Data</h3>
                        <div class="filter-input-container">
                            <input class="input-date date" type='text' value="{{@$filterOptions[dateFrom]}}" name="dateFrom"  placeholder="De" />
                            <h3> - </h3>
                            <input class="input-date date" type='text' value="{{@$filterOptions[dateAt]}}" name="dateAt" placeholder="Até" />
                        </div>
                    </div>
                    <div class="filters-column">
                        <h3>Preço</h3>
                        <div class="filter-input-container">
                            <input class="input-value value" type='text' value="{{@$filterOptions[valueFrom]}}" name="valueFrom" placeholder="De" />
                            <h3> - </h3>
                            <input class="input-value value" type='text' value="{{@$filterOptions[valueAt]}}" name="valueAt" placeholder="Até" />
                        </div>
                    </div>

                    <div class="filters-column">
                        <h3>Ordem</h3>
                        <select name="order" class="gray-select">
                            <option {{@$filterOptions[order] == 'rec' ? 'selected' : ''}} value="rec">Mais recente</option>
                            <option {{@$filterOptions[order] == 'ant' ? 'selected' : ''}} value="ant">Mais antigo</option>
                            <option {{@$filterOptions[order] == 'mav' ? 'selected' : ''}} value="mav">Maior valor</option>
                            <option {{@$filterOptions[order] == 'mev' ? 'selected' : ''}} value="mev">Menor valor</option>
                        </select>
                    </div>
                </div>
                <button class="purple-button">Filtrar</button>
            </form>
            <div id="card-container">
                <div class="card-add card">
                    <a href="{{route('despesa.create.index')}}" class="icon-add-container" style="background-color: #864FDE">
                        <image class='icon' width="60" height="60" src="{{ asset('images/icons/add.svg') }}" />
                    </a>
                </div>

                @foreach(@$despesas as $despesa)
                    <div class="card">
                        <div class="tab-bar">
                            <h4>{{$despesa->date}}</h4>
                            <div class="button-container">
                                <a href="{{route('despesa.image', $despesa->id)}}" class="icon-container">
                                    <image class='icon' width="13" height="13" src="{{ asset('images/icons/image.svg') }}" />
                                </a>
                                <a href="{{route('despesa.edit.index', $despesa->id)}}" class="icon-container">
                                    <image class='icon' width="13" height="13" src="{{ asset('images/icons/pencil.svg') }}" />
                                </a>
                                <form action="{{route('despesa.delete', $despesa->id)}}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="icon-container">
                                        <image class='icon' width="15" height="15" src="{{ asset('images/icons/trash.svg') }}" />
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="info-despesa-card">
                            <div class="price-row">
                                <h2>{{$despesa->value}}</h2>
                                <h4>,{{$despesa->cents}}</h4>
                            </div>
                            <h3>{{$despesa->desc}}</h3>
                        </div>    
                    </div>
                @endforeach

                
            </div>
        </div>
    </div>
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="{{ URL::asset('js/mask.js') }}"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">
    $('document').ready(function() {
        $('#form-filters').hide()
    })

    $('#filter-button').click(function() {
        $('#form-filters').toggle()
    })
</script>
<script type="text/javascript">
    var categories = <?php echo json_encode(@$chart[dates]) ?>;
    var data = <?php echo json_encode(@$chart[values]) ?>;
    var amount = <?php echo @$chart[amount] ?>;

    if(amount > 1) Highcharts.chart('chart', {
        title: {
            text: amount == '0' ? 'Nenhuma despesa no momento' : amount == '1' ? 'Sua ultima despesa' : 'Suas ultimas '+amount+' despesas'
        },
        xAxis: {
            categories
        },
        yAxis: {
            title: null
        },
        series: [{
            name: 'Despesas',
            data
        }]
    })
</script>
