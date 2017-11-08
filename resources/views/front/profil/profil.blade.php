@extends('front.layouts.front')

@section('content')

<div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >

    @if(isset($baslik))
        <div class="panel ag-front-baslik-kutusu">
            <h1>{{$baslik}}</h1>
            <i class="fa fa-home fa-2x ag-anasayfa" aria-hidden="true"></i>
        </div>
    @endif
    @if(Session::has('mesaj'))
        <div class="alert alert-success ag-alert">
            {{Session::get('mesaj')}}
        </div>
    @endif
    @if(Session::has('alert'))
        <div class="alert alert-danger ag-alert">
            {{Session::get('alert')}}
        </div>
    @endif



    <div class="panel ag-front-panel col-md-12">

        <!--  -------------------------------------        FORM          --------------------------------------------  -->

        {!! Form::open(
         array(
        'url' => '/profil/kaydet',
        'class' => 'form',
        'novalidate' => 'novalidate',
        'files' => true))!!}
        <div class="form-group duellolar-kutusu">
            <div class="row">
                <div class="col-xs-6 col-md-3 col-lg-3">
                    {!! Form::label('profilresmi', 'Profil Resminiz',['class' => 'ag-label']) !!}
                    <img id="ag-profil-resmi" class="img-thumbnail" src="{{ isset($profilArr->profil_resmi)?$profilArr->profil_resmi:'images/noimage.png' }}">
                    {{ Form::file('profilresmi', ['class' => 'invisible']) }}
                    <a href="#" id="ag-resmi-degistir">Değiştir</a> |
                    <a href="#" id="ag-avatar-secin">Avatar seç</a>
                </div>
            </div>
        </div>
        <div class="form-group duellolar-kutusu">
            {!! Form::label('sinif', 'Sınıfınız',['class' => 'ag-label']) !!}
            {{ Form::select('sinif', array('4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14'),
            isset($profilArr->sinif)?$profilArr->sinif:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
        </div>


        <br><br>
        {{ Form::submit('Kaydet',['class' => 'btn btn-primary ag-form-control ag-kaydet']) }}
        {!! Form::close() !!}


    </div>
</div>
<script>
    $('#ag-resmi-degistir').click(function () {
        $('#profilresmi').trigger('click');
    })
</script>

    <style>
        #ag-avatar-secin{
            bor
        }

        .ag-kaydet{
            font-family: Anton;
            font-size: 25px;
        }

    </style>
@endsection
