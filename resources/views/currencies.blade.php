@extends('layouts.main')

@section('title')
    {{$title}}
@endsection

@section('content')
    <h1>{{$title}}</h1>
    @if(Session::has('success_msg'))
        <div class="card green">
            <div class="card-content white-text">{{ Session::get('success_msg') }}</div>
        </div>
    @endif
    <table class="currency-table striped" border="1" cellspacing="0" cellpadding="0">
        <colgroup>
            <col span="1" style="width: 7%;">
            <col span="1" style="width: 28%;">
            <col span="1" style="width: 15%;">
            <col span="1" style="width: 25%;">
            <col span="1" style="width: 25%;">
        </colgroup>
        @empty($currencies)
            <thead>
            <tr class="currency-table__head">
                <th></th>
                <th>Name</th>
                <th>Short name</th>
                <th>Price</th>
                <th></th>
            </tr>
            </thead>
        @endempty
        @forelse($currencies as $currency)
            <tr class="currency-table__row">
                <td>
                    <img src="{{$currency->logo_url}}" alt="{{$currency->title}}"/>
                </td>
                <td>
                    <a href="{{route('currencies.show',$currency->id)}}">{{$currency->title}}</a>
                </td>
                <td>
                    {{$currency->short_name}}
                </td>
                <td>
                    {{$currency->price}}
                </td>
                @if( Gate::check('update',$currency) || Gate::check('delete',$currency) )
                    <td>
                        <div class="currencies__edit-btns">
                            @can('update',$currency)
                                <a class="btn currencies__edit-btn edit-button" href="{{route('currencies.edit',$currency->id)}}">Edit</a>
                            @endcan
                            @can('delete',$currency)
                                <form class="currency-delete-form" action="{{ route('currencies.destroy',$currency->id) }}"
                                      method="POST">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button class="btn red delete-button currencies__edit-btn" type="submit">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <div class="card">
                <div class="card-content white-text red">No currencies</div>
            </div>
        @endforelse
    </table>
@endsection
