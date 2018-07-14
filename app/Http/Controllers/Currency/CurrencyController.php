<?php

namespace App\Http\Controllers\Currency;

use App\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Currency market";
        $currencies = Currency::all();
        return view('currencies', ['currencies' => $currencies, 'title' => $title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create', Currency::class)) {
            return redirect('/');
        }
        $title = 'Add currency';
        return view('currencies.create', ['title' => $title]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  CurrencyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyRequest $request)
    {
        if (Gate::denies('create', Currency::class)) {
            return redirect('/');
        }
        $currency = new Currency();
        $currency->title = $request->getTitle();
        $currency->short_name = $request->getShortName();
        $currency->logo_url = $request->getLogoUrl();
        $currency->price = $request->getPrice();
        $currency->save();

        Session::flash('success_msg', 'Currency successfully added!');
        return redirect()->route('currencies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        if (Gate::denies('view', $currency)) {
            return redirect('/');
        }
        return view('currencies.show', ['currency' => $currency, 'title' => $currency->title]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $currency = Currency::find($id);
        if (!$currency || /*
              Yes, I can get Currency model instead of id as param of this method
              but test "Task3GeneralUserActionsTest@test_user_dont_see_edit_currency_page"
              needs redirect instead of 404 error ¯\_(ツ)_/¯ */
            Gate::denies('update', $currency)) {
            return redirect('/');
        }
        return view('currencies.edit', ['currency' => $currency, 'title' => $currency->title]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CurrencyRequest $request
     * @param  Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        if (Gate::denies('update', $currency)) {
            return redirect('/');
        }
        if ($request->has('title')) {
            $currency->title = $request->getTitle();
        }
        if ($request->has('short_name')) {
            $currency->short_name = $request->getShortName();
        }
        if ($request->has('logo_url')) {
            $currency->logo_url = $request->getLogoUrl();
        }
        if ($request->has('price')) {
            $currency->price = $request->getPrice();
        }

        $currency->save();

        Session::flash('success_msg', 'Currency successfully updated!');
        return redirect()->route('currencies.show', $currency->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Currency $currency
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Currency $currency)
    {
        if (Gate::denies('delete', $currency)) {
            return redirect('/');
        }

        $currency->delete();

        Session::flash('success_msg', 'Currency successfully deleted!');
        return redirect()->route('currencies.index');
    }
}
