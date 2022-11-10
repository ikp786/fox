<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs        = Faq::all();
        $title       = 'faq';
        $data        = compact('title', 'faqs');
        return view('admin.faqs.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title       = 'faq';
        $data        = compact('title');
        return view('admin.faqs.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'question'    => 'required|max:255',
            'answer'      => 'required|max:500',
        ]);

        $save  =  new Faq();
        $save->fill($request->all());
        $save->save();
        return redirect()->route('admin.faqs.index')->with('success', 'Faq addd success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faqs        = Faq::find($id);
        $title       = 'faq';
        $data        = compact('title', 'faqs');
        return view('admin.faqs.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'question' => 'required|max:255',
            'answer'      => 'required|max:500',
        ]);

        $save  =  Faq::find($id);
        $save->fill($request->all());
        $save->save();
        return redirect()->route('admin.faqs.index')->with('success', 'Faq update success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Faq::find($id)->delete();
        return redirect()->back()->with('success','faq delete successfully');
    }
}
