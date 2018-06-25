<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Module;

class AddonsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['modules'] = Module::all();
        if(!count($data['modules'])) {
            alert()->danger('You have no addons installed.');
        }

        return view('panel::addons.index', $data);
    }

    public function toggle($module_alias, Request $request)
    {
        foreach(Module::all() as $v) {
            if($module_alias == $v->alias) {
                $module_name = $v->getName();
                break;
            }
        }
        $module = Module::find($module_name);

        if($module) {
            if($module->active)
                $module->disable();
            else
                $module->enable();
        }

        return redirect('/panel/addons');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'POST',
            'url' => route('panel.pages.store'),
        ]);
        return view('panel::pages.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $page = new PageTranslation();
        $page->fill($request->all());
        $page->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $page = PageTranslation::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'PUT',
            'url' => route('panel.pages.update', $id),
            'model' => $page
        ]);
        return view('panel::pages.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $page = PageTranslation::findOrFail($id);
        $page->fill($request->all());
        $page->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
