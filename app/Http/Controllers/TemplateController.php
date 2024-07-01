<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Template;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = auth()->user()->templates;
        return Inertia::render('Template/Index', compact('templates'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'template' => 'required|array',
            'name' => 'string|unique:templates,name', // Ensure name is unique
        ]);
    
        $template = auth()->user()->templates()->create([
            'template' => $request->input('template'),
            'name' => $request->input('name'),
        ]);
    
        return response()->json([
            'template' => $template,
            'message' => 'Template created successfully.'
        ]);
    }

    public function getTemplateByName(Request $request, $name)
    {
        $template = auth()->user()->templates()->where('name', $name)->firstOrFail();
        return response()->json([
            'template' => $template,
        ]);
    }
    
    public function destroyByName(Request $request, $name)
    {
        $template = auth()->user()->templates()->where('name', $name)->firstOrFail();
        $template->delete();
        
        return response()->json(['message' => 'Template deleted successfully']);
    }
    

    public function destroy(Request $request, Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index');
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'template' => 'required|array',
        ]);

        $template->update([
            'template' => $request->input('template')
        ]);

        return redirect()->route('templates.index');
    }

    public function edit(Request $request, Template $template)
    {
        return Inertia::render('Template/Edit', [
            'template' => $template
        ]);
    }

}
