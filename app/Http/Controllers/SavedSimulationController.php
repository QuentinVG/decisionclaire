<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSimulationRequest;
use App\Models\SavedSimulation;
use App\Models\User;
use App\Support\ToolCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedSimulationController extends Controller
{
    public function store(SaveSimulationRequest $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_simulations.'.$request->string('pending_key'));

        if (! is_array($pending)) {
            return back()->withErrors(['pending_key' => 'Cette simulation n’est plus disponible. Relance le calcul pour la sauvegarder.']);
        }

        /** @var User $user */
        $user = $request->user();

        $simulation = $user->savedSimulations()->create([
            'tool_key' => (string) $pending['tool_key'],
            'title' => (string) $pending['title'],
            'input_data' => $pending['input_data'],
            'result_data' => $pending['result_data'],
        ]);

        $request->session()->forget('pending_simulations.'.$request->string('pending_key'));

        return redirect()
            ->route('simulations.show', $simulation)
            ->with('status', 'Simulation sauvegardée.');
    }

    public function show(SavedSimulation $simulation): View
    {
        $this->authorize('view', $simulation);

        return view('simulations.show', [
            'simulation' => $simulation,
            'tool' => ToolCatalog::get($simulation->tool_key),
            'result' => $simulation->result_data,
        ]);
    }

    public function duplicate(Request $request, SavedSimulation $simulation): RedirectResponse
    {
        $this->authorize('view', $simulation);

        /** @var User $user */
        $user = $request->user();

        $copy = $user->savedSimulations()->create([
            'tool_key' => $simulation->tool_key,
            'title' => $simulation->title.' (copie)',
            'input_data' => $simulation->input_data,
            'result_data' => $simulation->result_data,
        ]);

        return redirect()
            ->route('simulations.show', $copy)
            ->with('status', 'Simulation dupliquée.');
    }

    public function destroy(SavedSimulation $simulation): RedirectResponse
    {
        $this->authorize('delete', $simulation);
        $simulation->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Simulation supprimée.');
    }
}
